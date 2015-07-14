<?php
/*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdminSellerTransferControllerCore extends AdminController
{ 
	
	public function __construct()
	{
		$this->bootstrap = true;

		$this->context = Context::getContext();
			
	    parent::__construct();
	}
	public function initContent()
        {
			
            parent::initContent();
            $smarty = $this->context->smarty;
			
			if(Tools::isSubmit('transfer')){
			if(Tools::getValue('id_seller')=='')
			$this->errors[] = Tools::displayError('Please select a seller first.');
			if(Tools::getValue('amount')<.50 || Tools::getValue('amount')>Tools::getValue('available_amt'))
			$this->errors[] = Tools::displayError('Amount must be greater than 50 cents and less than available amount.');
			}
			
			if(empty($this->errors) && Tools::isSubmit('transfer')){
			  
				  $action = $this->sellerTransfer();
				 if($action==1){
				   $this->context->smarty->assign('confirmation',1);
				 }else
				   $this->errors[] = Tools::displayError($action);
			}
			
			if(Tools::getValue('id_seller')!=''){
			  $transfers = Db::getInstance()->executeS('select * from `'._DB_PREFIX_.'seller_transfer` where `id_seller`='.Tools::getValue('id_seller'),false);
			  $transferred = Db::getInstance()->getValue('select SUM(`amount`) from `'._DB_PREFIX_.'seller_transfer` where `id_seller`='.Tools::getValue('id_seller'),false);
			   $turnover =  Db::getInstance()->getValue('select SUM(a.`price`+b.`shipping_amt`) from `'._DB_PREFIX_.'marketplace_commision_calc` a, `'._DB_PREFIX_.'marketplace_order_commision` b,`'._DB_PREFIX_.'marketplace_customer` c where a.`id_order`=b.`id_order` && a.`customer_id`=c.`id_customer` && c.`marketplace_seller_id`='.Tools::getValue('id_seller'),false);
			  $seller_turnover =  Db::getInstance()->getValue('select SUM(a.`price`+b.`shipping_amt`-a.`commision`) from `'._DB_PREFIX_.'marketplace_commision_calc` a, `'._DB_PREFIX_.'marketplace_order_commision` b,`'._DB_PREFIX_.'marketplace_customer` c where a.`id_order`=b.`id_order` && a.`customer_id`=c.`id_customer` && c.`marketplace_seller_id`='.Tools::getValue('id_seller'),false);
			  $commission =  Db::getInstance()->getValue('select SUM(a.`commision`) from `'._DB_PREFIX_.'marketplace_commision_calc` a, 
			  `'._DB_PREFIX_.'marketplace_customer` b where a.`customer_id`=b.`id_customer` && b.`marketplace_seller_id`='.Tools::getValue('id_seller'),false);
			}
			$sellers =  Db::getInstance()->executeS('select * from `'._DB_PREFIX_.'marketplace_seller_info` order by shop_name asc',false);
		
			$currency ='USD';

		$this->context->smarty->assign(array(
		        'turnover' => number_format((float)$turnover, 2, '.', ''),
				'commission' => number_format((float)$commission, 2, '.', ''),
				'seller_turnover' => number_format((float)$seller_turnover, 2, '.', ''),
		        'transfers' => $transfers,
				'transferred' => number_format((float)$transferred, 2, '.', ''),
				'sellers' => $sellers,
				'id_seller' => Tools::getValue('id_seller'),
				'available_amt' => number_format((float)($seller_turnover-$transferred), 2, '.', ''),
				'currency' => $currency
			));
		
	}
	
	public function sellerTransfer()
	{	
	
	    $details =  Db::getInstance()->getRow('select a.stripe_seller_id,a.`id_customer` from `'._DB_PREFIX_.'stripepro_sellers` a, 
			`'._DB_PREFIX_.'marketplace_customer` b where a.`id_customer`=b.`id_customer` && b.`marketplace_seller_id`='.Tools::getValue('id_seller'));
			
		include_once($_SERVER['DOCUMENT_ROOT']._MODULE_DIR_.'stripepro/lib/Stripe.php'); 
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));
			try
				{
				$result_json =  \Stripe\Transfer::create(array('amount' => Tools::getValue('amount')*100,'currency' => 'USD','destination' => $details['stripe_seller_id']));
				}
			catch (Exception $e)
				{
					$stripe_transfer_error = $e->getMessage();
					Logger::addLog($this->l('Stripe - transfer failed').' '.$e->getMessage(), 1, null, 'Customer', (int)$details['id_customer'], true);
					
					return $stripe_transfer_error;
				}
	
			  Db::getInstance()->execute('insert into `'._DB_PREFIX_.'seller_transfer` (`id_seller`,`id_customer`,`destination`,`transaction_id`,`amount`, `currency`, `status`, `date_add`) VALUES ('.Tools::getValue('id_seller').','.$details['id_customer'].',"'.$details['stripe_seller_id'].'","'.$result_json->id.'",'.$result_json->amount*.01.',"'.$result_json->currency.'","'.$result_json->status.'",NOW())');
			 
			 
		 return true;
		}
	
}
