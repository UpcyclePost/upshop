<?php
if (!defined('_PS_VERSION_'))
	exit;
	class mpsellerlistsellerlistModuleFrontController extends ModuleFrontController	
	{
		public function initContent() 
		{
			parent::initContent();	
			$link = new Link();
			$id_customer = $this->context->customer->id;
			if ($id_customer)
			{
				$obj_mp_shop = new MarketplaceShop();
				$mp_shop_info = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($id_customer);
				if ($mp_shop_info)
				{
					$mp_id_shop = $mp_shop_info['id'];
					$param = array('shop'=>$mp_id_shop);
					$gotoshop_link = $link->getModuleLink('marketplace','marketplaceaccount',$param);
				}
				else
					$gotoshop_link = $link->getPageLink('my-account');
			}
			else
				$gotoshop_link = $link->getPageLink('my-account');

			$obj_seller = new SellerInfoDetail();
			$all_active_seller = $obj_seller->findAllActiveSellerInfoByLimit();
			
			if ($all_active_seller)
			{
				$shop_img = array();
				$shop_store_link = $link->getModuleLink('marketplace','shopstore');
				foreach($all_active_seller as $act_seller) 
				{
					$img_file = 'modules/marketplace/img/shop_img/'.$act_seller['id'].'-'.$act_seller['shop_name'].'.jpg';
					if(file_exists($img_file))
						$shop_img[] = $act_seller['id'].'-'.$act_seller['shop_name'].'.jpg';
					else
						$shop_img[] = 'defaultshopimage.jpg';
				}
				$viewmorelist_link = $link->getModuleLink('mpsellerlist', 'viewmorelist');
				$this->context->smarty->assign('viewmorelist_link', $viewmorelist_link);
				$this->context->smarty->assign('shop_img', $shop_img);
				$this->context->smarty->assign('shop_store_link', $shop_store_link);
				$this->context->smarty->assign('all_active_seller', $all_active_seller);
			}
			
			$obj_seller_product = new SellerProductDetail();
			$seller_product_info = $obj_seller_product->findAllActiveSellerProductByLimit(0,9);
			if($seller_product_info) 
			{
				$product_info = array();
				$lang_id = $this->context->language->id;
				$lang_iso_code = $this->context->language->iso_code;
				foreach($seller_product_info as $key=>$active_pro) 
				{
					$product = new Product($active_pro['main_id_product'], false, $lang_id);
					$cover_image_id = Product::getCover($product->id);
					$product_info[$key][0] = $product->link_rewrite;
					$product_info[$key][3] = $link->getProductLink($product, null, null, null, $lang_id, null, 0, false, true);
					if($cover_image_id) 
						$product_info[$key][1] = $product->id.'-'.$cover_image_id['id_image'];
					else
					{
						$product_info[$key][1] = "";
	                	$product_info[$key][2] = $lang_iso_code;
					}
				}
				$product_link = $link->getPageLink('product', true, $lang_id);
				$this->context->smarty->assign('product_link',$product_link);
				$this->context->smarty->assign('seller_product_info',$seller_product_info);
				$this->context->smarty->assign('product_info',$product_info);				
			}

			$mp_seller_text = Configuration::getGlobalValue('MP_SELLER_TEXT');
			$this->context->smarty->assign('mp_seller_text',$mp_seller_text);
			$this->context->smarty->assign('gotoshop_link',$gotoshop_link);
			$this->setTemplate('mpsellerlist.tpl');
		}

		public function setMedia()
		{
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_.'mpsellerlist/views/css/sellerlist.css');
		}
	}

?>