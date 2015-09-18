<?php

class Product extends ProductCore
{

	public function getAdjacentProducts()
	{
		//get the current position in the product's default category
		//$position  = Db::getInstance()->getValue('SELECT position FROM '._DB_PREFIX_.'category_product WHERE id_product = ' . (int)$this->id . ' AND id_category = ' . (int)$this->id_category_default);

		// get the seller for the current product
		$sql = "SELECT s.id_seller
				FROM "._DB_PREFIX_."marketplace_seller_product s 
				JOIN "._DB_PREFIX_."marketplace_shop_product p ON s.id = p.marketplace_seller_id_product
				WHERE p.id_product = " . (int)$this->id . ";";
		
		echo "<!-- sql :";
		echo ($sql);
		echo "-->";

		$seller_id  = Db::getInstance()->getValue($sql);

		echo "<!-- seller_id :";
		echo ($seller_id);
		echo "-->";

		// get products that are before and after
		$previoussql = "SELECT pl.id_product, pl.link_rewrite, pl.name
						FROM up_product_lang pl 
						LEFT JOIN "._DB_PREFIX_."marketplace_shop_product p ON p.id_product = pl.id_product
						LEFT JOIN "._DB_PREFIX_."marketplace_seller_product s ON p.marketplace_seller_id_product = s.id
						WHERE s.id_seller = " . $seller_id . "
						AND pl.id_product < " . (int)$this->id . "
						ORDER BY pl.id_product DESC";
		echo "<!-- previoussql : ";
		echo ($previoussql);
		echo "-->";
		
		$previous = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($previoussql);
		echo "<!-- previous";
		print_r ($previous);
		echo "-->";
		
		$nextsql = "SELECT pl.id_product, pl.link_rewrite, pl.name
						FROM up_product_lang pl 
						LEFT JOIN "._DB_PREFIX_."marketplace_shop_product p ON p.id_product = pl.id_product
						LEFT JOIN "._DB_PREFIX_."marketplace_seller_product s ON p.marketplace_seller_id_product = s.id
						WHERE s.id_seller = " . $seller_id . "
						AND pl.id_product > " . (int)$this->id . "
						ORDER BY pl.id_product ASC";
		echo "<!-- nextsql";
		echo ($nextsql);
		echo "-->";

		$next = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($nextsql);
		echo "<!-- next";
		print_r($next);
		echo "-->";

		return array('previous' => $previous, 'next' => $next);
	}

}