<?php

/**

 * @package   T3 Blank

 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.

 * @license   GNU General Public License version 2 or later; see LICENSE.txt

 */



defined('_JEXEC') or die;



// get params

$sitename  = $this->params->get('sitename');

$slogan    = $this->params->get('slogan', '');

$logotype  = $this->params->get('logotype', 'text');

$logoimage = $logotype == 'image' ? $this->params->get('logoimage', T3Path::getUrl('images/logo.png', '', true)) : '';

$logoimgsm = ($logotype == 'image' && $this->params->get('enable_logoimage_sm', 0)) ? $this->params->get('logoimage_sm', T3Path::getUrl('images/logo-sm.png', '', true)) : false;



if (!$sitename) {

	$sitename = JFactory::getConfig()->get('sitename');

}



$logosize = 'col-sm-12';

if ($headright = $this->countModules('head-search or languageswitcherload')) {

	$logosize = 'col-sm-8';

}



?>



<!-- HEADER -->

<header id="t3-header" class="container t3-header">

	<div class="row">


		<!-- LOGO -->

		<?php if ($headright): ?>

			<div class="col-xs-12 col-sm-4">

				<?php if ($this->countModules('head-search')) : ?>

					<!-- HEAD SEARCH -->

					<div class="head-search <?php $this->_c('head-search') ?>">

						<jdoc:include type="modules" name="<?php $this->_p('head-search') ?>" style="raw" />

					</div>
                    <div id="line">
                        <img  src="templates/t3_bs3_blank/images/site/line_under_submenu.jpg">
					</div>
                    <div id="line-menu">
                        <img  src="templates/t3_bs3_blank/images/site/back_submenu.png">
                    </div>
					<!-- //HEAD SEARCH -->

				<?php endif ?>



				

			</div>

		<?php endif ?>



	</div>

</header>

<!-- //HEADER -->

