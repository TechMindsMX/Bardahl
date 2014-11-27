<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
    <div class="articlelanding-page ">

        <?php echo JLayoutHelper::render('joomla.content.categories_default', $this); ?>

    </div>
    <div class="module-inner">
        <h3 class="module-title ">
            <span>Tipos de producto</span>
        </h3>
    </div>

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

        <div class="article_titl">

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