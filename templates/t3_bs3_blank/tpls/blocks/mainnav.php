<?php

/**

 * @package   T3 Blank

 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.

 * @license   GNU General Public License version 2 or later; see LICENSE.txt

 */



defined('_JEXEC') or die;

?>



<!-- MAIN NAVIGATION -->

<nav id="t3-mainnav" class="wrap navbar navbar-default t3-mainnav">

	<a href="<?php echo JUri::base(); ?>" class="homelink"><img src="templates/t3_bs3_blank/images/site/bardahl.png"/></a>
    <div class="back" id="img_left">
     <img  src="templates/t3_bs3_blank/images/site/line_right_submenu.png"/>
    </div>
    <div class="back"  id="img_left2">
        <img src="templates/t3_bs3_blank/images/site/line_right_submenu.png">
    </div>

	<div class="container">



		<!-- Brand and toggle get grouped for better mobile display -->

		<div class="navbar-header">


			<?php if ($this->getParam('navigation_collapse_enable', 1) && $this->getParam('responsive', 1)) : ?>

				<?php $this->addScript(T3_URL.'/js/nav-collapse.js'); ?>

				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".t3-navbar-collapse">

					<i class="fa fa-bars"></i>

				</button>

			<?php endif ?>



			<?php if ($this->getParam('addon_offcanvas_enable')) : ?>

				<?php $this->loadBlock ('off-canvas') ?>

			<?php endif ?>



		</div>



		<?php if ($this->getParam('navigation_collapse_enable')) : ?>

			<div class="t3-navbar-collapse navbar-collapse collapse"></div>

		<?php endif ?>



		<div class="t3-navbar2">

			<jdoc:include type="<?php echo $this->getParam('navigation_type', 'megamenu') ?>" name="<?php echo $this->getParam('mm_type', 'mainmenu') ?>" />

		</div>



	</div>

</nav>

<!-- //MAIN NAVIGATION -->

