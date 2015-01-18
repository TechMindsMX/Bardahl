<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<ul class="latestnews<?php echo $moduleclass_sfx; ?>">
<?php foreach ($list as $item) :  ?>
	<li itemscope itemtype="http://schema.org/Article">
		<a href="<?php echo $item->link; ?>" itemprop="url">
            <img id="img-tu-vida" src="<?php
            $images=json_decode($item->images);
            echo $images->image_intro; ?>"><br/>
            <div id="div-tu-vida" >
                <span itemprop="name"><?php  echo $item->title; ?></span>
            </div>
        </a>
	</li>
<?php endforeach; ?>
</ul>
