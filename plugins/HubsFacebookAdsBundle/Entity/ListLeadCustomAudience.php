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
     * @var LeadList
     * */
    private $list;

    /**
     * @var Lead
     */
    private $customAudience;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('custom_audience_lead_list')
                ->setCustomRepositoryClass('MauticPlugin\HubsFacebookAdsBundle\Entity\ListLeadCustomAudienceRepository');

        $builder->createManyToOne('list', 'Mautic\LeadBundle\Entity\LeadList')
                ->isPrimaryKey()
                ->addJoinColumn('leadlist_id', 'id', false, false, 'CASCADE')
                ->build();

        $builder->createManyToOne('customAudience', 'MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudience')
                ->addJoinColumn('custom_audience_id', 'id', false, false, 'CASCADE')
                ->build();

        $builder->addDateAdded();
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
    public function setList($leadList)
    {
        $this->list = $leadList;
    }
}
