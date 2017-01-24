<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Helpers;

use FacebookAds\Session;

class FacebookApiSession extends Session
{
    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->appId       = (isset($data['client_id']) && $data['client_id']) ? $data['client_id'] : null;
        $this->appSecret   = (isset($data['client_secret']) && $data['client_secret']) ? $data['client_secret'] : null;
        $this->accessToken = (isset($data['access_token']) && $data['access_token']) ? $data['access_token'] : null;
        $this->adAccountId = (isset($data['add_account_id']) && $data['add_account_id']) ? $data['add_account_id'] : null;
    }

    /**
     * return boolean.
     */
    public function isValidSession()
    {
        return $this->appId && $this->appSecret && $this->accessToken && $this->adAccountId;
    }

    /**
     * return string.
     */
    public function getAdAccountId()
    {
        return $this->adAccountId;
    }

    /**
     * return string.
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
