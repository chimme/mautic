<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\CommonEntity;

/**
 * Class CustomAudience.
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
     * @param LeadList $list
     *
     * @return CustomAudience
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
     * @return CustomAudience
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
     * @return CustomAudience
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
