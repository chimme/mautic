
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