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
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\CommonEntity;

/**
 * Class Lead.
 */
class CustomAudience extends CommonEntity
{
    /**
     * @var
     */
    private $id;

    /**
     * @var
     */
    private $list;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $customAudienceId;

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('custom_audience')
                ->setCustomRepositoryClass('MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudienceRepository');

        $builder->addIdColumns();
        $builder->createField('customAudienceId', 'string')
                ->columnName('custom_audience_id')
                ->build();
        $builder->createManyToOne('list', 'Mautic\LeadBundle\Entity\LeadList')
                ->addJoinColumn('leadlist_id', 'id', false, false, 'NO ACTION')
                ->build();
    }

    /**
     * @param ApiMetadataDriver $metadata
     */
    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->setGroupPrefix('custom_audience')
                ->addListProperties(
                        [
                            'name',
                            'custom_audience_id',
                        ]
                )
                ->build();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param string $name
     *
     * @return Name
     */
    public function setList(\Mautic\LeadBundle\Entity\LeadList $list)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomAudienceId()
    {
        return $this->customAudienceId;
    }

    /**
     * @param string $customAudienceId
     *
     * @return Tag
     */
    public function setCustomAudienceId($customAudienceId)
    {
        $this->customAudienceId = $customAudienceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Tag
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
