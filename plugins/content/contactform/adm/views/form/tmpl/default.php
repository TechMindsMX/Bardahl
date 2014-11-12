<?php
/**
 * ------------------------------------------------------------------------
 * Plugin ContactForm for Joomla! 1.7 - 2.5
 * ------------------------------------------------------------------------
 * @copyright   Copyright (C) 2011-2012 Chartiermedia.com - All Rights Reserved.
 * @license     GNU/GPL, http://www.gnu.org/copyleft/gpl.html
 * @author:     Sebastien Chartier
 * @link:     http://www.chartiermedia.com
 * ------------------------------------------------------------------------
 *
 * @package	Joomla.Plugin
 * @subpackage  ContactForm
 * @version     1.12
 * @since	1.7
 */

defined('_JEXEC') or die;
$doc = JFactory::getDocument();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $doc->language; ?>" lang="<?php echo $doc->language; ?>" dir="<?php echo $doc->direction; ?>" >
    <head>
        <script type="text/javascript" src="<?php echo $site_url; ?>/media/system/js/mootools-core.js"></script>
        <script type="text/javascript" src="<?php echo $site_url; ?>/media/system/js/tabs.js"></script>
        <script type="text/javascript" src="<?php echo $site_url; ?>/media/system/js/validate.js"></script>
        <script type="text/javascript" src="<?php echo $site_url; ?>/plugins/content/contactform/adm/js/popup-contactform.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/plugins/content/contactform/adm/css/popup-contactform.css" />
        <?php
        $options = '{';
        $opt['onActive'] = (isset($params['onActive'])) ? $params['onActive'] : null;
        $opt['onBackground'] = (isset($params['onBackground'])) ? $params['onBackground'] : null;
        $opt['display'] = (isset($params['startOffset'])) ? (int) $params['startOffset'] : null;
        $opt['useStorage'] = (isset($params['useCookie']) && $params['useCookie']) ? 'true' : 'false';
        $opt['titleSelector'] = "'dt.tabs'";
        $opt['descriptionSelector'] = "'dd.tabs'";

        foreach ($opt as $k => $v) {
            if ($v) {
                $options .= $k . ': ' . $v . ',';
            }
        }

        if (substr($options, -1) == ',') {
            $options = substr($options, 0, -1);
        }

        $options .= '}';
        ?>
        <script type="text/javascript">
            window.addEvent('domready', function(){
                $$('dl#btnContactform.tabs').each(function(tabs){
                    new JTabs(tabs, <?php echo $options; ?>);
                });
            });

            var MAILTO_REQUIRED = '<?php echo str_replace('\'', '\\\'', JText::_('PLG_CONTENT_CONTACTFORM_MAILTO_MISSING')); ?>';
        </script>
    </head>
    <body>
        <form action="#" id="ContactFormForm">
            <dl class="tabs" id="btnContactform"><dt style="display:none;"></dt><dd style="display:none;">
                    <?php foreach ($form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.  ?>
                        <?php $fields = $form->getFieldset($fieldset->name); ?>
                        <?php if (count($fields)): ?>
                        </dd>
                        <dt class="tabs <?php echo $fieldset->name; ?>">
                            <span>
                                <h3>
                                    <a href="javascript:void(0);">
                                        <?php echo JText::_($fieldset->label); ?>
                                    </a>
                                </h3>
                            </span>
                        </dt>
                        <dd class="tabs">
                            <fieldset id="<?php echo $fieldset->name; ?>">
                                <?php if (isset($fieldset->label)):
                                    // If the fieldset has a label set, display it as the legend. ?>
                                    <legend><?php echo JText::_($fieldset->label); ?></legend>
                                <?php endif; ?>
                                <dl>
                                    <?php foreach ($fields as $field):
                                    // Iterate through the fields in the set and display them.  ?>
                                        <?php if ($field->hidden):
                                        // If the field is hidden, just display the input.?>
                                            <?php echo $field->input; ?>
                                        <?php else: ?>
                                            <dt>
                                                <?php echo $field->label; ?>
                                            </dt>
                                            <dd><?php echo $field->input; ?></dd>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dl>
                            </fieldset>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </dd></dl>
            <div>
                <?php echo JHtml::_('form.token'); ?>
                <p id="buttons">
                    <button type="button" onclick="if(CFPManager.onok())window.parent.SqueezeBox.close();"><?php echo JText::_('PLG_CONTENT_CONTACTFORM_INSERT') ?></button>
                    <button type="button" onclick="window.parent.SqueezeBox.close();"><?php echo JText::_('JCANCEL') ?></button>
                </p>
            </div>
        </form>
    </body>