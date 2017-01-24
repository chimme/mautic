<?php

namespace MauticPlugin\HubsFacebookAdsBundle\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\MenuEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;

/**
 * Class MenuSubscriber.
 */
class MenuSubscriber extends CommonSubscriber
{
    //put your code here
    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::BUILD_MENU => ['onMenuBuild'],
        ];
    }

    public function onMenuBuild(MenuEvent $event)
    {
        $plugin = $this->em->getRepository('MauticPluginBundle:Integration')
                ->findBy(['name' => 'FacebookAds', 'isPublished' => true]);
        if (!$plugin) {
            return;
        }
        if ($event->getType() != 'main') {
            return;
        }
        $main = [
            'items' => [
                'Facebook Ads' => [
                    'priority' => 10,
                    'children' => [
                        'Custom Audiences' => [
                            'route' => 'hubs_fb_ca_index',
                        ],
                    ],
                ],
            ],
        ];
        $event->addMenuItems($main);
    }
}
