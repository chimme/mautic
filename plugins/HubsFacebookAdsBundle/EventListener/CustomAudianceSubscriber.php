<?php

namespace MauticPlugin\HubsFacebookAdsBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use MauticPlugin\HubsFacebookAdsBundle\Event\CustomAudianceChangeEvent;
use MauticPlugin\HubsFacebookAdsBundle\Event\CustomAudianceEvent;
use MauticPlugin\HubsFacebookAdsBundle\Event\CustomAudianceEvents;
use MauticPlugin\HubsFacebookAdsBundle\Helpers\FacebookApiHelper;

class CustomAudianceSubscriber extends CommonSubscriber
{
    protected $apiHelper;

    public function __construct(FacebookApiHelper $apiHelper)
    {
        $this->apiHelper = $apiHelper;
    }

    public static function getSubscribedEvents()
    {
        return [
            CustomAudianceEvents::CUSTOM_AUDIENCE_ADD        => ['onCustomAudienceChange'],
            CustomAudianceEvents::CUSTOM_AUDIENCE_REMOVE     => ['onCustomAudienceChange'],
            CustomAudianceEvents::CUSTOM_AUDIENCE_PRE_DELETE => ['onCustomAudienceDelete'],
        ];
    }

    public function onCustomAudienceChange(CustomAudianceChangeEvent $event)
    {
        $leads          = $event->getLeads();
        $customAudience = $event->getCustomAudience();
        $action         = ($event->wasAdded()) ? true : false;
        $this->apiHelper->updateUsers($leads, $customAudience, $action);
    }

    public function onCustomAudienceDelete(CustomAudianceEvent $event)
    {
        $customAudience = $event->getCustomAudience();
        $this->apiHelper->deleteCustomAudience($customAudience);
    }
}
