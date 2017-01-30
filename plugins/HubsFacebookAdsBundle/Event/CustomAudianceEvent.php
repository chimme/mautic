<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Event;

use MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudience;
use Symfony\Component\EventDispatcher\Event;

class CustomAudianceEvent extends Event
{
    private $customAudience;
    private $added;

    /**
     * @param customAudience $customAudience
     * @param bool           $added
     */
    public function __construct(CustomAudience $customAudience, $added = true)
    {
        $this->customAudience = $customAudience;
        $this->added          = $added;
    }

    /**
     * @return LeadList|List
     */
    public function getCustomAudience()
    {
        return $this->customAudience;
    }

    /**
     * @return bool
     */
    public function wasAdded()
    {
        return $this->added;
    }

    /**
     * @return bool
     */
    public function wasRemoved()
    {
        return !$this->added;
    }
}
