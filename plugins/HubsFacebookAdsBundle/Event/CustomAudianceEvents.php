<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Event;

final class CustomAudianceEvents
{
    /**
     * The hubs.custom_audience_add event is dispatched right after a leads are added in custom audience.
     *
     * @var string
     */
    const CUSTOM_AUDIENCE_ADD = 'hubs.custom_audience_add';

    /**
     * The hubs.custom_audience_add event is dispatched right after a lead are removed in custom audience.
     *
     * @var string
     */
    const CUSTOM_AUDIENCE_REMOVE = 'hubs.custom_audience_remove';
}
