<?php

return [
    'name'        => 'BEE email editor',
    'description' => 'Enables BEE email editor in mautic.',
    'version'     => '1.0',
    'services'    => [
        'other' => [
            'mautic.helper.bee.auth.helper' => [
                'class'     => 'MauticPlugin\BeeEditorBundle\Helpers\BeeAuthHelper',
                'arguments' => ['doctrine.orm.default_entity_manager', 'mautic.helper.integration'],
            ],
        ],
        'helpers' => [
            '55hubs.helper.template.beeeditor' => [
                'class'     => 'MauticPlugin\BeeEditorBundle\Templating\Helper\BeeTemplateHelper',
                'arguments' => 'mautic.factory',
                'alias'     => 'bee_template',
            ],
        ],
    ],
    'routes' => [
        'main' => [
            'hubs_bee_generate_token' => [
                'path'       => '/generatebeetoken',
                'controller' => 'BeeEditorBundle:Index:generate',
            ],
        ],
    ],
];
