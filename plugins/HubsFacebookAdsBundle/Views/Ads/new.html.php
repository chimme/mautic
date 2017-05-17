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
$view['slots']->set('headerTitle', $view['translator']->trans('hubs.fbAds.customAudience.new'));
?>
<?php echo $view['form']->start($form); ?>
<div class="box-layout">
    <!-- container -->
    <div class="col-md-12 bg-auto height-auto bdr-r">
        <div class="pr-lg pl-lg pt-md pb-md">
            <div class="col-md-6">
                <?php
                echo $view['form']->row($form['list']);
                echo $view['form']->row($form['description']);
                ?>
            </div>
        </div>
    </div>
</div>
<?php
echo $view['form']->end($form);
?>
