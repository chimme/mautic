<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Helpers;

use FacebookAds\Http\Client;
use FacebookAds\Object\CustomAudience;
use FacebookAds\Object\Values\CustomAudienceTypes;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\HubsFacebookAdsBundle\Integration\FacebookAdsIntegration;

class FacebookApiHelper
{
    private $integrationHelper;
    private $api;
    private $session;

    public function __construct(IntegrationHelper $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    public function getPluginIntegrationObject()
    {
        return $this->integrationHelper->getIntegrationObject(FacebookAdsIntegration::PLUGIN_NAME);
    }

    public function init($env = false, $cacheDir = false)
    {
        $this->session = new FacebookApiSession($this->getPluginIntegrationObject()->getKeys());
        $this->api     = new \FacebookAds\Api(new Client(), $this->session);
        \FacebookAds\Api::setInstance($this->api);
        if ($env == 'dev') {
            $fd = fopen($cacheDir.'/fb_api_logs.txt', 'a');
            $this->api->setLogger(new \FacebookAds\Logger\CurlLogger($fd));
        }
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getApi()
    {
        return $this->api;
    }

    public function updateUsers($leads, $customAudience, $action = true)
    {
        $audience   = new CustomAudience($customAudience->getCustomAudienceId());
        $leadsEmail = [];
        foreach ($leads as $lead) {
            if ($lead['email']) {
                $leadsEmail[] = $lead['email'];
            }
        }
        if ($action) {
            $audience->addUsers($leadsEmail, CustomAudienceTypes::EMAIL);
        } else {
            $audience->removeUsers($leadsEmail, CustomAudienceTypes::EMAIL);
        }
    }
}
