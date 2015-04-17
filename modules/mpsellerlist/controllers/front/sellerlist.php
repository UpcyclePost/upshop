<?php
if (!defined('_PS_VERSION_'))
	exit;
	class mpsellerlistsellerlistModuleFrontController extends ModuleFrontController	{
		public function initContent() {
			global $smarty;	
			global $cookie;
			parent::initContent();	
			
			$link = new Link();
			
			$id_customer = $this->context->cookie->id_customer;
			if($id_customer) {
				$obj_mp_shop = new MarketplaceShop();
				$mp_shop_info = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($id_customer);
				if($mp_shop_info) {
					$mp_id_shop = $mp_shop_info['id'];
					$param = array('shop'=>$mp_id_shop);
					$gotoshop_link = $link->getModuleLink('marketplace','marketplace_account',$param);
				} else {
					$gotoshop_link = $link->getPageLink('my-account');
				}
			} else {
				$gotoshop_link = $link->getPageLink('my-account');
			}
			$obj_seller = new SellerInfoDetail();
			$all_active_seller = $obj_seller->findAllActiveSellerInfoByLimit();
			
			if($all_active_seller) {
				$total_active_seller = count($all_active_seller);
				$param = array('flag'=>1);
				$shop_store_link = $link->getModuleLink('marketplace','shop_store',$param);
				foreach($all_active_seller as $act_seller) {
					$img_file = 'modules/marketplace/img/shop_img/'.$act_seller['id'].'-'.$act_seller['shop_name'].'.jpg';
					
					if(file_exists($img_file)) {
						$shop_img[] = $act_seller['id'].'-'.$act_seller['shop_name'].'.jpg';
					} else {
						$shop_img[] = 'defaultshopimage.jpg';
					}
				}
				$viewmorelist_link = $link->getModuleLink('mpsellerlist','viewmorelist');
				$smarty->assign('viewmorelist_link',$viewmorelist_link);
				$smarty->assign('shop_img',$shop_img);
				$smarty->assign('shop_store_link',$shop_store_link);
				$smarty->assign('all_active_seller',$all_active_seller);
			} else {
				$total_active_seller = 0;
			}
			
			$obj_seller_product = new SellerProductDetail();
			$seller_product_info = $obj_seller_product->findAllActiveSellerProductByLimit();
			if($seller_product_info) {
				$active_seller_product = count($seller_product_info);
				foreach($seller_product_info as $active_pro) {
					$product = new Product($active_pro['main_id_product']);
					$cover_image_id = Product::getCover($product->id);
					if($cover_image_id) {
						$ids = $product->id.'-'.$cover_image_id['id_image'];
						$prduct_img_link = "http://".$link->getImageLink($product->link_rewrite,$ids,'home_default');
						$product_img_info[] = $prduct_img_link;
					} else {
						$product_img_info[] = _MODULE_DIR_.'mpsellerlist/img/defaultproduct.jpg';
					}
					
				}	
				$params = array('flag'=>1);
				$product_link = $link->getPageLink('product',$params);
				$smarty->assign('product_link',$product_link);
				$smarty->assign('seller_product_info',$seller_product_info);
				$smarty->assign('product_img_info',$product_img_info);				
			} else {
				$active_seller_product = 0;
			}
			$mp_seller_text = Configuration::getGlobalValue('MP_SELLER_TEXT');
			$smarty->assign('mp_seller_text',$mp_seller_text);
			$smarty->assign('active_seller_product',$active_seller_product);
			$smarty->assign('total_active_seller',$total_active_seller);
			$smarty->assign('gotoshop_link',$gotoshop_link);
			$this->setTemplate('mpsellerlist.tpl');
		}

		public function setMedia() {
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_.'mpsellerlist/views/css/sellerlist.css');
		}
	}

?>