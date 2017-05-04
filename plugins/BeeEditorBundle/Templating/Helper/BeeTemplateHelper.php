<?php

namespace MauticPlugin\BeeEditorBundle\Templating\Helper;

use Mautic\CoreBundle\Factory\MauticFactory;
use Symfony\Component\Templating\Helper\Helper;

class BeeTemplateHelper extends Helper
{
    /**
     * @var \MauticPlugin\BeeEditorBundle\Helpers\BeeAuthHelper
     */
    protected $helper;

    /**
     * @var
     */
    protected $locale;
    protected $beeUid;

    /**
     * @param MauticFactory $factory
     */
    public function __construct(MauticFactory $factory)
    {
        $this->locale     = $factory->getRequest()->getLocale();
        $this->beeUid     = $factory->getParameter('bee_uid');
        $this->authHelper = $factory->getHelper('bee.auth.helper');
    }

    public function getBeeLocale()
    {
        return $this->authHelper->getBeeLocale($this->locale);
    }

    public function getBeeUID()
    {
        return $this->beeUid ?? '55hubs-template';
    }

    public function hasValidToken()
    {
        return $this->authHelper->hasValidToken();
    }

    public function getEncodedToken()
    {
        return json_encode($this->authHelper->getToken());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bee_helper';
    }
}
