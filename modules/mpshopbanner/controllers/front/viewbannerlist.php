<?php
	if (!defined('_PS_VERSION_'))
		exit;
	class mpshopbannerviewbannerlistModuleFrontController extends ModuleFrontController	
	{
		public function initContent() 
		{
			global $smarty;	
			global $cookie;
			parent::initContent();
			$link = new Link();
			$id_customer = $this->context->cookie->id_customer;
			if($id_customer) 
			{
				$obj_mp_shop = new MarketplaceShop();
				$mp_shop_info = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($id_customer);
				if($mp_shop_info)
				{
					$id_shop = $mp_shop_info['id'];
					$param = array('shop'=>(int)$id_shop);
					$obj_ps_shop = new MarketplaceShop($id_shop);
					$name_shop = $obj_ps_shop->link_rewrite;
					$link_store        = $link->getModuleLink('marketplace', 'shopstore', array('shop'=>$id_shop,'shop_name'=>$name_shop));
                    $link_collection   = $link->getModuleLink('marketplace', 'shopcollection', array('shop'=>$id_shop,'shop_name'=>$name_shop));
                    $add_product       = $link->getModuleLink('marketplace', 'addproduct', $param);
                    $account_dashboard = $link->getModuleLink('marketplace', 'marketplaceaccount', $param);
                    $seller_profile    = $link->getModuleLink('marketplace', 'sellerprofile', $param);
					$edit_profile    = $link->getModuleLink('marketplace', 'marketplaceaccount', array('shop'=>$id_shop,'l'=>2,'edit-profile'=>1));
					$product_list    = $link->getModuleLink('marketplace', 'marketplaceaccount', array('shop'=>$id_shop,'l'=>3));
					$my_order    = $link->getModuleLink('marketplace', 'marketplaceaccount', array('shop'=>$id_shop,'l'=>4));
					$payment_details    = $link->getModuleLink('marketplace', 'marketplaceaccount', array('shop'=>$id_shop,'id_cus'=>$id_customer,'l'=>5));

					$this->context->smarty->assign('account_dashboard', $account_dashboard);
                    $this->context->smarty->assign('link_store', $link_store);
                    $this->context->smarty->assign('seller_profile', $seller_profile);
                    $this->context->smarty->assign('link_collection', $link_collection);
                    $this->context->smarty->assign('add_product', $add_product);
                    $this->context->smarty->assign('edit_profile', $edit_profile);
					$this->context->smarty->assign('product_list', $product_list);
					$this->context->smarty->assign('my_order', $my_order);
					$this->context->smarty->assign('payment_details', $payment_details);
                    $this->context->smarty->assign('is_seller', 1);
                    $this->context->smarty->assign('logic','banner');

                    $dash_board_link = $link->getModuleLink('marketplace', 'marketplaceaccount', $param);
					$link_add_new = $link->getModuleLink('mpshopbanner', 'addnewbanner');
					$banner_front_link = $link->getModuleLink('mpshopbanner', 'bannerfrontaction', $param);
					$this->context->smarty->assign('link_add_new', $link_add_new);
					$this->context->smarty->assign('banner_front_link', $banner_front_link);
					$this->context->smarty->assign('dash_board_link', $dash_board_link);
					$this->context->smarty->assign('id_shop', $id_shop);

					$obj = new MarketplaceShopBanner();
					$banner_list = $obj->getAllBannerIdshop($id_shop);
					if(empty($banner_list))
						$this->context->smarty->assign('banner', 0);
					else
					{
						$this->context->smarty->assign('banner', 1);
						$this->context->smarty->assign('banner_list', $banner_list);
					}
				}
				$this->setTemplate('viewbannerlist.tpl');
			}else{
				Tools::redirect('index.php?controller=authentication&back='.urlencode($link->getModuleLink('mpshopbanner', 'viewbannerlist')));
			}
		}

		public function setMedia() 
		{
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_.'mpshopbanner/views/css/bannermenu.css');
		 	$this->addCSS(_MODULE_DIR_.'marketplace/css/marketplace_account.css');
			$this->addJS(_MODULE_DIR_.'mpshopbanner/views/js/banner_script.js');
			$this->addJqueryPlugin(array('fancybox','tablednd'));
		}
	}
?>