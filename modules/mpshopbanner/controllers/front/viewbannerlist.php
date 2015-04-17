<?php
	if (!defined('_PS_VERSION_'))
		exit;
	class mpshopbannerviewbannerlistModuleFrontController extends ModuleFrontController	{
		public function initContent() {
			global $smarty;	
			global $cookie;
			parent::initContent();
			$link = new Link();
			$come_from = Tools::getValue('come_from');
			$id_customer = $this->context->cookie->id_customer;
			if($id_customer) {
				$obj_mp_shop = new MarketplaceShop();
				$mp_shop_info = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($id_customer);
				$mp_id_shop = $mp_shop_info['id'];
				$extra = array('shop'=>(int)$mp_id_shop);				
				$dash_board_link = $link->getModuleLink('marketplace','marketplace_account',$extra);
				$link_add_new = $link->getModuleLink('mpshopbanner','addnewbanner');
				
				$banner_front_link = $link->getModuleLink('mpshopbanner','bannerfrontaction',$extra);
				//fetch all banner by mp_id_shop
				$obj = new MarketplaceShopBanner();
				$banner_list = $obj->getAllBannerIdshop($mp_id_shop);
				if(empty($banner_list)){
					$smarty->assign('banner',0);
				}else{
					$smarty->assign('banner',1);
					$smarty->assign('banner_list',$banner_list);
				}
				$smarty->assign('link_add_new',$link_add_new);
				$smarty->assign('banner_front_link',$banner_front_link);
				$smarty->assign('dash_board_link',$dash_board_link);
				
				$this->setTemplate('viewbannerlist.tpl');
			}
		}
		public function setMedia() {
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_.'mpshopbanner/views/css/bannermenu.css');
			$this->addJS(_MODULE_DIR_.'mpshopbanner/views/js/banner_script.js');
			$this->addJqueryPlugin(array('fancybox','tablednd'));
		}
	}
?>