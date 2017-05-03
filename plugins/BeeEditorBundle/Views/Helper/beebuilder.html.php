<?php if ($view['bee_template']->hasValidToken()) : ?>
    <script type="text/javascript">
        var BEE_LOCALE = "<?php echo $view['bee_template']->getBeeLocale(); ?>";
        var BEE_UID = "<?php echo $view['bee_template']->getBeeUID(); ?>";
    </script>
    <script src="https://app-rsrc.getbee.io/plugin/BeePlugin.js"></script>
    <div id="bee-plugin-container" data-template="<?php echo $view->escape($description) ?>"></div>
    <style>
        #bee-plugin-container {
            position: absolute;
            top:5px;
            bottom:30px;
            left:5px;
            right:5px;
            z-index: 9999;
            display: none;
        }
    </style>
<?php endif; ?>