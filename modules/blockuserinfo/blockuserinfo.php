<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class BlockUserInfo extends Module
{
	public function __construct()
	{
		$this->name = 'blockuserinfo';
		$this->tab = 'front_office_features';
		$this->version = '0.3.1';
		$this->author = 'PrestaShop';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('User info block');
		$this->description = $this->l('Adds a block that displays information about the customer.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return (parent::install() && $this->registerHook('displayTop') && $this->registerHook('displayNav') && $this->registerHook('displayHeader'));
	}

	/**
	* Returns module content for header
	*
	* @param array $params Parameters
	* @return string Content
	*/
	public function hookDisplayTop($params)
	{
		if (!$this->active)
			return;

		$this->smarty->assign(array(
			'cart' => $this->context->cart,
			'cart_qties' => $this->context->cart->nbProducts(),
			'logged' => $this->context->customer->isLogged(),
			'customerName' => ($this->context->customer->logged ? $this->context->customer->firstname.' '.$this->context->customer->lastname : false),
			'firstName' => ($this->context->customer->logged ? $this->context->customer->firstname : false),
			'lastName' => ($this->context->customer->logged ? $this->context->customer->lastname : false),
			'order_process' => Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order'
		));
		return $this->display(__FILE__, 'blockuserinfo.tpl');
	}

	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'blockuserinfo.css', 'all');
	}

	public function hookDisplayNav($params)
	{
		// Add support for marketplace stuff
		$link            = new link();
		$customer_id     = $this->context->customer->id;

		// get the number of messages for the customers
		$sql = 'SELECT count(*) FROM up.message m WHERE m.from_user_ik = '.$customer_id.' and m.read is not null';
		$m_number_messages = Db::getInstance()->getValue($sql);		
       	$this->context->smarty->assign("m_number_messages", $m_number_messages);


		$obj_marketplace_seller = new SellerInfoDetail();
		$already_request = $obj_marketplace_seller->getMarketPlaceSellerIdByCustomerId($customer_id);

		if ($already_request) {
			$is_seller = $already_request['is_seller'];
			$this->context->smarty->assign("is_seller", $is_seller);
			if ($is_seller == 1)
			{
				$obj_marketplace_shop = new MarketplaceShop();
				$market_place_shop = $obj_marketplace_shop->getMarketPlaceShopInfoByCustomerId($customer_id);
				$id_shop   = $market_place_shop['id'];
				$param = array('shop' => $id_shop);
				$account_dashboard = $link->getModuleLink('marketplace', 'marketplaceaccount',$param);

		        $add_product    = $link->getModuleLink('marketplace', 'addproduct',$param);
				$edit_profile   = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>2,'edit-profile'=>1));
				$product_list   = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>3));
				$my_order    	= $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>4));
				$my_shop	= $link->getModuleLink('marketplace', 'shopstore',$param);

				//get the # of orders for the seller
				$sql = 'SELECT count(*) AS total from `'._DB_PREFIX_.'marketplace_shop_product` msp
				join  `'._DB_PREFIX_.'order_detail` ordd on (ordd.`product_id`=msp.`id_product`)
				join  `'._DB_PREFIX_.'orders` ord on (ordd.`id_order`=ord.`id_order`)
				join  `'._DB_PREFIX_.'marketplace_seller_product` msep on (msep.`id` = msp.`marketplace_seller_id_product`)
				join  `'._DB_PREFIX_.'marketplace_customer` mkc on (mkc.`marketplace_seller_id` = msep.`id_seller`)
				join  `'._DB_PREFIX_.'customer` cus on (mkc.`id_customer`=cus.`id_customer`)
				join  `'._DB_PREFIX_.'order_state_lang` ords on (ord.`current_state`=ords.`id_order_state`)
				where ords.id_lang=1 and cus.`id_customer`= '.$customer_id.' and ord.current_state in (2)';
			
				$m_number_orders = Db::getInstance()->getValue($sql);
				
					
				$this->context->smarty->assign("account_dashboard", $account_dashboard);
                $this->context->smarty->assign("add_product", $add_product);
                $this->context->smarty->assign("edit_profile", $edit_profile);
       	        $this->context->smarty->assign("product_list", $product_list);
      			$this->context->smarty->assign("my_order", $my_order);
               	$this->context->smarty->assign("my_shop", $my_shop);
               	$this->context->smarty->assign("m_number_orders", $m_number_orders);
			}
		} else {
			$this->context->smarty->assign("is_seller", -1);
		}

		return $this->display(__FILE__, 'nav.tpl');
	}
}
