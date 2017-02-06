<?php

namespace MauticPlugin\HubsSlugGeneratorBundle\Helper;

use Doctrine\ORM\EntityManager;

class SlugGeneratorHelper
{
    protected $em;
    protected $slugField;

    public function __construct(EntityManager $em, $slugField = false)
    {
        $this->em = $em;

        $this->slugField = $slugField;
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
        $col   = 'l.'.$this->slugField;
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
}
