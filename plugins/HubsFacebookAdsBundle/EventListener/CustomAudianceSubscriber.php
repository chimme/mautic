<?php

namespace MauticPlugin\HubsFacebookAdsBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use MauticPlugin\HubsFacebookAdsBundle\Event\CustomAudianceChangeEvent;
use MauticPlugin\HubsFacebookAdsBundle\Event\CustomAudianceEvents;

class CustomAudianceSubscriber extends CommonSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            CustomAudianceEvents::CUSTOM_AUDIENCE_ADD    => ['onCustomAudienceChange'],
            CustomAudianceEvents::CUSTOM_AUDIENCE_REMOVE => ['onCustomAudienceChange'],
        ];
    }

    public function onCustomAudienceChange(CustomAudianceChangeEvent $event)
    {
        $lead   = $event->getLeads();
        $action = $event->wasAdded();
    }
}
