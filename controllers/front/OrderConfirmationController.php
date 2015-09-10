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

class OrderConfirmationControllerCore extends FrontController
{
	public $ssl = true;
	public $php_self = 'order-confirmation';
	public $id_cart;
	public $id_module;
	public $id_order;
	public $reference;
	public $secure_key;

	/**
	 * Initialize order confirmation controller
	 * @see FrontController::init()
	 */
	public function init()
	{
		parent::init();

		$this->id_cart = (int)(Tools::getValue('id_cart', 0));
		$is_guest = false;

		/* check if the cart has been made by a Guest customer, for redirect link */
		if (Cart::isGuestCartByCartId($this->id_cart))
		{
			$is_guest = true;
			$redirectLink = 'index.php?controller=guest-tracking';
		}
		else
			$redirectLink = 'index.php?controller=history';

		$this->id_module = (int)(Tools::getValue('id_module', 0));
		$this->id_order = Order::getOrderByCartId((int)($this->id_cart));
		$this->secure_key = Tools::getValue('key', false);
		$order = new Order((int)($this->id_order));
		if ($is_guest)
		{
			$customer = new Customer((int)$order->id_customer);
			$redirectLink .= '&id_order='.$order->reference.'&email='.urlencode($customer->email);
		}
		if (!$this->id_order || !$this->id_module || !$this->secure_key || empty($this->secure_key))
			Tools::redirect($redirectLink.(Tools::isSubmit('slowvalidation') ? '&slowvalidation' : ''));
		$this->reference = $order->reference;
		if (!Validate::isLoadedObject($order) || $order->id_customer != $this->context->customer->id || $this->secure_key != $order->secure_key)
			Tools::redirect($redirectLink);
		$module = Module::getInstanceById((int)($this->id_module));
		if ($order->module != $module->name)
			Tools::redirect($redirectLink);
	}

	/**
	 * Assign template vars related to page content
	 * @see FrontController::initContent()
	 
	 */
	public function initContent()
	{
		parent::initContent();

		$this->context->smarty->assign(array(
			'is_guest' => $this->context->customer->is_guest,
			'HOOK_ORDER_CONFIRMATION' => $this->displayOrderConfirmation(),
			'HOOK_PAYMENT_RETURN' => $this->displayPaymentReturn()
		));

		$order = new Order($this->id_order);
		$currency = new Currency($order->id_currency);
		if (Validate::isLoadedObject($order))
		{
			$this->context->smarty->assign('order', $order);
			
			$sql = "SELECT sum(ord.`total_products`) as `total_products`, sum(ord.`total_shipping`) as `total_shipping`, sum(ord.`total_paid`) as `total_paid` 
			from `" . _DB_PREFIX_ . "orders` ord where ord.`reference`='".$order->reference."'";
			
			$orderheader =  Db::getInstance()->executeS($sql);
			
			$this->context->smarty->assign('orderheader', $orderheader);
			
			$this->context->smarty->assign('total_to_pay', number_format($orderheader[0]['total_paid'],2));
			$this->context->smarty->assign('currency_iso_code', $currency->iso_code);
			$this->context->smarty->assign('currency_sign', $currency->sign);	
								
			$sql = "SELECT ord.`id_order` as `id_order`, ordd.`id_order_detail` as `id_order_detail`,ordd.`product_name` as `ordered_product_name`,
			ordd.`total_price_tax_excl` as total_price,ordd.`product_quantity` as qty, ordd.`id_order` as id_order, p.`price` as unit_price,
			ord.`id_customer` as order_by_cus,  ord.`payment` as payment_mode,ord.`date_add`,ord.`id_currency` as `id_currency`, ord.`reference` as `ref`, 
			ords.`name`as order_status , cus.`website` as website , cus.`firstname` as name, msi.`shop_name` as shop_name
					from `" . _DB_PREFIX_ . "orders` ord
					 join `" . _DB_PREFIX_ . "order_detail` ordd on (ord.`id_order`= ordd.`id_order`) 
					 join `" . _DB_PREFIX_ . "order_state_lang` ords on (ord.`current_state`=ords.`id_order_state`)
					 left join `" . _DB_PREFIX_ . "product` p on (ordd.`product_id`= p.`id_product`) 
					 left join `" . _DB_PREFIX_ . "marketplace_shop_product` msp on (ordd.`product_id`= msp.`id_product`)
					 left join `" . _DB_PREFIX_ . "marketplace_seller_product` msep on (msep.`id` = msp.`marketplace_seller_id_product`) 
					 left join `" . _DB_PREFIX_ . "marketplace_customer` mkc on (mkc.`marketplace_seller_id` = msep.`id_seller`) 
					 left join `" . _DB_PREFIX_ . "customer` cus on (mkc.`id_customer`=cus.`id_customer`) 
					 left join `" . _DB_PREFIX_ . "marketplace_seller_info` msi on (msep.`id_seller` = msi.`id`)
					 where ord.`reference`='".$order->reference."'";

			$this->context->smarty->assign('orderitems', Db::getInstance()->executeS($sql));				
		}

		if ($this->context->customer->is_guest)
		{
			$this->context->smarty->assign(array(
				'id_order' => $this->id_order,
				'reference_order' => $this->reference,
				'id_order_formatted' => sprintf('#%06d', $this->id_order),
				'email' => $this->context->customer->email
			));
			/* If guest we clear the cookie for security reason */
			$this->context->customer->mylogout();
		}

		$this->setTemplate(_PS_THEME_DIR_.'order-confirmation.tpl');
	}

	/**
	 * Execute the hook displayPaymentReturn
	 */
	public function displayPaymentReturn()
	{
		if (Validate::isUnsignedId($this->id_order) && Validate::isUnsignedId($this->id_module))
		{
			$params = array();
			$order = new Order($this->id_order);
			$currency = new Currency($order->id_currency);

			if (Validate::isLoadedObject($order))
			{
				$params['total_to_pay'] = $order->getOrdersTotalPaid();
				$params['currency'] = $currency->sign;
				$params['objOrder'] = $order;
				$params['currencyObj'] = $currency;

				return Hook::exec('displayPaymentReturn', $params, $this->id_module);
			}
		}
		return false;
	}

	/**
	 * Execute the hook displayOrderConfirmation
	 */
	public function displayOrderConfirmation()
	{
		if (Validate::isUnsignedId($this->id_order))
		{
			$params = array();
			$order = new Order($this->id_order);
			$currency = new Currency($order->id_currency);

			if (Validate::isLoadedObject($order))
			{
				$params['total_to_pay'] = $order->getOrdersTotalPaid();
				$params['currency'] = $currency->sign;
				$params['objOrder'] = $order;
				$params['currencyObj'] = $currency;

				return Hook::exec('displayOrderConfirmation', $params);
			}
		}
		return false;
	}
	
	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(array(
				_MODULE_DIR_.'marketplace/css/marketplace_account.css'
			));
		$this->addJqueryPlugin(array('footable'));	
			
	}
}

