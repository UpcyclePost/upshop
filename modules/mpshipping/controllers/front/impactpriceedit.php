<?php
	if (!defined('_PS_VERSION_'))
	exit;
	class mpshippingimpactpriceeditModuleFrontController extends ModuleFrontController	
	{
		public function initContent() 
		{
			global $smarty;	
			global $cookie;
			parent::initContent();
			$link = new Link();
			
			$mp_shipping_id = Tools::getValue('id_shipping');
			$is_succe = Tools::getValue('is_succe');
			
			if($is_succe) {
				$smarty->assign('is_succe',$is_succe);
			} else {
				$smarty->assign('is_succe',0);
			}
			
			$obj_mp_shipping = new Mpshippingmethod($mp_shipping_id);
			$smarty->assign('shipping_method',$obj_mp_shipping->shipping_method);
			$smarty->assign('mp_shipping_id',$mp_shipping_id);
			
			$extra = array('shop'=>(int)$obj_mp_shipping->mp_id_shop);
			$dash_board_link = $link->getModuleLink('marketplace','marketplaceaccount',$extra);
			$sellershippinglist_link = $link->getModuleLink('mpshipping','sellershippinglist');
			
			$shipping_ajax_link = $link->getModuleLink('mpshipping','shippingajax');
			$shipping_ajax_range = $link->getModuleLink('mpshipping','shoprange');
			
			$smarty->assign('sellershippinglist_link',$sellershippinglist_link);
			$smarty->assign('dash_board_link',$dash_board_link);
			$smarty->assign('shipping_ajax_link',$shipping_ajax_link);
			$smarty->assign('shipping_ajax_range_link',$shipping_ajax_range);
			
			$zone_detail = Zone::getZones();
			$smarty->assign('zones',$zone_detail);
			$smarty->assign('self',dirname(__FILE__));
			$currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
			$smarty->assign('currency_sign', $currency->sign);
			$smarty->assign('PS_WEIGHT_UNIT', Configuration::get('PS_WEIGHT_UNIT'));
			
			$this->setTemplate('impactprice.tpl');
		}
		
		public function setMedia() 
		{
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_.'mpshipping/css/sellershippinglist.css');
			$this->addJs(_MODULE_DIR_.'mpshipping/js/editmpshipping.js');
			$this->addJqueryPlugin('typewatch');
		}
	}