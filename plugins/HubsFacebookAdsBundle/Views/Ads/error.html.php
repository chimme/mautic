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
?>
<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <div class="panel-body">
        <div class="box-layout">
            <div class="alert alert-danger">
                <p>
                    <?php if (isset($error)) {
    echo $error;
}
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>