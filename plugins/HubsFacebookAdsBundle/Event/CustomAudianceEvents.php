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

    /**
     * The hubs.custom_audience.pre_delete event is dispatched right before custom audience deleted.
     *
     * @var string
     */
    const CUSTOM_AUDIENCE_PRE_DELETE = 'hubs.custom_audience.pre_delete';

    /**
     * The hubs.custom_audience.post_delete event is dispatched right after custom audience deleted.
     *
     * @var string
     */
    const CUSTOM_AUDIENCE_POST_DELETE = 'hubs.custom_audience.post_delete';
}
