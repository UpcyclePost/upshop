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

				$this->context->smarty->assign("account_dashboard", $account_dashboard);

				$this->context->smarty->assign("shop_id", $id_shop);

				$this->context->smarty->assign("up_user_id", $this->context->customer->website);
			}
		} else {
			$this->context->smarty->assign("is_seller", -1);
		}

		return $this->display(__FILE__, 'nav.tpl');
	}
}
