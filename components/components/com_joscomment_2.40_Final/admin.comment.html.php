<?php defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

/**
 * Copyright Copyright (C) 2006 Frantisek Hliva. All rights reserved.
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * !JoomlaComment is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * !JoomlaComment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA  02110-1301, USA.
 */

class HTML_comments {
    function viewAbout()
    {

        ?>
      <table cellpadding="4" cellspacing="0" border="0" width="50%">
      <tr>
        <td>
          	<p><div style='font-weight: bold; font-size: 1.2em;'>!JoomlaComment - License</div></p>
            <div align='justify'>
			<p>Copyright Copyright (C) 2006 Frantisek Hliva. All rights reserved.
            License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php</p>
            <p>!JoomlaComment is free software; you can redistribute it and/or modify
            it under the terms of the GNU General Public License as published by
            the Free Software Foundation; either version 2 of the License, or
            (at your option) any later version.</p>
            <p>!JoomlaComment is distributed in the hope that it will be useful,
            but <b>WITHOUT ANY WARRANTY</b>; without even the implied warranty of
            <b>MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE</b>.  See the
            GNU General Public License for more details.</p>
            <p>You should have received a copy of the GNU General Public License
            along with this program; if not, write to the Free Software
            Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
            MA  02110-1301, USA.</p>
            </div>
        </td>
      </tr>
      <tr>
        <td>
          <img src="./../components/com_comment/joscomment/images/logo.jpeg">
        </td>
      </tr>
      </table>
  	    <?php
    }
	
    function viewComments($option, &$rows, &$articles, &$search, &$pageNav)
    {
        global $mosConfig_live_site;
		$maxlength = "200";
        ?>
	<form action="index2.php" method="post" name="adminForm">
    <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%" align="left">Commentarios</td>
      <td nowrap="nowrap">Mostrar</td>
      <td>
        <?php
        echo $pageNav->writeLimitBox();
        ?>
      </td>
      <td>Buscar:</td>
      <td>
        <input type="text" name="search" value="<?php echo $search;

        ?>" class="inputbox" onChange="document.adminForm.submit();" />
      </td>
    </tr>
    </table>
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
        <th width="2%" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);

        ?>);" /></th>
        <th class="title"><div align="center">Autor</div></th>
        <th class="title"><div align="center">Fecha</div></th>
		<th class="title"><div align="left">Comentario</div></th>
		<th class="title"><div align="center">Artículo</div></th>
        <th class="title"><div align="center">Publicado</div></th>
      </tr>
      <?php
        $k = 0;
        for ($i = 0, $n = count($rows); $i < $n; $i++) {
            $row = &$rows[$i];
            echo "<tr class='row$k'>";
            echo "<td width='5%'><input type='checkbox' id='cb$i' name='cid[]' value='$row->id' onclick='isChecked(this.checked);' /></td>";
            if ($row->name == '') $row->name = 'Anonimo';
			$row->name = utf8_decode ($row->name);
           ?>   
            <td align 'center'><a href='javascript: void(0);' onClick='return listItemTask("cb<?php echo $i;?>","edit")'><?php echo $row->name; ?></a></td>
            <?php
            $row->comment = stripslashes($row->comment);
			$row->comment = utf8_decode ($row->comment);
            if (strlen($row->comment) > $maxlength) {
                $row->comment = substr($row->comment, 0, $maxlength);
                $row->comment .= "...";
            }
            $article = $articles[$row->contentid];
			$content = "$mosConfig_live_site/index.php?option=com_content&task=view&id=$row->contentid#josc$row->id";
            echo "<td align='center'>$row->date</td>";
            echo "<td align='left'>$row->comment</td>";
            echo "<td width='15%' align='center'><a href='$content' target='_blank'>$article</a></td>";
            if (strlen($row->gbcomment) > $maxlength) {
                $row->gbcomment = substr($row->gbcomment, 0, $maxlength);
                $row->gbcomment .= "...";
            }

            $task = $row->published ? 'unpublish' : 'publish';
            $img = $row->published ? 'publish_g.png' : 'publish_x.png';

            ?>
        <td width="10%" align="center"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;

            ?>','<?php echo $task;

            ?>')"><img src="images/<?php echo $img;

            ?>" width="12" height="12" border="0" alt="" /></a></td></tr>
    <?php $k = 1 - $k;
        }

        ?>
    <tr>
      <th align="center" colspan="7">
        <?php echo $pageNav->writePagesLinks();

        ?></th>
    </tr>
    <tr>
      <td align="center" colspan="7">
        <?php echo $pageNav->writePagesCounter();

        ?></td>
    </tr>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;

        ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  </form>
  <?php
    }

    function editComment($option, &$row, &$clist, &$olist, &$puplist)
    {
        mosMakeHtmlSafe($row, ENT_QUOTES, 'comment');

        ?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
      var form = document.adminForm;
      if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
      }

      if (form.comment.value == ""){
        alert( "Debes escribir algo al menos en el texto del comentario." );
      } else if (form.contentid.value == "0"){
        alert( "Debes elegir el artículo que corresponda." );
      } else {
        submitform( pressbutton );
      }
    }
    </script>
	<?php
	$row->name = utf8_decode ($row->name);
	$row->title = utf8_decode ($row->title);
	$row->comment = utf8_decode ($row->comment);
	
	?>
    <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%"><span class="sectionname"><?php echo $row->id ? 'Editar' : 'Añadir';

        ?> Comentario</span></td>
    </tr>
  </table>
    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
    <form action="index2.php" method="post" name="adminForm" id="adminForm">
      <tr>
        <td width="20%" align="right">Nombre:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="name" size="50" maxlength="30" value="<?php echo $row->name;

        ?>" />
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Artículo:</td>
        <td>
          <?php echo $clist;

        ?>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Título:</td>
        <td>
          <input class="inputbox" type="text" name="title" value="<?php echo $row->title;

        ?>" size="50" maxlength="50" />
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Comentario:</td>
        <td>
          <textarea class="inputbox" cols="50" rows="5" name="comment"><?php echo $row->comment;

        ?></textarea>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Publicado:</td>
        <td>
          <?php echo $puplist;

        ?>
        </td>
      </tr>

    </table>

    <input type="hidden" name="id" value="<?php echo $row->id;

        ?>" />
    <input type="hidden" name="option" value="<?php echo $option;

        ?>" />
    <input type="hidden" name="task" value="" />
    </form>
  <?php
    }
}

?>