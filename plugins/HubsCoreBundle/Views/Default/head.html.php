<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>
<head>
    <meta charset="UTF-8" />
    <title>55 hubs - by 55 weeks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo $view['assets']->getUrl('media/images/favicon.ico') ?>" />

    <?php echo $view['assets']->outputSystemStylesheets(); ?>

    <?php echo $view->render('MauticCoreBundle:Default:script.html.php'); ?>
    <?php $view['assets']->outputHeadDeclarations(); ?>

    <script>
        (function(t,a,l,k,u,s,e){if(!t[u]){t[u]=function(){(t[u].q=t[u].q||[]).push(arguments)},t[u].l=1*new Date();s=a.createElement(l),e=a.getElementsByTagName(l)[0];s.async=1;s.src=k;e.parentNode.insertBefore(s,e)}})(window,document,'script','//www.talkus.io/plugin.beta.js','talkus');
        talkus('init', 'qhmAwqHXMQeEy8oyK', {
            id: '<?php echo $app->getUser()->getUsername();?>',
            name: '<?php echo $app->getUser()->getName();?>',
            email: '<?php echo $app->getUser()->getEmail();?>'
        })
    </script>
    <style>
        div.talkus-plugin.talkus-active.talkus-minimized {
            transform: none !important;
            -webkit-transform: none !important;
            -ms-transform: none !important;
        }
    </style>
</head>
