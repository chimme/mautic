<?php

namespace MauticPlugin\HubsSlugGeneratorBundle;

use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\LeadBundle\Entity\LeadField;
use Mautic\PluginBundle\Bundle\PluginBundleBase;
use Mautic\PluginBundle\Entity\Plugin;
use MauticPlugin\HubsSlugGeneratorBundle\Helper\SlugGeneratorHelper;

class HubsSlugGeneratorBundle extends PluginBundleBase
{
    /**
     * @param Plugin        $plugin
     * @param MauticFactory $factory
     * @param null          $metadata
     * @param null          $installedSchema
     *
     * @throws \Exception
     */
    public static function onPluginInstall(Plugin $plugin, MauticFactory $factory, $metadata = null, $installedSchema = null)
    {
        $label = (!$factory->getParameter('slug_field_label')) ? SlugGeneratorHelper::DEFAULT_SLUG_NAME : $factory->getParameter('slug_field_label');
        $model = $factory->getModel('lead.field');
        $field = new LeadField();
        $field->setIsPubliclyUpdatable(true);
        $field->setIsUniqueIdentifer(true);
        $field->setType('text');
        $field->setIsPublished(true);
        $field->setGroup('core');
        $field->setAlias($label);
        $field->setLabel($label);
        $model->saveEntity($field);
        parent::onPluginInstall($plugin, $factory, $metadata, $installedSchema);
    }

    public function getParent()
    {
        return 'MauticLeadBundle';
    }
}
