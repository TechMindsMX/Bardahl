CREATE TABLE IF NOT EXISTS `#__uniteshowbiz_sliders` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `alias` tinytext,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__uniteshowbiz_slides` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `slider_id` int(9) NOT NULL,
  `slide_order` int(11) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__uniteshowbiz_settings` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `wildcards` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__uniteshowbiz_templates` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `type` tinytext NOT NULL,
  `content` text NOT NULL,
  `css` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

INSERT INTO `#__uniteshowbiz_settings` (`id`, `wildcards`, `params`) VALUES
(1, 'a:2:{i:0;a:2:{s:5:"title";s:5:"Color";s:4:"name";s:5:"color";}i:1;a:2:{s:5:"title";s:5:"Price";s:4:"name";s:5:"price";}}', '');

