<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
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
        if (typeof Mautic !== 'undeined' && typeof mQuery !== 'undeined') {
            Mautic.generatePageTitle = function (route) {
                if (-1 !== route.indexOf('view')) {
                    //loading view of module title
                    var currentModule = route.split('/')[3];

                    //check if we find spans
                    var titleWithHTML = mQuery('.page-header h3').find('span.span-block');
                    var currentModuleItem = '';

                    if (1 < titleWithHTML.length) {
                        currentModuleItem = titleWithHTML.eq(0).text() + ' - ' + titleWithHTML.eq(1).text();
                    } else {
                        currentModuleItem = mQuery('.page-header h3').text();
                    }

                    mQuery('title').html(currentModule[0].toUpperCase() + currentModule.slice(1) + ' | ' + currentModuleItem + ' | 55 hubs - by 55 weeks');
                } else {
                    //loading basic title
                    mQuery('title').html(mQuery('.page-header h3').html() + ' | 55 hubs - by 55 weeks');
                }
            };
        }
        (function(t,a,l,k,u,s,e){if(!t[u]){t[u]=function(){(t[u].q=t[u].q||[]).push(arguments)},t[u].l=1*new Date();s=a.createElement(l),e=a.getElementsByTagName(l)[0];s.async=1;s.src=k;e.parentNode.insertBefore(s,e)}})(window,document,'script','//www.talkus.io/plugin.beta.js','talkus');
        talkus('init', 'qhmAwqHXMQeEy8oyK', {
            id: '<?php echo is_object($app->getUser()) ? $app->getUser()->getUsername() : ''; ?>',
            name: '<?php echo is_object($app->getUser()) ? $app->getUser()->getName() : ''; ?>',
            email: '<?php echo is_object($app->getUser()) ? $app->getUser()->getEmail() : ''; ?>'
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
