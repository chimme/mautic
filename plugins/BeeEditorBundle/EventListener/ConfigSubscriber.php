<?php

namespace MauticPlugin\BeeEditorBundle\EventListener;

use Mautic\ConfigBundle\ConfigEvents;
use Mautic\ConfigBundle\Event\ConfigBuilderEvent;
use Mautic\ConfigBundle\Event\ConfigEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;

/**
 * Class ConfigSubscriber.
 */
class ConfigSubscriber extends CommonSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ConfigEvents::CONFIG_ON_GENERATE => ['onConfigGenerate', 0],
            ConfigEvents::CONFIG_PRE_SAVE    => ['onConfigSave', 0],
        ];
    }

    /**
     * @param ConfigBuilderEvent $event
     */
    public function onConfigGenerate(ConfigBuilderEvent $event)
    {
        $event->addForm(
            [
                'formAlias'  => 'bee_config',
                'formTheme'  => 'BeeEditorBundle:FormTheme\Config',
                'parameters' => $event->getParametersFromConfig('BeeEditorBundle'),
            ]
        );
    }

    /**
     * @param ConfigEvent $event
     */
    public function onConfigSave(ConfigEvent $event)
    {
        /** @var array $values */
        $values = $event->getConfig();

        // Manipulate the values
        if (!empty($values['social_config']['twitter_handle_field'])) {
            $values['social_config']['twitter_handle_field'] = htmlspecialchars($values['social_config']['twitter_handle_field']);
        }

        // Set updated values
        $event->setConfig($values);
    }
}
