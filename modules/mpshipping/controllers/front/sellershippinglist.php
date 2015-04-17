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
						
						$this->setTemplate('mpshippinglist.tpl');
					}
				}
			}
		}
		
		public function setMedia() {
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_.'mpshipping/css/sellershippinglist.css');
			$this->addCSS(_MODULE_DIR_.'marketplace/css/marketplace_account.css');
		}
	}
?>