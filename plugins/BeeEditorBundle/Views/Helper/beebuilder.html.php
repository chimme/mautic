<?php if ($view['bee_template']->hasValidToken()) : ?>
    <script type="text/javascript">
        var BEE_LOCALE = "<?php echo $view['bee_template']->getBeeLocale(); ?>";
        var BEE_UID = "<?php echo $view['bee_template']->getBeeUID(); ?>";
        var BEE_TOKEN = <?php echo $view['bee_template']->getEncodedToken(); ?>;
    </script>
    <script src="https://app-rsrc.getbee.io/plugin/BeePlugin.js"></script>
    <div id="bee-plugin-container" data-template="<?php echo $view->escape($description) ?>"></div>
    <style>
        #bee-plugin-container {
            position: absolute;
            top:0;
            bottom:30px;
            left:0;
            right:0;
            z-index: 9999;
            display: none;
            background: rgba(0,0,0,.6);
            padding: 20px;
        }
    </style>
<?php endif; ?>