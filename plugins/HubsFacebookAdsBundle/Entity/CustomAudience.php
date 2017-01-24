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
     * @var string
     */
    private $name;

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
                ->setCustomRepositoryClass('MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudienceRepository')
                ->addIndex(['custom_audience_id'], 'custom_audiance_search');

        $builder->addId();
        $builder->addField('name', 'string');
        $builder->createField('customAudienceId', 'boolean')
            ->columnName('custom_audience_id')
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
}
