<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Event;

use MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudience;

class CustomAudianceChangeEvent
{
    private $lead;
    private $leads;
    private $customAudience;
    private $added;

    /**
     * @param Lead           $lead
     * @param customAudience $customAudience
     */
    public function __construct($leads, CustomAudience $customAudience, $added = true)
    {
        if (is_array($leads)) {
            $this->leads = $leads;
        } else {
            $this->lead = $leads;
        }
        $this->customAudience = $customAudience;
        $this->added          = $added;
    }

    /**
     * Returns the Lead entity.
     *
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * Returns batch array of leads.
     *
     * @return array
     */
    public function getLeads()
    {
        return $this->leads;
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
