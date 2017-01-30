<?php

return [
    'name'        => '55 hubs facebook ads extentions',
    'description' => 'Enables facebook ads extentions and customizing.',
    'version'     => '1.0',
    'author'      => '55weeks',
    'services'    => [
        'events' => [
            'mautic.hubs.menubuilder.subscriber' => [
                'class' => 'MauticPlugin\HubsFacebookAdsBundle\EventListener\MenuSubscriber',
            ],
            'mautic.hubs.lead.subscriber' => [
                'class'     => 'MauticPlugin\HubsFacebookAdsBundle\EventListener\LeadSubscriber',
                'arguments' => [
                    'hubs.fbads.model.customaudiance',
                ],
            ],
            'mautic.hubs.custom_audiance.subscriber' => [
                'class'     => 'MauticPlugin\HubsFacebookAdsBundle\EventListener\CustomAudianceSubscriber',
                'arguments' => [
                    'hubs.fbads.helper',
                ],
            ],
        ],
        'others' => [
            'hubs.fbads.helper' => [
                'class'     => 'MauticPlugin\HubsFacebookAdsBundle\Helpers\FacebookApiHelper',
                'arguments' => [
                    'mautic.helper.integration',
                ],
                'methodCalls' => [
                    'init' => ['%kernel.environment%', '%kernel.cache_dir%'],
                ],
            ],
        ],
        'models' => [
            'hubs.fbads.model.customaudiance' => [
                'class'     => 'MauticPlugin\HubsFacebookAdsBundle\Model\CustomAudienceModel',
                'arguments' => [
                    'mautic.helper.core_parameters',
                ],
            ],
        ],
    ],
    'routes' => [
        'main' => [
            'hubs_fb_ca_index' => [
                'path'       => '/ads/view/customaudience/{customaudienceId}',
                'controller' => 'HubsFacebookAdsBundle:CustomAudience:index',
                'defaults'   => ['customaudienceId' => null],
            ],
            'hubs_fb_ca_action' => [
                'path'       => '/ads/customaudience/{objectAction}/{objectId}',
                'controller' => 'HubsFacebookAdsBundle:CustomAudience:execute',
            ],
        ],
    ],
];
