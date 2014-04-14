CREATE TABLE IF NOT EXISTS `cep_actualite` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL COMMENT 'Titre du message',
  `contenu` text NOT NULL COMMENT 'Contenu du message',
  `date_debut` date NOT NULL COMMENT 'Date de la création du message, ne s''affichera aps avant cette date',
  `date_modif` date NOT NULL COMMENT 'Date de la dernière modification',
  `date_fin` date NOT NULL COMMENT 'Date de fin de cette actualite, ne s''affichera aps aprèscette date',
  `etat` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 = activé, 2=activé (invisible, 0 désactivé',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;
