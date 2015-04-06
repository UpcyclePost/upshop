<?php
class marketplaceShopstoreModuleFrontController extends ModuleFrontController	
{
	public function initContent()
	{
        $id_lang = $this->context->cookie->id_lang;
		parent::initContent();

		$id_product = Tools::getValue('id');
		$link = new link();
		$id_shop = Tools::getValue('shop');
		if (!$id_shop)
			$id_shop = Tools::getValue('id_shop');
		
		if($id_shop != '') 
		{
			$marketplace_shop = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `shop_name`,`id_customer`,`about_us` from `"._DB_PREFIX_."marketplace_shop` where `id` =".$id_shop." ");
			$obj_ps_shop = new MarketplaceShop($id_shop);
        	$name_shop = $obj_ps_shop->link_rewrite;
        	$this->context->smarty->assign("name_shop",$name_shop);
			if ($marketplace_shop) 
			{
				$shop_name = $marketplace_shop['shop_name'];
				$id_customer = $marketplace_shop['id_customer'];
				$about_us = $marketplace_shop['about_us'];
				
				$marketplace_seller_id_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `marketplace_seller_id`,`is_seller` from `"._DB_PREFIX_."marketplace_customer` where `id_customer` =".$id_customer." ");
			
				if($marketplace_seller_id_info) 
				{
					$is_seller_active = $marketplace_seller_id_info['is_seller'];
					$marketplace_seller_id = $marketplace_seller_id_info['marketplace_seller_id'];
					if ($is_seller_active == 1) 
					{
						 $reviews_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("select * from `"._DB_PREFIX_."seller_reviews` where `id_seller` =".$marketplace_seller_id." and `active`=1");
						
						$rating = 0;
						$i = 0;
						foreach($reviews_info as $reviews)
						{
							$rating = $rating + $reviews['rating'];
							$i++;
						}
						if($rating != 0)
							$avg_rating = (double)($rating/$i);
						else
							$avg_rating = 0;

						$this->context->smarty->assign("avg_rating", $avg_rating);
					
						$market_place_seller_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `"._DB_PREFIX_."marketplace_seller_info` where `id` =".$marketplace_seller_id." ");

						if($market_place_seller_info) 
						{
							$business_email = $market_place_seller_info['business_email'];
							$phone = $market_place_seller_info['phone'];

							$fax = $market_place_seller_info['fax'];

							$customer_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `"._DB_PREFIX_."customer` where `id_customer` =".$id_customer." ");

							$param1 = array('flag'=>'1','all_reviews'=>'1','shop'=>$id_shop,'seller_id'=>$marketplace_seller_id);
							
							$all_reviews_links = $link->getModuleLink('marketplace','allreviews',$param1);
							
							$this->context->smarty->assign("id_shop1", $id_shop);
							$this->context->smarty->assign("all_reviews", $all_reviews_links);							
							
							$marketplace_shop_product = Db::getInstance()->ExecuteS("SELECT msp.*,mslp.* FROM `" . _DB_PREFIX_ . "marketplace_shop_product` msp join `" . _DB_PREFIX_ . "marketplace_seller_product` mslp on (msp.`marketplace_seller_id_product` = mslp.`id`) join `" . _DB_PREFIX_ . "product` p on (msp.`id_product`=p.`id_product`) where msp.`id_shop` =" . $id_shop . " order by `product_name` asc limit 15");
													
							$this->context->smarty->assign("shop_arg",array('shop'=>$id_shop));		
							$this->context->smarty->assign("seller_name",$market_place_seller_info['seller_name']);
							$this->context->smarty->assign("phone",$phone);
							$this->context->smarty->assign("fax",$fax);
							$this->context->smarty->assign("business_email",$business_email);
							$this->context->smarty->assign("id_shop",$id_shop);
							$this->context->smarty->assign("seller_id",$marketplace_seller_id);
							$this->context->smarty->assign("shop_name",$shop_name);
							$this->context->smarty->assign("id_customer",$id_customer);
							$this->context->smarty->assign("market_place_seller_info",$market_place_seller_info);
							$this->context->smarty->assign("customer_info",$customer_info);
							$this->context->smarty->assign("module_path",_MODULE_DIR_);
							$this->context->smarty->assign("id_product",$id_product);
						} 
						else // market_place_seller_info check close
						{
							Tools::redirect(__PS_BASE_URI__.'pagenotfound');
						}
					} // is_seller_active status check close
					else 
					{
						Tools::redirect(__PS_BASE_URI__.'pagenotfound');
					}
				} // marketplace_seller_id_info check close
				else 
				{
					Tools::redirect(__PS_BASE_URI__.'pagenotfound');
				}
			} // marketplace_shop check close
			else 
			{
				Tools::redirect(__PS_BASE_URI__.'pagenotfound');
			}
		} // id_shop check close 
		else
		{
			Tools::redirect(__PS_BASE_URI__.'pagenotfound');
		}

		if ($marketplace_shop_product) 
		{
			$a = 0;
			$marketplace_product_id = array();
			foreach ($marketplace_shop_product as $marketplace_shop_product1) 
			{
				$marketplace_product_id[] = $marketplace_shop_product1['id_product'];
				$marketplace_seller_id    = $marketplace_shop_product1['id_seller'];
				$a++;
			}
			
			$count = count($marketplace_product_id);
			$product = array();
			for ($i = 0; $i < $count; $i++) 
			{
				$active_product = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * FROM `"._DB_PREFIX_."product`
												WHERE id_product =".$marketplace_product_id[$i]." AND active = 1");
				if ($active_product)
					$product[] = $active_product;
			}

			$a = 0;
			$product_id = array();
			foreach ($product as $product1) 
			{
				$product_id[] = $product1['id_product'];
				$a++;
			}

			$count_product = count($product_id);
			$image = array();
			$product_lang = array();
			$image_link = array();
			for ($i = 0; $i < $count_product; $i++) 
			{
				$image[] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "image` where id_product =" . $product_id[$i] . " and cover = 1");
				$image_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "image` where id_product =" . $product_id[$i] . " and cover = 1");
				
				$product_lang[] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "product_lang` where id_product =" . $product_id[$i] . " and id_lang=".$id_lang);
				
				$ids = $product_id[$i].'-'.$image_id['id_image'];
				$product_obj = new Product($product_id[$i], false, $id_lang);
				$cover_image_id = Product::getCover($product_obj->id);	
				if($cover_image_id) 
				{
					$image_link[$i][0] = $product_obj->link_rewrite;
					$image_link[$i][1] = $product_obj->id.'-'.$cover_image_id['id_image'];
					$image_link[$i][3] = $this->context->language->id;
				} 
				else 
				{
					$image_link[$i][0] = $product_obj->link_rewrite;
                	$image_link[$i][1] = "";
                	$image_link[$i][2] = $this->context->language->iso_code;
                	$image_link[$i][3] = $this->context->language->id;
				}
			}

			$a = 0;
			$product_name = array();
			$product_desc = array();
			foreach ($product_lang as $product_lang1)
			{
				$product_name[] = $product_lang1['name'];
				$product_desc[] = $product_lang1['description'];
				$a++;
			}
			$a = 0;
			$product_id = array();
			$product_price = array();
			$product_quantity = array();
			foreach ($product as $product1)
			{
				$product_price[]    = number_format($product1['price'],2,'.','');
				$product_id[]       = $product1['id_product'];
				$product_quantity[] = $product1['quantity'];
				$a++;
			}

			$a = 0;
			$i = 0;
			$mkt_shop  = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "marketplace_seller_info` where id =" . $marketplace_seller_id . " ");

			$shop_name = $mkt_shop['shop_name'];
			$param = array('seller_id'=>$marketplace_seller_id);
			$all_reviews = $link->getModuleLink('marketplace','allreviews',$param);
			$this->context->smarty->assign("all_reviews",$all_reviews);
			$count_product = count($product_quantity);
			$this->context->smarty->assign("shop_name", $shop_name);
			$this->context->smarty->assign("seller_id", $marketplace_seller_id);
			$this->context->smarty->assign("product_quantity", $product_quantity);
			$this->context->smarty->assign("product_price", $product_price);
			$this->context->smarty->assign("product_id", $product_id);
			$this->context->smarty->assign("product_desc", $product_desc);
			$this->context->smarty->assign("product_name", $product_name);
			$this->context->smarty->assign("count_product", $count_product);
			$this->context->smarty->assign("image_link", $image_link);
		} 
		else
			$this->context->smarty->assign("count_product", 0);

		$param = array('shop'=>$id_shop);
		$link_collection = $link->getModuleLink('marketplace','shopcollection',$param);
		$link_store = $link->getModuleLink('marketplace','shopstore',$param);
		$link_conatct = $link->getModuleLink('marketplace','contact',$param);
		$Seller_profile = $link->getModuleLink('marketplace','sellerprofile',$param);
		$this->context->smarty->assign("link_store",$link_store);
		$this->context->smarty->assign("link_collection",$link_collection);
		$this->context->smarty->assign("Seller_profile",$Seller_profile);
		$this->context->smarty->assign("link_conatct",$link_conatct);
		$this->context->smarty->assign("about_us",$about_us);

		if (!file_exists(_PS_MODULE_DIR_."marketplace/img/shop_img/".$marketplace_seller_id."-".$shop_name.".jpg"))
			$this->context->smarty->assign("no_shop_img", 1);
		else
			$this->context->smarty->assign("no_shop_img", 0);
		
		$this->setTemplate('shop-store.tpl');									
	}

	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(_MODULE_DIR_.'marketplace/css/store_profile.css');
		$this->addCSS(_MODULE_DIR_.'marketplace/views/css/product_slider/ps_gray.css');
		$this->addJS(_MODULE_DIR_.'marketplace/rateit/lib/jquery.raty.min.js');
		$this->addJS(_MODULE_DIR_.'marketplace//views/js/mp_product_slider.js');
	}
}
?>