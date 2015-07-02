<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class MyAccountControllerCore extends FrontController
{
	public $auth = true;
	public $php_self = 'my-account';
	public $authRedirection = 'my-account';
	public $ssl = true;

	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(_THEME_CSS_DIR_.'my-account.css');
	}

	/**
	 * Assign template vars related to page content
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();

		$has_address = $this->context->customer->getAddresses($this->context->language->id);
		$this->context->smarty->assign(array(
			'has_customer_an_address' => empty($has_address),
			'voucherAllowed' => (int)CartRule::isFeatureActive(),
			'returnAllowed' => (int)Configuration::get('PS_ORDER_RETURN')
		));
		$this->context->smarty->assign('HOOK_CUSTOMER_ACCOUNT', Hook::exec('displayCustomerAccount'));
		
		$customer_id     = $this->context->customer->id;
		// get the number of messages for the customers
		If (isset($customer_id)){
			// get the up customer - website field
			$sql = 'SELECT website from `'._DB_PREFIX_.'customer` c Where c.id_customer = '.$customer_id;
			$c_website = Db::getInstance()->getValue($sql);
			
			If (isset($c_website)){
				$sql = 'SELECT count(*) FROM up.message m WHERE m.to_user_ik = '.$c_website.' and m.read is null';
				$m_number_messages = Db::getInstance()->getValue($sql,false);
		       	$this->context->smarty->assign("m_number_messages", $m_number_messages);
			}
		}
		
		$obj_marketplace_seller = new SellerInfoDetail();
		if (isset($customer_id)){
			$already_request = $obj_marketplace_seller->getMarketPlaceSellerIdByCustomerId($customer_id);
		}
		else{
			$already_request = null;
		}
		if ($already_request) {
			$is_seller = $already_request['is_seller'];
			

			if ($is_seller == 1)
			{
	
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
					
	           	$this->context->smarty->assign("m_number_orders", $m_number_orders);
			}
		}

		$this->setTemplate(_PS_THEME_DIR_.'my-account.tpl');
	}
}

