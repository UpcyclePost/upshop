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

class PdfInvoiceControllerCore extends FrontController
{
	public $php_self = 'pdf-invoice';
	protected $display_header = false;
	protected $display_footer = false;

	public $content_only = true;

	protected $template;
	public $filename;

	public function postProcess()
	{
		if (!$this->context->customer->isLogged() && !Tools::getValue('secure_key'))
			Tools::redirect('index.php?controller=authentication&back=pdf-invoice');

		if (!(int)Configuration::get('PS_INVOICE'))
			die(Tools::displayError('Invoices are disabled in this shop.'));

		$id_order = (int)Tools::getValue('id_order');
		if (Validate::isUnsignedId($id_order))
			$order = new Order((int)$id_order);

		if (!isset($order) || !Validate::isLoadedObject($order))
			die(Tools::displayError('The invoice was not found.'));

		If (_PS_MODE_DEV_)
			{
				echo "<br>Order : " . $id_order;
				echo "<br>this->context->customer->id : " . $this->context->customer->id;		
				echo "<br>order->id_customer : " . $order->id_customer;
				echo "<br>Secure_key : " . Tools::getValue('secure_key');
				echo "<br>order->secure_key : " . $order->secure_key;
				echo "<pre>";
		//		print_r($order);
				echo "</pre><br>";
				echo "Customer Match?  : " . ((isset($this->context->customer->id) && $order->id_customer != $this->context->customer->id)) . "<br>";
			}	
		// check to see if customer requesting invoice matches customer that placed the order
		if ((isset($this->context->customer->id) && $order->id_customer != $this->context->customer->id))
			{
			If (_PS_MODE_DEV_) 
			{
				echo "customer does not match<br>";
				echo "test : " . ((Tools::isSubmit('secure_key') && $order->secure_key != Tools::getValue('secure_key'))) . "<br>";
			}
			// customer does not match, but do they have the secure key?
			if ((Tools::isSubmit('secure_key')))
				{
				// secure_key submitted, does it match?
				if ($order->secure_key != Tools::getValue('secure_key'))
					{
					If (_PS_MODE_DEV_){echo "customer does not match and no secure key<br>";} 
					die(Tools::displayError('The invoice was not found'));
					}
				}
				else
				{
					If (_PS_MODE_DEV_){echo "no secure key<br>";}
					die(Tools::displayError('The invoice was not found.'));					
				}
			}

		if (!OrderState::invoiceAvailable($order->getCurrentState()) && !$order->invoice_number)
			die(Tools::displayError('No invoice is available.'));

		$this->order = $order;
	}

	public function display()
	{
		$order_invoice_list = $this->order->getInvoicesCollection();
		Hook::exec('actionPDFInvoiceRender', array('order_invoice_list' => $order_invoice_list));

		$pdf = new PDF($order_invoice_list, PDF::TEMPLATE_INVOICE, $this->context->smarty);
		$pdf->render();
	}

}
