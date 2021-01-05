USE postafi;

CREATE TABLE `topico` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `assunto` varchar(250) DEFAULT NULL,
  `texto` longtext,
  `cod_usuario` int(11) DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

