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
JHtml::addIncludePath(T3_PATH.'/html/com_content');
JHtml::addIncludePath(dirname(dirname(__FILE__)));
JHtml::_('behavior.caption');
JLoader::register('fieldsattachHelper',  'components/com_fieldsattach/helpers/fieldsattach.php');
$categorias =$this->get('Items');
$i=0;


?>
    <h2 class="tit"> <?php  echo $this->data->title; ?><br><br></h2>

<?php
echo $this->data->description;
foreach($categorias as $key => $value) {
    $data[]     = $value;
    $jsonimg    = $data[$i]->images;
    $decode_img = json_decode($jsonimg);
    $imag       = new ContentModelCategory();
    $img   = $imag->getFieldsImage($data[$i]->id);
    ?>

    <div id="article_conteiner">
    <div id="article_image">
                    <span>
                        <img id="article_img" src="<?php echo $decode_img->image_intro;  ?>"/></span>
    </div>
    <div id="article_title">
        <a href="index.php?option=com_content&catid=<?php echo $data[$i]->catid; ?>&view=article&id=<?php echo $data[$i]->id; ?>&Itemid=186">
            <?php echo $data[$i]->title; ?>
        </a>
    </div>
        <div class="div_icons_prod">
            <?php
            foreach($img as $value){
                ?>
                <img class="iconos_prod" src="<?php echo $value->image1; ?>" />&nbsp;
            <?php
            }
            ?>
        </div>
    </div>

    <?php
    $i++;
}

