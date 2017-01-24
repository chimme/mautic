<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', $view['translator']->trans('hubs.fbAds.customAudience'));
$view['slots']->set('headerTitle', $view['translator']->trans('hubs.fbAds.customAudience'));

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:page_actions.html.php',
        [
            'templateButtons' => [
                'new' => true,
            ],
            'actionRoute' => 'hubs_fb_ca_action',
            'routeBase'   => 'form',
            'langVar'     => 'form.form',
        ]
    )
);

?>
<div class="panel panel-default bdr-t-wdh-0 mb-0">
<?php if (count($items)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered" id="formTable">
            <thead>
            <tr>
                <td class="col-actions">&nbsp;</td>
                <?php
//                 echo $view->render(
//                    'MauticCoreBundle:Helper:tableheader.html.php',
//                    [
//                        'checkall'        => 'true',
//                        'target'          => '#formTable',
//                        'routeBase'       => 'form',
//                        'templateButtons' => [
//                            'delete' => $permissions['facebookAds:addvertising:delete'],
//                        ],
//                    ]
//                );
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'text'    => 'mautic.core.id',
                        'class'   => 'col-form-id',
                        'default' => true,
                    ]
                );
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'text'    => 'mautic.core.name',
                        'class'   => 'col-form-name',
                        'default' => true,
                    ]
                );
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'text'    => 'mautic.core.created',
                        'class'   => 'col-form-name',
                        'default' => true,
                    ]
                );
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'text'    => 'hubs.fbAds.updatedOn',
                        'class'   => 'col-form-name',
                        'default' => true,
                    ]
                );
                ?>
            </tr>
            </thead>
            <tbody>
            <?php  foreach ($items as $item):  ?>
                <tr>
                     <td>
                        <?php
                        echo $view->render(
                            'MauticCoreBundle:Helper:list_actions.html.php',
                            [
                                'item'            => $item,
                                'templateButtons' => [
                                    'delete' => $security->hasEntityAccess(
                                        $permissions['facebookAds:addvertising:delete'],
                                        $permissions['facebookAds:addvertising:delete']
                                    ),
                                ],
                                'route'   => 'hubs_fb_ca_action',
                                'langVar' => 'form',
                            ]
                        );
                        ?>
                    </td>
                    <td class="visible-md visible-lg"><?php echo  $item['id']; ?></td>
                    <td class="visible-md visible-lg">
                            <?php echo $item['name']; ?>
                    </td>
                    <td class="visible-md visible-lg"><?php echo  $view['date']->toDate(new DateTime('@'.$item['time_created'])); ?></td>
                    <td class="visible-md visible-lg"><?php echo  $view['date']->toDate(new DateTime('@'.$item['time_updated'])); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php', ['tip' => 'mautic.form.noresults.tip']); ?>
<?php endif; ?>
</div>