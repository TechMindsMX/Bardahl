<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;


$selected = 'selected="selected"';
$objWildcards = new ShowBizWildcards();
$arrWildcards = $objWildcards->getWildcardsSettingNames();
$arrCustomOptions = $objWildcards->getArrCustomOptions();

?>
<div class="settings_wrapper unite_settings_wide">
    <script type="text/javascript">
        var g_uniteDirPlagin = "showbiz";
        var g_settingsObj = {};
        g_settingsObj['form_slide_params'] = {};
        g_settingsObj['form_slide_params'].jsonControls = '{"enable_link":[{"name":"link","type":"show","value":"true"},{"name":"hr2","type":"show","value":"true"}]}';
        g_settingsObj['form_slide_params'].controls = JSON.parse(g_settingsObj['form_slide_params'].jsonControls);
    </script>
    <table class="form-table">	
        <tbody>
            <tr id="title_row" valign="top">
                <th scope="row">
                    Slide Title:
                </th>
                <td>
                    <input class="medium" id="title" name="title" value="<?php echo $this->title; ?>" type="text">
                    <span class="description">The title of the slide, will be shown in the slides list.</span>
                </td>
            </tr>
            <tr id="state_row" valign="top">
                <th scope="row">
                    State:
                </th>
                <td>
                    <select id="state" name="state">
                        <option value="published" <?php if ($this->params->get("state", "published") == "publish") echo "selected=selected" ?>>Published</option>
                        <option value="unpublished" <?php if ($this->params->get("state") == "unpublished") echo "selected=selected" ?>>Unpublished</option>
                    </select>       
                    <span class="description">The state of the slide. The unpublished slide will be excluded from the slider.</span>

                </td>
            </tr>
            <tr id="template_id_row" valign="top">
                <th scope="row">
                    Item Template:
                </th>
                <td>
                    <select id="template_id" name="template_id">
                        <option value="0" <?php if ($this->params->get("template_id", 0) == 0) echo $selected; ?>>[Not Selected, use global]</option>
                        <?php foreach ($this->templates as $template) { ?>
                            <option
                            <?php if ($this->params->get("template_id", 0) == $template->id) echo $selected; ?>
                                value="<?php echo $template->id ?>"><?php echo $template->title ?></option>
                            <?php }; ?>
                    </select>               
                    <span class="description">The template that set the look of the item (if not selected it will be taken from the slider global template)</span>

                </td>
            </tr>
            <tr id="hr1_row">
                <td colspan="4" style="text-align:left;" align="left">
                    <hr> 
                </td>
            </tr>
            <tr id="enable_link_row" valign="top">
                <th scope="row">
                    Enable Link:
                </th>
                <td>
                    <select id="enable_link" name="enable_link">
                        <option value="true" <?php if ($this->params->get("enable_link") == "true") echo $selected; ?>>Enable</option>
                        <option value="false" <?php if ($this->params->get("enable_link", "false") == "false") echo $selected; ?>>Disable</option>
                    </select>
                </td>
            </tr>
            <tr id="link_row" <?php if ($this->params->get("enable_link", "false") == "false") echo 'style="display:none;" '; ?> valign="top">
                <th scope="row">
                    Slide Link:
                </th>
                <td>
                    <input class="regular-text" id="link" name="link" type="text" value="<?php echo $this->params->get("link") ?>">     
                    <span class="description">A link on the whole slide pic</span>

                </td>
            </tr>
            <tr id="hr2_row" <?php if (!$this->params->get("enable_link", false)) echo 'style="display:none;'; ?>">
                <td colspan="4" style="text-align:left;" align="left">
                    <hr> 
                </td>
            </tr>
            <tr id="slide_image_row" valign="top">
                <th scope="row">
                    Slide Image:
                </th>
                <td>

                    <div id="divLayers" class="slide_layers setting-image-preview" style="<?php echo $this->styleLayers; ?>;"></div>
                    <?php $this->putOptionalField("image", $this->params->get("slide_image")); ?>

                    <script type="text/javascript">
//operate on slide image change
                        var obj = document.getElementById("jform_params_image");
                        obj.addEvent('change', function() {
                            var imageUrl = g_urlBase + this.value;
                            jQuery("#divLayers").css("background-image", "url('" + imageUrl + "')");
                        });

                    </script>


                </td>
            </tr>
            <tr id="slide_text_row" valign="top">
                <th scope="row">
                    Slide Text:
                </th>
                <td> <?php
                    $editor = JFactory::getEditor(null);
                    echo $editor->display('slide_text', $this->params->get("slide_text"), '100%', '300', '60', '15', false);
                    ?>
                    <br><br>
                </td>   
            </tr>
            
            <tr id="intro_text_row" valign="top">
                <th scope="row">
                    Intro Text:
                </th>
                <td> 
                	<textarea id="intro_text" name="intro_text" rows="6" cols="50"><?php echo $this->params->get("intro_text")?></textarea>
                	
					<span class="description">If the intro text is empty, the intro will be taken from the slide text</span>                	
                </td>   
            </tr>
            
            <tr id="showbiz_excerpt_limit_row" valign="top">
                <th scope="row">
                    Intro Text Limit:
                </th>
                <td>
                    <input class="small-text" id="showbiz_excerpt_limit" name="showbiz_excerpt_limit" type="text" value="<?php echo $this->params->get("showbiz_excerpt_limit"); ?>">                    
                    <span class="description">Overwrite the global intro words limit option for this slide</span>

                </td>
            </tr>
            <tr id="hr3_row">
                <td colspan="4" style="text-align:left;" align="left">
                    <hr> 
                </td>
            </tr>
            <tr id="youtube_id_row" valign="top">
                <th scope="row">
                    Youtube ID:
                </th>
                <td>
                    <input class="medium" id="youtube_id" name="youtube_id" type="text" value="<?php echo $this->params->get("youtube_id"); ?>">

                    <span class="description">The youtube ID, example: 9bZkp7q19f0</span>                </td>
            </tr>
            <tr id="vimeo_id_row" valign="top">
                <th scope="row">
                    Vimeo ID:
                </th>
                <td>
                    <input class="medium" id="vimeo_id" name="vimeo_id" type="text" value="<?php echo $this->params->get("vimeo_id"); ?>">   
                    <span class="description">The youtube ID, example: 18554749</span>

                </td>
            </tr>
            <tr id="hr4_row">
                <td colspan="4" style="text-align:left;" align="left">
                    <hr> 
                </td>
            </tr>
            <tr id="textitem5_row" valign="top">
                <td colspan="2">
                    <span class="settings_static_text">Those custom options can be used for variety of purposes in the templates section.</span>
                </td>
            </tr>

            <?php
            if ($arrWildcards) {
                foreach ($arrWildcards as $key => $value) {
                    ?>
                    <tr id="<?php echo $key ?>_row" valign="top">
                        <th scope="row">
                            <?php echo $value ?>
                        </th>
                        <td>
                            <input class="regular-text" id="<?php echo $key ?>" name="<?php echo $key ?>" value="<?php echo $this->params->get($key) ?>" type="text">
                        </td>
                    <tr>
                        <?php
                    };
                };
                ?>

        </tbody>
    </table>			
</div>
