<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Helpers;

use FacebookAds\Http\Client;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\HubsFacebookAdsBundle\Integration\FacebookAdsIntegration;

class FacebookApiHelper
{
    private $integrationHelper;
    private $api;

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
        $session   = new FacebookApiSession($this->getPluginIntegrationObject()->getKeys());
        $this->api = new \FacebookAds\Api(new Client(), $session);
        \FacebookAds\Api::setInstance($this->api);
        if ($env == 'dev') {
            $fd = fopen($cacheDir.'/fb_api_logs.txt', 'a');
            $this->api->setLogger(new \FacebookAds\Logger\CurlLogger($fd));
        }
    }

    public function getApi()
    {
        return $this->api;
    }
}
