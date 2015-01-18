<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;
?>

<div id="dialog_preview_sliders" class="dialog_preview_sliders" title="Preview Slider" style="display:none;">
    <iframe id="frame_preview_slider" name="frame_preview_slider"></iframe>
</div>

<form id="form_preview" name="form_preview" action="" target="frame_preview_slider" method="post">
    <input type="hidden" name="client_action" value="preview_slider">
    <input type="hidden" id="preview_sliderid" name="sliderid" value="">
</form>
<script>
 var g_urlAjaxActions = "index.php?option=com_uniteshowbiz&view=templates&layout=ajax";
</script>