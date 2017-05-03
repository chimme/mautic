<?php

return [
    'name'        => 'BEE email editor',
    'description' => 'Enables BEE email editor in mautic.',
    'version'     => '1.0',
    'services'    => [
        'events' => [
            '55hubs.bee.email.editor' => [
                'class' => 'MauticPlugin\BeeEditorBundle\EventListener\ConfigSubscriber',
            ],
        ],
        'forms' => [
            '55hubs.form.type.bee.editor' => [
                'class' => 'MauticPlugin\BeeEditorBundle\Form\Type\ConfigType',
                'alias' => 'bee_config',
            ],
        ],
        'other' => [
            'mautic.helper.bee.auth.helper' => [
                'class'     => 'MauticPlugin\BeeEditorBundle\Helpers\BeeAuthHelper',
                'arguments' => ['%mautic.bee_client_id%', '%mautic.bee_client_secret%'],
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
    'parameters' => [
        'bee_client_id' => null,
    'bee_client_secret' => null,
    'bee_uid'           => null,
    ],
];
