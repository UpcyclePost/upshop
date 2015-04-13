CREATE TABLE IF NOT EXISTS `PREFIX_marketplace_shipping` (
`id` int(11) unsigned NOT NULL auto_increment,
`order_id` int(11) NOT NULL,
`shipping_description` text,
`shipping_date` datetime,
PRIMARY KEY  (`id`)
)ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_marketplace_delivery` (
`id` int(11) unsigned NOT NULL auto_increment,
`order_id` int(11) NOT NULL,
`delivery_date` datetime,
`received_by` varchar(1000),
PRIMARY KEY  (`id`)
)ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
