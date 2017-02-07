<?php

namespace MauticPlugin\HubsSlugGeneratorBundle\Helper;

use Doctrine\ORM\EntityManager;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\HubsSlugGeneratorBundle\Integration\SlugGeneratorIntegration;

class SlugGeneratorHelper
{
    protected $em;
    protected $slugField = false;
    protected $integrationHelper;
    protected $contacts     = [];
    const DEFAULT_SLUG_NAME = 'slug';

    public function __construct(EntityManager $em, IntegrationHelper $integrationHelper)
    {
        $this->em                = $em;
        $this->integrationHelper = $integrationHelper;
        $this->setSlugFieldName();
    }

    public function getPluginIntegrationObject()
    {
        return $this->integrationHelper->getIntegrationObject(SlugGeneratorIntegration::PLUGIN_NAME);
    }

    public function setSlugFieldName()
    {
        $plugin = $this->em->getRepository('MauticPluginBundle:Integration')
                 ->findBy(['name' => SlugGeneratorIntegration::PLUGIN_NAME, 'isPublished' => true]);
        if (!$plugin) {
            return false;
        }
        $integrationObj = $this->getPluginIntegrationObject();
        if (!$integrationObj) {
            return false;
        }
        $fields          = $integrationObj->getKeys();
        $this->slugField = $fields['slugfield'];
    }

    public function getSlugFieldName()
    {
        return $this->slugField;
    }

    public function clean($string)
    {
        // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string);
        // Removes special chars.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        // Replaces multiple hyphens with single one.
        return preg_replace('/-+/', '-', $string);
    }

    public function getSlugText($firstName, $lastName)
    {
        if (!$firstName || !$lastName) {
            return false;
        }
        $strToProcess = $string = $this->clean($firstName.'-'.$lastName);
        $noToAdd      = '';
        while (1) {
            if (false == $count = $this->isContactSlugExists($strToProcess)) {
                break;
            }
            $noToAdd = ($noToAdd == '') ? $count : $noToAdd + 1;
            $strToProcess .= $noToAdd;
        }

        return $string.$noToAdd;
    }

    public function isContactSlugExists($string, $id = null)
    {
        $repo  = $this->em->getRepository('MauticLeadBundle:Lead');
        $table = $this->em->getClassMetadata($repo->getClassName())->getTableName();
        $col   = 'l.'.$this->getSlugFieldName();
        $conn  = $this->em->getConnection();
        $q     = $conn->createQueryBuilder()
            ->select("COUNT($col) as cnt")
            ->from($table, 'l');

        $q->where("$col = :search")
            ->setParameter('search', "{$string}");

        if ($id) {
            $q->andWhere('l.id != :idVal')
                ->setParameter('idVal', $id);
        }
        $results = $q->execute()->fetchAll();
        if ($results[0]['cnt'] == 0) {
            return false;
        }
        $q = $conn->createQueryBuilder()
            ->select("COUNT($col) as cnt")
            ->from($table, 'l');

        $q->where($q->expr()->isNotNull($col))
            ->andWhere("$col LIKE :search")
            ->setParameter('search', "{$string}%");
        if ($id) {
            $q->andWhere('l.id != :idVal')
                ->setParameter('idVal', $id);
        }
        $results = $q->execute()->fetchAll();

        return $results[0]['cnt'] > 0 ? $results[0]['cnt'] : false;
    }

    public function generateAllContactSlugs()
    {
        if (!$this->slugField) {
            return;
        }
        $this->contacts = $this->getAllContacts();
        $slugsGenerated = [];
        foreach ($this->contacts as $key => $contact) {
            $cnt          = 0;
            $strToProcess = $string = $this->clean($contact['firstname'].'-'.$contact['lastname']);
            while (isset($slugsGenerated[$strToProcess])) {
                $strToProcess = $string.(++$cnt);
            }
            $slugsGenerated[$strToProcess] = true;
            $this->contacts[$key]['slug']  = $strToProcess;
        }
        $this->updateContactSlug();
    }

    private function getAllContacts()
    {
        $repo  = $this->em->getRepository('MauticLeadBundle:Lead');
        $table = $this->em->getClassMetadata($repo->getClassName())->getTableName();
        $col   = 'l.'.$this->getSlugFieldName();
        $conn  = $this->em->getConnection();
        $q     = $conn->createQueryBuilder()
            ->select("l.id, $col as slug, l.firstname, l.lastname")
            ->from($table, 'l')
            ->where('l.firstname IS NOT NULL')
            ->andWhere('l.lastname IS NOT NULL');

        return $q->execute()->fetchAll();
    }

    private function updateContactSlug()
    {
        $col = 'l.'.$this->getSlugFieldName();
        $qb  = $this->em->getConnection()->createQueryBuilder();
        foreach ($this->contacts as $contact) {
            $q = $qb->update('leads', 'l')
                    ->set("{$col}", $qb->expr()->literal($contact['slug']))
                    ->where('l.id = :Id')
                    ->setParameter('Id', $contact['id']);
            $q->execute();
        }
    }
}
