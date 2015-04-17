<?php
	if (!defined('_PS_VERSION_'))
	exit;
	include_once dirname(__FILE__).'/classes/MarketplaceShopBanner.php';
	class mpshopbanner extends Module {
		const INSTALL_SQL_FILE = 'install.sql';
		
		public function __construct() {
			$this->name = 'mpshopbanner';
			$this->version = '0.5';
			$this->author = 'Webkul Software PVT LTD.';
			$this->need_instance = 1;
			
			parent::__construct();
			$this->displayName = $this->l('Marketplace Shop Banner');
			$this->description = $this->l('Seller can add banner according to his choice.');
		}
		
		private function _displayForm() {
			
		}
		
		public function getContent() {
			$this->_html = '<h2>' . $this->displayName . '</h2>';
			$this->_postProcess();
			$this->_displayForm();
			return $this->_html;
		}
		
		private function _postProcess() {
			
		}
		
		public function callAssociateModuleToShop() {
			$module_id = Module::getModuleIdByName($this->name);
			Configuration::updateGlobalValue('MPBLOG_MODULE_ID',$module_id);
			return true;
		}
		
		public function hookDisplayMpmyaccountmenuhook($params) 
		{
			global $smarty;
			global $cookie;
			$id_customer = $this->context->cookie->id_customer;
			$mp_customer = new MarketplaceCustomer();
			$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);
			if($mp_customer_info) {
				$is_seller = $mp_customer_info['is_seller'];
				if($is_seller==1) {
					$link = new Link();					
					$extra = array('come_from'=>'my-account');
					$viewbannerlist = $link->getModuleLink('mpshopbanner','viewbannerlist',$extra);
					$smarty->assign("viewbannerlist",$viewbannerlist);
					$smarty->assign("mpmenu","0");
					return $this->display(__FILE__, 'shop_banner_menu.tpl');
				}
			}
		}
		
		public function hookDisplayMpcollectionbannerhook($params){
			global $smarty;
			global $cookie;
			$shop = Tools::getValue('shop');
			$obj = new MarketplaceShopBanner();
			$active_banner = $obj->getActiveBannerByIdshop($shop);			
			if($active_banner){
				$smarty->assign("banner_id",$active_banner[0]['id']);
				$smarty->assign("active_banner",1);
			}else{
				$smarty->assign("active_banner",0);
			}
			return $this->display(__FILE__, 'shop_collection_banner.tpl');
		}
		
		public function hookDisplayMpmenuhookext($params)
		{
			global $smarty;
			global $cookie;
			$id_customer = $this->context->cookie->id_customer;
			$mp_customer = new MarketplaceCustomer();
			$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);
			if($mp_customer_info) {
				$is_seller = $mp_customer_info['is_seller'];
				if($is_seller==1) {
					$mp_seller_id = $mp_customer_info['marketplace_seller_id'];
					$link = new Link();
					$extra = array('come_from'=>'my-account');
					$viewbannerlist = $link->getModuleLink('mpshopbanner','viewbannerlist',$extra);
					$smarty->assign("viewbannerlist",$viewbannerlist);
					$smarty->assign("mpmenu","1");
					return $this->display(__FILE__, 'shop_banner_menu.tpl');
				}
			}
		}
		
		
		public function install() {
			$ismpinstall = Module::isInstalled('marketplace');
			if($ismpinstall) {
				if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
						return (false);
					else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
						return (false);
					$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
					$sql = preg_split("/;\s*[\r\n]+/", $sql);
					foreach ($sql AS $query)
						if($query)
							if(!Db::getInstance()->execute(trim($query)))
								return false;
				if (!parent::install()
					|| !$this->registerHook('displayMpmyaccountmenuhook')
					|| !$this->registerHook('displayMpmenuhookext')
					|| !$this->registerHook('displayMpcollectionbannerhook')
				)
					return false;
				else {
					if(!$this->callAssociateModuleToShop()) {
						return false;
					} else {
						return true;	
					}
				}
			} else {
				$this->errors[] = Tools::displayError($this->l('Marketplace Module Not install.'));
				return false;
			}
		}
		
		
		
		
		public function drop_table($table_name_without_prefix) {
			$drop =Db::getInstance()->execute("DROP TABLE `"._DB_PREFIX_.$table_name_without_prefix."`");
			if(!$drop)
				return false;
			return true;	

		}
	
		public function uninstall() {

			if (parent::uninstall() == false  
			|| !$this->drop_table('marketplace_shop_banner'))

				return false;

			return true;

		}
	}
?>