CREATE TABLE `gnsvz_donde_comprar_` (
  `id`        INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tienda`    VARCHAR(150)     NOT NULL,
  `direccion` VARCHAR(255)     NOT NULL DEFAULT '',
  `telefono`  INT(11)          NOT NULL DEFAULT '0',
  `Lada`      INT(11)          NOT NULL DEFAULT '0',
  `estado`    VARCHAR(110)     NOT NULL DEFAULT '0',
  `url_mapa`  VARCHAR(255)     NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  AUTO_INCREMENT =35
  DEFAULT CHARSET =utf8;

REPLACE INTO `gnsvz_donde_comprar_` VALUES
  (6, 'Nombre de la tienda', 'direccion lañsdjkfñalsdfkjañlkj', 2147483647, 776556789, 'Acapulco', 'maps.google.com'),
  (7, 'Nombre', 'asdlfkjasdlñfkjasñldfkj sdflkja ', 798797889, 987987987, 'Aguascalientes', 'lñkajsdf'),
  (8, 'Nombre', 'ñljlñkjñlkj', 998798798, 987987, 'Cancún', 'lñkjñlkj'),
  (9, 'nombre', 'ñlkjñlkj', 888888888, 88888, 'Celaya', 'ñlkj'),
  (10, 'nomb', 'lkjñlkjñlkj', 987987, 987987, 'Chihuahua', 'ñlkjñklj'),
  (11, 'dfasdfas', 'ñlkjlkjñlkj', 98987987, 987987987, 'Cd. Juarez', 'lñkj'),
  (12, 'kjñlkjñlkj', 'lñkjñlkjñlkj', 98798798, 987987987, 'Coatzacoalcos', 'lñkjñlkj'),
  (13, 'ñlkjñlkj', 'ñlkjñlkjñlkj', 97987987, 987987987, 'D.F. Cervantes I', 'ñlkjñlkj'),
  (14, 'ñlkjñlkjñlkj', 'ñlkjñlkjñlkj', 9098098, 98098098, 'D.F. Cervantes II', 'ñlkjñlkj'),
  (15, 'ñlkjñlkjñlkj', 'ñlkjñlkjñlkj', 98098, 98098098, 'D.F. Insurjentes', 'ñlkjñlkj'),
  (16, 'ñlkjñkljñlkj', 'ñlkjñlkjñlkj', 98098098, 9809809, 'D.F.', 'ñlkjñlkjlñkj'),
  (17, 'lñkjñkjñklj', 'ñlkjñlkjklj', 98098098, 9809, 'Guadalajara', 'ñlkjkljñlkj'),
  (18, 'ñlkjñlkjñlkj', 'ñlkjñkljñlkj', 98098098, 8098098, 'Hermosillo', ''), (19, '', '', 0, 0, 'León', ''),
  (20, '', '', 0, 0, 'Mérida', ''), (21, '', '', 0, 0, 'Monterrey', ''), (22, '', '', 0, 0, 'Morelia', ''),
  (23, '', '', 0, 0, 'Pachuca', ''), (24, '', '', 0, 0, 'Puebla', ''), (25, '', '', 0, 0, 'Queretaro', ''),
  (26, 'tienda de san luis potoso', 'direcicon de san luis', 111111111, 333333333, 'San Luis Potosi', 'url mapa'),
  (27, '', '', 0, 0, 'Tampico', ''), (28, '', '', 0, 0, 'Tijuana', ''), (29, '', '', 0, 0, 'Toluca', ''),
  (30, '', '', 0, 0, 'Torreón', ''), (31, '', '', 0, 0, 'Tuxtla', ''), (32, '', '', 0, 0, 'Veracruz', ''),
  (33, '', '', 0, 0, 'Villahermosa', ''), (34, 'asdfasdfafsd', 'lkjhlkjhlkj', 876876, 876876, 'D.F.', 'laksdjfñlajsdf');
