<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\HubsFacebookAdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

/**
 * Class ListLead.
 */
class ListLeadCustomAudience
{
    /**
     * @var id
     * */
    private $id;

    /**
     * @var Lead
     * */
    private $lead;

    /**
     * @var Lead
     */
    private $customAudience;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var int
     */
    private $isRemoved;

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->addId();
        $builder->setTable('custom_audience_lead_list')
                ->setCustomRepositoryClass('MauticPlugin\HubsFacebookAdsBundle\Entity\ListLeadCustomAudienceRepository');

        $builder->createManyToOne('customAudience', 'MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudience')
                ->addJoinColumn('custom_audience_id', 'id', false, false, 'CASCADE')
                ->build();

        $builder->addLead(false, 'NO ACTION', false);
        $builder->createField('email', 'string')
                ->build();
        $builder->createField('isRemoved', 'boolean')
                ->columnName('is_removed')
                ->build();
        $builder->addDateAdded();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $date
     */
    public function setDateAdded($date)
    {
        $this->dateAdded = $date;
    }

    /**
     * @return mixed
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @param mixed $customAudience
     */
    public function setCustomAudience($customAudience)
    {
        $this->customAudience = $customAudience;
    }

    /**
     * @return CustomAudience
     */
    public function getCustomAudience()
    {
        return $this->customAudience;
    }

    /**
     * @param LeadList $leadList
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
    }

    /**
     * @return email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getIsRemoved()
    {
        return $this->isRemoved;
    }

    /**
     * @param $removed
     */
    public function setIsRemoved($removed)
    {
        $this->isRemoved = $removed;

        return $this;
    }
}
