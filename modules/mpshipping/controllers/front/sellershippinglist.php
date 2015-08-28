<?php
	if (!defined('_PS_VERSION_'))
		exit;
	class mpshippingsellershippinglistModuleFrontController extends ModuleFrontController	
	{
		public function initContent() 
		{
			parent::initContent();
			$link = new Link();
			$id_customer = $this->context->cookie->id_customer;
			if($id_customer) 
			{
				$mp_customer = new MarketplaceCustomer();
				$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);
				if($mp_customer_info)
				{
					$is_seller = $mp_customer_info['is_seller'];
					if($is_seller == 1)
					{
						//Delete Shipping method from front
						$obj_mp_shipping = new Mpshippingmethod();
						$delete = Tools::getValue('delete');
						if ($delete)
						{
							$id_shipping = Tools::getValue('id_shipping');
							$obj_mp_shipping->deleteMpShipping($id_shipping);
						}
						//close


						$obj_mp_shop = new MarketplaceShop();
						$mp_shop_info = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($id_customer);
						
						$mp_id_shop = $mp_shop_info['id'];	
						
						//show all shipping method which was not deleted in shipping list
						
						$mp_shipping_detail = $obj_mp_shipping->getAllShippingMethodNotDelete($mp_id_shop,0);
						
						if($mp_shipping_detail)
						{
							$k = 0;
							foreach($mp_shipping_detail as $mp_shipping)
							{
								if(file_exists(_PS_MODULE_DIR_.'mpshipping/img/logo/'.$mp_shipping['id'].'.jpg'))
									$mp_shipping_detail[$k]['image_exist'] = 1;
								else
									$mp_shipping_detail[$k]['image_exist'] = 0;
								$k++;
							}
							$this->context->smarty->assign('mp_shipping_detail',$mp_shipping_detail);
						}
						else
							$this->context->smarty->assign('mp_shipping_detail','0');
						
						$extra = array('shop'=>(int)$mp_id_shop);
						$dash_board_link = $link->getModuleLink('marketplace','marketplaceaccount',$extra);
						$addnew_shipping_link = $link->getModuleLink('mpshipping','addnewshipping',$extra);
						
						$basicedit_shipping_link = $link->getModuleLink('mpshipping','basiceditshipping',$extra);
						
						$impact_price_edit_link = $link->getModuleLink('mpshipping','impactpriceedit',$extra);
						$view_shipping_link = $link->getModuleLink('mpshipping','viewshipping',$extra);
						
						$this->context->smarty->assign('dash_board_link',$dash_board_link);
						$this->context->smarty->assign('addnew_shipping_link',$addnew_shipping_link);
						$this->context->smarty->assign('basicedit_shipping_link',$basicedit_shipping_link);
						$this->context->smarty->assign('impact_price_edit_link',$impact_price_edit_link);
						$this->context->smarty->assign('view_shipping_link',$view_shipping_link);
						$this->context->smarty->assign('mp_id_shop',$mp_id_shop);

						//for Menubar on left side
						$obj_ps_shop = new MarketplaceShop($mp_id_shop);
						$name_shop = $obj_ps_shop->link_rewrite;
						$param = array('shop'=>$mp_id_shop);
						$payment_detail    = $link->getModuleLink('marketplace', 'customerPaymentDetail',$param);
		                $link_store        = $link->getModuleLink('marketplace', 'shopstore',array('shop'=>$mp_id_shop,'shop_name'=>$name_shop));
		                $link_collection   = $link->getModuleLink('marketplace', 'shopcollection',array('shop'=>$mp_id_shop,'shop_name'=>$name_shop));
		                $link_profile      = $link->getModuleLink('marketplace', 'shopprofile',$param);
		                $add_product       = $link->getModuleLink('marketplace', 'addproduct',$param);
		                $account_dashboard = $link->getModuleLink('marketplace', 'marketplaceaccount',$param);
		                $seller_profile    = $link->getModuleLink('marketplace', 'sellerprofile',$param);
		                $edit_profile    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$mp_id_shop,'l'=>2,'edit-profile'=>1));
		                $product_list    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$mp_id_shop,'l'=>3));
		                $my_order    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$mp_id_shop,'l'=>4));
		                $payment_details    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$mp_id_shop,'id_cus'=>$id_customer,'l'=>5));
		                        
		                $this->context->smarty->assign("id_shop", $mp_id_shop);
		                $this->context->smarty->assign("id_customer", $id_customer);
		                $this->context->smarty->assign("is_seller", $is_seller);
		                $this->context->smarty->assign("payment_detail", $payment_detail);
		                $this->context->smarty->assign("link_store", $link_store);
		                $this->context->smarty->assign("link_collection", $link_collection);
		                $this->context->smarty->assign("link_profile", $link_profile);
		                $this->context->smarty->assign("add_product", $add_product);
		                $this->context->smarty->assign("account_dashboard", $account_dashboard);
		                $this->context->smarty->assign("seller_profile", $seller_profile);
		                $this->context->smarty->assign("edit_profile", $edit_profile);
		                $this->context->smarty->assign("product_list", $product_list);
		                $this->context->smarty->assign("my_order", $my_order);
		                $this->context->smarty->assign("payment_details", $payment_details);
		                $this->context->smarty->assign("logic", 'shipping_method_list');
						
						$this->setTemplate('mpshippinglist.tpl');
					}
				}
			}
		}
		
		public function setMedia() {
			parent::setMedia();
			
			$this->addJqueryPlugin(array('footable','footable-sort'));			
			$this->addCSS(_MODULE_DIR_.'mpshipping/css/sellershippinglist.css');
			$this->addCSS(_MODULE_DIR_.'marketplace/css/marketplace_account.css');

			//Jquery mobile (for the marketplace menu)
			if ($this->context->getMobileDevice()){
				$this->addJS(array(
						_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.min.js',
					));
				$this->addCSS(array(
						_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.structure.min.css',
						_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.theme.min.css'
					));
			}
		}
	}
?>