<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');
echo JLayoutHelper::render('joomla.content.categories_default', $this);?>
<div class="module-inner">
    <h3>Tipos de producto</h3>
</div>
<br/>
<?php
JLoader::register('fieldsattachHelper',  'components/com_fieldsattach/helpers/fieldsattach.php');
$categorias =$this->get('Items');
$i=0;
foreach($categorias as $key => $value) {
    $data[] = $value;
    ?>
    <div id="article_conteiner">
        <div class="article_image">
                    <span>
                <?php
                $value->imagen = json_decode($value->params);
                ?>
                        <img class="iconos_prod" src="<?php echo $value->imagen->image; ?>" />
        </div>
        <div class="article_title">
            <a href="index.php?option=<?php echo $data[$i]->get('extension'); ?>&view=category&id=<?php echo $data[$i]->get('id') ?>">
                <?php echo $data[$i]->get('title') ?>
            </a>
        </div>
        <div class="div_icons_prod">
            &nbsp;
        </div>
    </div>
    <?php
    $i++;
}
?>