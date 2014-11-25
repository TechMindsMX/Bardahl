<?php

/**

 * @package     Joomla.Site

 * @subpackage  com_content

 *

 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt

 */



defined('_JEXEC') or die;



JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
// Create some shortcuts.

$params = &$this->item->params;

$n = count($this->items);
echo '
    <div class="module-inner">
        <h3>Tipos de producto</h3>
    </div>
    <br/>';
$listOrder = $this->escape($this->state->get('list.ordering'));

$listDirn = $this->escape($this->state->get('list.direction'));



// Check for at least one editable article

$isEditable = false;



if (!empty($this->items)) {

    foreach ($this->items as $article) {

        if ($article->params->get('access-edit')) {

            $isEditable = true;

            break;

        }

    }

}



?>



<?php if (empty($this->items)) : ?>



    <?php if ($this->params->get('show_no_articles', 1)) : ?>

        <p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>

    <?php endif; ?>



<?php else : ?>

    <div>

        <?php foreach ($this->items as $i => $article) :

            ?>

            <div class="cat-article">



                <?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>

                    <a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
                        <img class="article-catimg" src="<?php echo json_decode($article->images)->image_fulltext; ?>"/>

                        <span class="pleca-dorado"><div><?php echo $this->escape($article->title); ?></div></span>

                    </a>

                <?php else: ?>

                    <?php

                    echo $this->escape($article->title) . ' : ';

                    $menu = JFactory::getApplication()->getMenu();

                    $active = $menu->getActive();

                    $itemId = $active->id;

                    $link = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);

                    $returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug));

                    $fullURL = new JUri($link);

                    $fullURL->setVar('return', base64_encode($returnURL));

                    ?>

                    <a href="<?php echo $fullURL; ?>" class="register">

                        <?php echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?>

                    </a>

                <?php endif; ?>

                <?php if ($article->state == 0) : ?>

                    <span class="list-published label label-warning">

								<?php echo JText::_('JUNPUBLISHED'); ?>

							</span>

                <?php endif; ?>

                <?php if (strtotime($article->publish_up) > strtotime(JFactory::getDate())) : ?>

                    <span class="list-published label label-warning">

								<?php echo JText::_('JNOTPUBLISHEDYET'); ?>

							</span>

                <?php endif; ?>

                <?php if ((strtotime($article->publish_down) < strtotime(JFactory::getDate())) && $article->publish_down != '0000-00-00 00:00:00') : ?>

                    <span class="list-published label label-warning">

								<?php echo JText::_('JEXPIRED'); ?>

							</span>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>



