<?php

namespace MauticPlugin\BeeEditorBundle\Helpers;

use Doctrine\ORM\EntityManager;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\BeeEditorBundle\Integration\BeeEditorIntegration;

class BeeAuthHelper
{
    //Your API Uid
    private $_uid = null;

    //Your API Client ID
    private $_client_id = null;

    //Your API Client Secret
    private $_client_secret = null;

    //token generated
    private $_token = null;

    //Url to call when authenicating
    private $_auth_url = 'https://auth.getbee.io/apiauth';

    private $availableLocales = [
        'en_US' => 'en-US',
        'es'    => 'es-ES',
        'fr'    => 'fr-FR',
        'it_IT' => 'it-IT',
        'pt_BR' => 'pt-BR',
        'id_ID' => 'id-ID',
        'ja'    => 'ja-JP',
        'zh_CN' => 'zh-CN',
        'de'    => 'de-DE',
        'da'    => 'da-DK',
        'sv'    => 'sv-SE',
        'pl_PL' => 'pl-PL',
        'ru'    => 'ru-RU',
        'ko_KR' => 'ko-KR',
        'nl'    => 'nl-NL',
    ];

    private $fallBackLocale = 'en-US';

    protected $em;

    protected $integrationHelper;

    /**
     * @param EntityManager     $em
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(EntityManager $em, IntegrationHelper $integrationHelper)
    {
        $this->em                = $em;
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @return type
     */
    public function getPluginIntegrationObject()
    {
        return $this->integrationHelper->getIntegrationObject(BeeEditorIntegration::PLUGIN_NAME);
    }

    /**
     * check if bee editor plugin is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        $plugin = $this->em->getRepository('MauticPluginBundle:Integration')
                 ->findBy(['name' => BeeEditorIntegration::PLUGIN_NAME, 'isPublished' => true]);
        if ($plugin) {
            $authkeys = $this->getPluginIntegrationObject()->getKeys();
            $this->setUid($authkeys['uid']);
            $this->setClientID($authkeys['clientid']);
            $this->setClientSecret($authkeys['clientsecret']);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Sets the uid that is provided by the API.
     *
     * @param string $_uid
     */
    public function setUid($uid)
    {
        $this->_uid = $uid;
    }

    /**
     * Sets the client id that is provided by the API.
     *
     * @param string $_uid
     */
    public function getUid()
    {
        return $this->_uid ?? '55hubs-template';
    }

    /**
     * Sets the client id that is provided by the API.
     *
     * @param string $client_id
     */
    public function setClientID($client_id)
    {
        $this->_client_id = $client_id;
    }

    /**
     * Set the client secret provided by the API.
     *
     * @param string string $client_secret
     */
    public function setClientSecret($client_secret)
    {
        $this->_client_secret = $client_secret;
    }

    /**
     * Call the API and get the access token, user and other information  required
     * to access the api.
     *
     * @param string $grant_type  : The grant type used to authenticate the API
     * @param string $json_decode : Return the result as an object or array. Default is object, to return set type to 'array'
     *
     * @return $mixed credentials
     */
    public function generateCredentials($grant_type = 'password', $json_decode = 'object')
    {
        if ($this->_token) {
            return true;
        }
        //set POST variables
        $fields = ['grant_type' => urlencode($grant_type), 'client_id' => urlencode($this->_client_id), 'client_secret' => urlencode($this->_client_secret)];

        //url-ify the data for the POST
        $fields_string = '';

        foreach ($fields as $key => $value) {
            $fields_string .= $key.'='.$value.'&';
        }

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->_auth_url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);

        return $this->_token = json_decode($result, true);
    }

    /**
     * get API token.
     *
     * @return bool
     */
    public function getToken()
    {
        if (!$this->isPublished()) {
            return false;
        }
        try {
            $this->generateCredentials();
        } catch (\Exception $ex) {
            $this->_token = ['error' => true];

            return false;
        }

        if ($this->_token && isset($this->_token['error'])) {
            return false;
        }

        return $this->_token;
    }

    /**
     * @return type
     */
    public function hasValidToken()
    {
        return ($this->getToken()) ? true : false;
    }

    /**
     * @param type $locale
     *
     * @return type
     */
    public function getBeeLocale($locale)
    {
        return (isset($this->availableLocales[$locale])) ? $this->availableLocales[$locale] : $this->fallBackLocale;
    }

    /**
     * check for active configuration.
     *
     * @return type
     */
    public function hasValidConfig()
    {
        return $this->_client_id && $this->_client_secret;
    }
}
