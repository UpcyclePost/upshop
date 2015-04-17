CREATE TABLE IF NOT EXISTS `PREFIX_marketplace_shop_banner` (

	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	
	`name` varchar(255) character set utf8 NOT NULL,	
	
	`mp_id_shop` int(11) unsigned NOT NULL,
	
	`date_add` datetime NOT NULL,
	
	`date_upd` datetime NOT NULL,
	
	`is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
	
	PRIMARY KEY (`id`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
