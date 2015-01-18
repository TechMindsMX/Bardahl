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
 * Traducción al Español: MakSonico
 */

class config {
    var $_configFile;
    var $tabs;
    var $josc_ajax;
    var $josc_only_registered;
    var $josc_language;
    var $josc_moderator;
    var $josc_exclude_sections;
    var $josc_exclude_categories;
    var $josc_template;
    var $josc_emoticon_pack;
    var $josc_tree;
    var $josc_tree_indent;
    var $josc_sort_downward;
    var $josc_support_profiles;
    var $josc_support_avatars;
    var $josc_support_emoticons;
    var $josc_support_UBBcode;
    var $josc_support_pictures;
    var $josc_censorship_enable;
    var $josc_censorship_case_sensitive;
    var $josc_censorship_words;
    var $josc_censorship_usertypes;
    var $josc_IP_visible;
    var $josc_IP_partial;
    var $josc_IP_caption;
    var $josc_IP_usertypes;
    var $josc_preview_visible;
    var $josc_preview_length;
    var $josc_preview_lines;
    var $josc_voting_visible;
    var $josc_notify_admin;
    var $josc_notify_email;
    var $josc_rss;
    var $josc_date_format;
    var $josc_captcha;
    var $josc_captcha_usertypes;
    var $josc_autopublish;
    var $josc_ban;
    var $josc_maxlength_text;
    var $josc_maxlength_word;
    var $josc_show_readon;

    function config()
    {
        global $mosConfig_absolute_path;
        $this->_configFile = "$mosConfig_absolute_path/components/com_comment/config.comment.php";
    }
    function load()
    {
        require($this->_configFile);
        $this->josc_ajax = $josc_ajax;
        $this->josc_only_registered = $josc_only_registered;
        $this->josc_language = $josc_language;
        $this->josc_moderator = $josc_moderator;
        $this->josc_exclude_sections = $josc_exclude_sections;
        $this->josc_exclude_categories = $josc_exclude_categories;
        $this->josc_template = $josc_template;
        $this->josc_emoticon_pack = $josc_emoticon_pack;
        $this->josc_tree = $josc_tree;
        $this->josc_tree_indent = $josc_tree_indent;
        $this->josc_sort_downward = $josc_sort_downward;
        $this->josc_support_profiles = $josc_support_profiles;
		$this->josc_support_avatars = $josc_support_avatars;
        $this->josc_support_emoticons = $josc_support_emoticons;
        $this->josc_support_UBBcode = $josc_support_UBBcode;
        $this->josc_support_pictures = $josc_support_pictures;
        $this->josc_censorship_enable = $josc_censorship_enable;
        $this->josc_censorship_case_sensitive = $josc_censorship_case_sensitive;
        $this->josc_censorship_words = $josc_censorship_words;
        $this->josc_censorship_usertypes = $josc_censorship_usertypes;
        $this->josc_IP_visible = $josc_IP_visible;
        $this->josc_IP_partial = $josc_IP_partial;
        $this->josc_IP_caption = $josc_IP_caption;
        $this->josc_IP_usertypes = $josc_IP_usertypes;
        $this->josc_preview_visible = $josc_preview_visible;
        $this->josc_preview_length = $josc_preview_length;
        $this->josc_preview_lines = $josc_preview_lines;
        $this->josc_voting_visible = $josc_voting_visible;
        $this->josc_notify_admin = $josc_notify_admin;
        $this->josc_notify_email = $josc_notify_email;
        $this->josc_rss = $josc_rss;
        $this->josc_date_format = $josc_date_format;
        $this->josc_captcha = $josc_captcha;
        $this->josc_captcha_usertypes = $josc_captcha_usertypes;
        $this->josc_autopublish = $josc_autopublish;
        $this->josc_ban = $josc_ban;
		$this->josc_maxlength_text = $josc_maxlength_text;
        $this->josc_maxlength_word = $josc_maxlength_word;
        $this->josc_show_readon = $josc_show_readon;
    }
    function save($option)
    {
        @chmod ($this->_configFile, 0766);
        $permission = is_writable($this->_configFile);
        if (!$permission) {
            mosRedirect("index2.php?option=$option&task=config", '¡El archivo de configuración no ha podido ser escrito!');
            break;
        }
        $config = "<?php\n";
        $config .= "\$josc_ajax = '" . $_POST['josc_ajax'] . "';\n";
        $config .= "\$josc_only_registered = '" . $_POST['josc_only_registered'] . "';\n";
        $config .= "\$josc_language = '" . $_POST['josc_language'] . "';\n";
        $config .= "\$josc_moderator = '" . implode(',', $_POST['josc_moderator']) . "';\n";
        $config .= "\$josc_exclude_sections = '" . implode(',', $_POST['josc_exclude_sections']) . "';\n";
        $config .= "\$josc_exclude_categories = '" . implode(',', $_POST['josc_exclude_categories']) . "';\n";
        $config .= "\$josc_template = '" . $_POST['josc_template'] . "';\n";
        $config .= "\$josc_emoticon_pack = '" . $_POST['josc_emoticon_pack'] . "';\n";
        $config .= "\$josc_tree = '" . $_POST['josc_tree'] . "';\n";
        $config .= "\$josc_tree_indent = '" . $_POST['josc_tree_indent'] . "';\n";
        $config .= "\$josc_sort_downward = '" . $_POST['josc_sort_downward'] . "';\n";
        $config .= "\$josc_support_profiles = '" . $_POST['josc_support_profiles'] . "';\n";
	$config .= "\$josc_support_avatars = '" . $_POST['josc_support_avatars'] . "';\n";
        $config .= "\$josc_support_emoticons = '" . $_POST['josc_support_emoticons'] . "';\n";
        $config .= "\$josc_support_UBBcode = '" . $_POST['josc_support_UBBcode'] . "';\n";
	$config .= "\$josc_support_pictures = '" . $_POST['josc_support_pictures'] . "';\n";
        $config .= "\$josc_censorship_enable = '" . $_POST['josc_censorship_enable'] . "';\n";
        $config .= "\$josc_censorship_case_sensitive = '" . $_POST['josc_censorship_case_sensitive'] . "';\n";
        $config .= "\$josc_censorship_words = '" . $_POST['josc_censorship_words'] . "';\n";
        $config .= "\$josc_censorship_usertypes = '" . implode(',', $_POST['josc_censorship_usertypes']) . "';\n";
        $config .= "\$josc_IP_visible = '" . $_POST['josc_IP_visible'] . "';\n";
        $config .= "\$josc_IP_partial = '" . $_POST['josc_IP_partial'] . "';\n";
        $config .= "\$josc_IP_caption = '" . $_POST['josc_IP_caption'] . "';\n";
        $config .= "\$josc_IP_usertypes = '" . implode(',', $_POST['josc_IP_usertypes']) . "';\n";
        $config .= "\$josc_preview_visible = '" . $_POST['josc_preview_visible'] . "';\n";
        $config .= "\$josc_preview_length = '" . $_POST['josc_preview_length'] . "';\n";
        $config .= "\$josc_preview_lines = '" . $_POST['josc_preview_lines'] . "';\n";
        $config .= "\$josc_voting_visible = '" . $_POST['josc_voting_visible'] . "';\n";
        $config .= "\$josc_notify_admin = '" . $_POST['josc_notify_admin'] . "';\n";
        $config .= "\$josc_notify_email = '" . $_POST['josc_notify_email'] . "';\n";
        $config .= "\$josc_rss = '" . $_POST['josc_rss'] . "';\n";
        $config .= "\$josc_date_format = '" . $_POST['josc_date_format'] . "';\n";
        $config .= "\$josc_captcha = '" . $_POST['josc_captcha'] . "';\n";
        $config .= "\$josc_captcha_usertypes = '" . implode(',', $_POST['josc_captcha_usertypes']) . "';\n";
        $config .= "\$josc_autopublish = '" . $_POST['josc_autopublish'] . "';\n";
        $config .= "\$josc_ban = '" . $_POST['josc_ban'] . "';\n";
		$config .= "\$josc_maxlength_text = '" . $_POST['josc_maxlength_text'] . "';\n";
        $config .= "\$josc_maxlength_word = '" . $_POST['josc_maxlength_word'] . "';\n";
        $config .= "\$josc_show_readon = '" . $_POST['josc_show_readon'] . "';\n";
        $config .= "?>\n";
        if ($fp = fopen($this->_configFile, "w")) {
            fputs($fp, $config, strlen($config));
            fclose ($fp);
        }
        mosRedirect("index2.php?option=$option&task=settings", 'Configuración guardada');
    }
    function generalPage()
    {
        global $mosConfig_absolute_path;
        $this->tabs->startTab("General", "Página general");
        $rows = new tabRows();
        $rows->addTitle("Ajustes básicos");
        $row = new tabRow();
        $row->caption = 'Soporte para Ajax (recomendado):';
        $row->component = mosHTML::yesnoRadioList('josc_ajax', 'class="inputbox"', $this->josc_ajax);
        $row->help = 'JavaScript y XML sincronizados.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Solo usuarios registrados:';
        $row->component = mosHTML::yesnoRadioList('josc_only_registered', 'class="inputbox"', $this->josc_only_registered);
        $row->help = 'Solo los usuarios registrados pueden escribir comentarios.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Idioma:';
        $row->component = mosHTML::selectList(languageList("$mosConfig_absolute_path/components/com_comment/joscomment/language/"), 'josc_language', 'class="inputbox"', 'value', 'text', $this->josc_language);
        $row->help = 'Elegí el idioma que se utilizará para mostrar las opciones de !JoomlaComment al insertar un comentario.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Grupo de moderadores:';
        $row->component = usertypes('josc_moderator', $this->josc_moderator, false);
        $row->help = 'Elegí que grupo de usuarios pueden ser moderadores. Los moderadores podrán publicar, editar y/o eliminar los comentarios de los usuarios.';
        $rows->addRow($row);
        $rows->addTitle("Secciones y Categorías");
		$row = new tabRow();
        $row->caption = 'Secciones excluidas:';
        $row->component = sections('josc_exclude_sections', $this->josc_exclude_sections);
        $row->help = 'Seleccioná la o las secciones que no incluyan comentarios (el formulario para introducir un comentario no estará disponible).';
		$rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Categorías excluidas:';
        $row->component = categories('josc_exclude_categories', $this->josc_exclude_categories);
        $row->help = 'Seleccioná la o las categorías que no incluyan comentarios (el formulario para introducir un comentario no estará disponible).';
        $rows->addRow($row);
        $rows->addTitle("Comentarios");
        $row = new tabRow();
        $row->caption = 'Permitir Perfiles:';
        $row->component = mosHTML::yesnoRadioList('josc_support_profiles', 'class="inputbox"', $this->josc_support_profiles);
        $row->help = "¿Usar los perfiles de <a href='http://www.joomlapolis.com/' target='_blank'>Community Builder</a> en los comentarios publicados?";
		$rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Permitir Avatares:';
        $row->component = mosHTML::yesnoRadioList('josc_support_avatars', 'class="inputbox"', $this->josc_support_avatars);
        $row->help = "¿Usar los avatares de <a href='http://www.joomlapolis.com/' target='_blank'>Community Builder</a> en los comentarios publicados?";
		$rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Soporte para emoticones:';
        $row->component = mosHTML::yesnoRadioList('josc_support_emoticons', 'class="inputbox"', $this->josc_support_emoticons);
        $row->help = '¿Permitir emoticones en los comentarios?';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Soporte para codigo UBB:';
        $row->component = mosHTML::yesnoRadioList('josc_support_UBBcode', 'class="inputbox"', $this->josc_support_UBBcode);
        $row->help = '¿Permitir código UBB para los comentarios?';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Soporte para imágenes:';
        $row->component = mosHTML::yesnoRadioList('josc_support_pictures', 'class="inputbox"', $this->josc_support_pictures);
        $row->help = '¿Permitir imágenes en los comentarios?';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Permitir valoración del artículo:';
        $row->component = mosHTML::yesnoRadioList('josc_voting_visible', 'class="inputbox"', $this->josc_voting_visible);
        $row->help = 'El usuario podrá valorar el artículo publicado.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Formato de fecha:';
        $row->component = input('josc_date_format', 'class="inputbox"', $this->josc_date_format);
        $row->help = 'La sintáxis es la misma utilizada en la función PHP date().';
        $rows->addRow($row);
        $rows->addTitle("Notificación");
        $row = new tabRow();
        $row->caption = 'Notificar al administrador:';
        $row->component = mosHTML::yesnoRadioList('josc_notify_admin', 'class="inputbox"', $this->josc_notify_admin);
        $row->help = '¿Notificar al administrador de nuevos comentarios publicados por correo electrónico?';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Correo electrónico del administrador:';
        $row->component = input('josc_notify_email', 'class="inputbox"', $this->josc_notify_email);
        $row->help = 'Introducír un correo electrónico válido para el envió de notificaciones al administrador';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Usar RSS en los comentarios:';
        $row->component = mosHTML::yesnoRadioList('josc_rss', 'class="inputbox"', $this->josc_rss);
        $rows->addRow($row);
        $row = new tabRow();
        echo $rows->htmlCode();
        $this->tabs->endTab();
    }
    function layoutPage()
    {
        global $mosConfig_absolute_path;
        $this->tabs->startTab("Diseño", "Página de diseño");
        $rows = new tabRows();
        $rows->addTitle("Ajustes básicos");
        $row = new tabRow();
        $row->caption = 'Plantilla:';
        $row->component = mosHTML::selectList(folderList("$mosConfig_absolute_path/components/com_comment/joscomment/templates"), 'josc_template', 'class="inputbox" size="3"', 'value', 'text', $this->josc_template);
        $row->help = 'Elegí entre las diferentes plantillas que le darán distintos diseños a los comentarios.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Pack de emoticones:';
        $row->component = mosHTML::selectList(folderList("$mosConfig_absolute_path/components/com_comment/joscomment/emoticons"), 'josc_emoticon_pack', 'class="inputbox" size="3"', 'value', 'text', $this->josc_emoticon_pack);
        $row->help = 'Elegí entre los diferentes pack de emoticones para usar en los comentarios.';
        $rows->addRow($row);
        $rows->addTitle("Estructura");
        $row = new tabRow();
        $row->caption = 'Usar comentarios por jerarquización:';
        $row->component = mosHTML::yesnoRadioList('josc_tree', 'class="inputbox"', $this->josc_tree);
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Sangrado (en píxeles):';
        $row->component = input('josc_tree_indent', 'class="inputbox"', $this->josc_tree_indent);
        $row->help = 'Esto se utiliza para introducir sangrado a los comentarios.';
        $rows->addRow($row);
        $rowSorting = new tabRow();
        $rowSorting->caption = 'Ordenar comentarios:';
        $sorting[] = mosHTML::makeOption('1', 'Nuevas entradas primero');
        $sorting[] = mosHTML::makeOption('0', 'Nuevas entradas último');
        $rowSorting->id = 'sorting';
        $rowSorting->component = mosHTML::selectList($sorting, 'josc_sort_downward', 'class="inputbox"', 'value', 'text', $this->josc_sort_downward);
        $rowSorting->help = 'Ordena los nuevos comentarios.';
        $rows->addRow($rowSorting);
        $rows->addTitle("Previa");
        $row = new tabRow();
        $row->caption = 'Visible:';
        $row->component = mosHTML::yesnoRadioList('josc_preview_visible', 'class="inputbox"', $this->josc_preview_visible);
        $row->help = 'Muestra el comentario antes de publicarlo.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Longitud:';
        $row->component = input('josc_preview_length', 'class="inputbox"', $this->josc_preview_length);
        $row->help = 'Configura la longitud maxima de los comentarios.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Número maximo de líneas:';
        $row->component = input('josc_preview_lines', 'class="inputbox"', $this->josc_preview_lines);
        $row->help = 'Selecciona el número maximo de lineas en los comentarios.';
        $rows->addRow($row);

        //added custome parts for readon config
        $rows->addTitle("Página de inicio");
        $row = new tabRow();
        $row->caption = 'Mostrar "Leer más":';
        $row->component = mosHTML::yesnoRadioList('josc_show_readon', 'class="inputbox"', $this->josc_show_readon);

        $rows->addRow($row);
        echo $rows->htmlCode();
        $this->tabs->endTab();
        $rowSorting->visible(!$this->josc_tree);
        $element = element::get('sorting');
        onClick('josc_tree0', $element . element::visible(true));
        onClick('josc_tree1', $element . element::visible(false));
    }
    function securityPage()
    {
        $this->tabs->startTab("Seguridad", "Página de seguridad");
        $rows = new tabRows();
        $rows->addTitle("Ajustes básicos");
        $row = new tabRow();
        $row->caption = 'Publicar comentarios automáticamente:';
        $row->component = mosHTML::yesnoRadioList('josc_autopublish', 'class="inputbox"', $this->josc_autopublish);
        $row->help = 'Si seleccionas "NO" los comentarios serán agregados a la base de datos para después leerlos y decidir si publicarlos o no.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Bloqueo por IP:';
        $row->component = textarea('josc_ban', 'class="inputbox" rows="5"', $this->josc_ban);
        $row->help = 'Para especificar el bloqueo por IP tipea las direcciones utilizando comas (,) entre ellas.';
        $rows->addRow($row);
        $rows->addTitle("Desfasamiento");
        $row = new tabRow();
        $row->caption = 'Longitud maxima para los comentarios:';
        $row->component = input('josc_maxlength_text', 'class="inputbox"', $this->josc_maxlength_text);
        $row->help = 'Máximo de caracteres permitidos por comentarios (para que no haya máximo de caracteres tipear -1.)';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Longitud maxima de letras:';
        $row->component = input('josc_maxlength_word', 'class="inputbox"', $this->josc_maxlength_word);
        $row->help = 'Máximo de caracteres permitidos en las letras  (para que no haya máximo de caracteres tipear -1.)';
        $rows->addRow($row);
        $rows->addTitle("Captcha");
        $row = new tabRow();
        $row->caption = 'Usar:';
        $row->component = mosHTML::yesnoRadioList('josc_captcha', 'class="inputbox"', $this->josc_captcha);
        $row->help = 'El sistema Captcha contruye de forma automática imagenes con números y letras y obliga al usuario a tipear lo que ve en la imagen. Esto evita el envío masivo de mensajes, también conocido como SPAM, que afecta el funcionamiento general del sistema.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Tipo de usuario:';
        $row->component = usertypes('josc_captcha_usertypes', $this->josc_captcha_usertypes);
        $row->help = 'Escoge el tipo de usuario que deberá utilizar Captcha antes de enviar un comentario.';
        $rows->addRow($row);
        $rows->addTitle("Palabras sensuradas");
        $row = new tabRow();
        $row->caption = 'Usar:';
        $row->component = mosHTML::yesnoRadioList('josc_censorship_enable', 'class="inputbox"', $this->josc_censorship_enable);
        $row->help = 'Si marcas "SI", se activará el sensurador de palabras, que no permitirá la inserción de términos mal intencionados o que provocan ofensa.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Caso sensible:';
        $row->component = mosHTML::yesnoRadioList('josc_censorship_case_sensitive', 'class="inputbox"', $this->josc_censorship_case_sensitive);
        $row->help = '';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Palabras sensuradas:';
        $row->component = textarea('josc_censorship_words', 'class="inputbox" rows="5" cols="70"', $this->josc_censorship_words);
        $row->help = false;
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Tipo de usuario:';
        $row->component = usertypes('josc_censorship_usertypes', $this->josc_censorship_usertypes);
        $row->help = 'Escoge el tipo de usuario al que se le aplicará éste sistema.';
        $rows->addRow($row);
        $rows->addTitle("Dirección IP");
        $row = new tabRow();
        $row->caption = 'Visible:';
        $row->component = mosHTML::yesnoRadioList('josc_IP_visible', 'class="inputbox"', $this->josc_IP_visible);
        $row->help = 'Escoge si deseas mostrar la dirección IP del usuario que envia un comentario.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Vista parcial:';
        $row->component = mosHTML::yesnoRadioList('josc_IP_partial', 'class="inputbox"', $this->josc_IP_partial);
        $row->help = 'Muestra de manera parcial la dirección IP del usuario que envia un comentario.';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Texto descriptivo:';
        $row->component = input('josc_IP_caption', 'class="inputbox"', $this->josc_IP_caption);
        $row->help = 'Escribe el texto que se mostrar antes de la dirección IP. Por defecto, "IP".';
        $rows->addRow($row);
        $row = new tabRow();
        $row->caption = 'Tipo de usuario:';
        $row->component = usertypes('josc_IP_usertypes', $this->josc_IP_usertypes);
        $row->help = 'Escoge el tipo de usuario al que se le aplicará éste sistema.';
        $rows->addRow($row);
        echo $rows->htmlCode();
		$this->tabs->endTab();
    }
    function execute($option)
    {
        echo "<form action='index2.php' method='POST' name='adminForm'>";
        $this->tabs = new mosTabs(0);
        $this->tabs->startPane("jos_comment");
        $this->generalPage();
        $this->layoutPage();
        $this->securityPage();
        $this->tabs->endPane();
        echo hidden('task');
        echo hidden('option', $option);
        echo "</form>";
    }
}

?>