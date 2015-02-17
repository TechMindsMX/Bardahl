
<?php

/**

 * @package     Joomla.Site

 * @subpackage  com_content

 *

 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt

 */

defined("_JEXEC") or die("Restricted access");
$input =JFactory::getApplication()->input->getArray();
?>
<div class="head-category">
    <h1>
        Fondos de Pantalla
    </h1>

    <div class="img-desc">
        <div class="img-left">
            <img src="/images/calendario/2015/<?php echo $input['sJbD'] ?>">
        </div>
        <div class="img-rigth">
            <a href="/images/calendario/2015/1024/<?php echo $input['sJbD'] ?>">1024 x 768</a><br><br><br>
            <a href="/images/calendario/2015/1280/<?php echo $input['sJbD'] ?>">1280 x 1024</a><br><br><br>
            <a href="/images/calendario/2015/movil/<?php echo $input['sJbD'] ?>">Móvil</a>
        </div>

    </div>
</div>
