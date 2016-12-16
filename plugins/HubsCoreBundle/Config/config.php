<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => '55 hubs core extentions',
    'description' => 'Enables core extentions and customizing for 55 hubs mautic.',
    'version'     => '1.0',
    'author'      => '55weeks',

    'services' => [
        'events' => [
            'mautic.hubs.emailbuilder.subscriber' => [
                'class'     => 'MauticPlugin\HubsCoreBundle\EventListener\BuilderSubscriber',
                'arguments' => 'mautic.helper.theme',
            ],
        ],
    ],
];
