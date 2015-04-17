<?php
	if (!defined('_PS_VERSION_'))
		exit;
	class mpshopbanneraddnewbannerModuleFrontController extends ModuleFrontController	{
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
				$back_link = $link->getModuleLink('mpshopbanner','viewbannerlist');
				$banner_process_link = $link->getModuleLink('mpshopbanner','addnewbannerprocess');	
				$extra = array('shop'=>(int)$mp_id_shop);
				$dash_board_link = $link->getModuleLink('marketplace','marketplace_account',$extra);
				
				if(isset($_GET['error'])) {
					$smarty->assign('error',$_GET['error']);
				}else{
					$smarty->assign('error',0);
				}
				$smarty->assign('banner_process_link',$banner_process_link);
				$smarty->assign('id_shop',$mp_id_shop);
				$smarty->assign('back_link',$back_link);
				$smarty->assign('dash_board_link',$dash_board_link);				
				$this->setTemplate('addnewbanner.tpl');
			} else {
				$page_link = $link->getPageLink('my-account');
				Tools::redirect($page_link);
			}
		}
		
		public function setMedia() {
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_ .'mpshopbanner/views/css/bannerlist.css');
			$this->addJS(_MODULE_DIR_.'mpshopbanner/views/js/banner_script.js');
		}
	}
?>