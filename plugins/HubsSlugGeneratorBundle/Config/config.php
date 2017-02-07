<?php

return [
    'name'        => '55 hubs slug generator',
    'description' => 'Add and manage slug field in contact.',
    'version'     => '1.0',
    'author'      => '55weeks',
    'routes'      => [
        'main' => [
            'hubs_slug_generate' => [
                'path'       => '/generate/slug',
                'controller' => 'HubsSlugGeneratorBundle:SlugGenerator:generate',
            ],
        ],
    ],
    'services' => [
        'events' => [
            'hubs.sluggenerator.lead.subscriber' => [
                'class'     => 'MauticPlugin\HubsSlugGeneratorBundle\EventListener\LeadSubscriber',
                'arguments' => [
                    'hubs.helper.slug.generator',
                ],
            ],
        ],
        'forms' => [
            'mautic.form.type.lead' => [
                'class'     => 'MauticPlugin\HubsSlugGeneratorBundle\Form\Type\LeadType',
                'arguments' => ['mautic.factory', 'mautic.lead.model.company', 'hubs.helper.slug.generator'],
                'alias'     => 'lead',
            ],
        ],
        'others' => [
            'hubs.helper.slug.generator' => [
                'class'     => 'MauticPlugin\HubsSlugGeneratorBundle\Helper\SlugGeneratorHelper',
                'arguments' => [
                    'doctrine.orm.default_entity_manager', 'mautic.helper.integration',
                ],
            ],
        ],
    ],
];
