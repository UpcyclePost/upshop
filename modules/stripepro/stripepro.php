<?php
/**
*Copyright (c) 2015. All rights reserved - NTS
*You are NOT allowed to modify the software. 
*It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*/

if (!defined('_PS_VERSION_'))
	exit;

class stripepro extends PaymentModule
{
	protected $backward = false;

	public function __construct()
	{
		$this->name = 'stripepro';
		$this->tab = 'payments_gateways';
		$this->version = '2.0.1';
		$this->author = 'NTS';
		$this->need_instance = 0;
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		$this->module_key = '3dd3287465380c358a021f1378ed9f61';

		parent::__construct();

		$this->displayName = $this->l('Stripe Pro');
		$this->description = $this->l('Accept payments by Credit/Debit Cards & Bitcoin with Stripe (Visa, Mastercard, Amex, Discover and Diners Club)');
		$this->confirmUninstall = $this->l('Warning: all the Stripe customers credit cards and transaction details saved in your database will be deleted. Are you sure you want uninstall this module?');

		/* Backward compatibility */
		if (_PS_VERSION_ < '1.5')
		{
			$this->backward_error = $this->l('In order to work properly in PrestaShop v1.4, the Stripe module requires the backward compatibility module at least v0.3.').'<br />'.
				$this->l('You can download this module for free here: http://addons.prestashop.com/en/modules-prestashop/6222-backwardcompatibility.html');
			if (file_exists(_PS_MODULE_DIR_.'backwardcompatibility/backward_compatibility/backward.php'))
			{
				include(_PS_MODULE_DIR_.'backwardcompatibility/backward_compatibility/backward.php');
				$this->backward = true;
			}
			else
				$this->warning = $this->backward_error;
		}
		else
			$this->backward = true;
			
	}

	/**
	 * Stripe's module installation
	 *
	 * @return boolean Install result
	 */
	public function install()
	{
		if (!$this->backward && _PS_VERSION_ < 1.5)
		{
			echo '<div class="error">'.Tools::safeOutput($this->backward_error).'</div>';
			return false;
		}

		/* For 1.4.3 and less compatibility */
		$updateConfig = array(
			'PS_OS_CHEQUE' => 1,
			'PS_OS_PAYMENT' => 2,
			'PS_OS_PREPARATION' => 3,
			'PS_OS_SHIPPING' => 4,
			'PS_OS_DELIVERED' => 5,
			'PS_OS_CANCELED' => 6,
			'PS_OS_REFUND' => 7,
			'PS_OS_ERROR' => 8,
			'PS_OS_OUTOFSTOCK' => 9,
			'PS_OS_BANKWIRE' => 10,
			'PS_OS_PAYPAL' => 11,
			'PS_OS_WS_PAYMENT' => 12);

		foreach ($updateConfig as $u => $v)
			if (!Configuration::get($u) || (int)Configuration::get($u) < 1)
			{
				if (defined('_'.$u.'_') && (int)constant('_'.$u.'_') > 0)
					Configuration::updateValue($u, constant('_'.$u.'_'));
				else
					Configuration::updateValue($u, $v);
			}

		$ret = parent::install() && $this->registerHook('payment') && $this->registerHook('header') && $this->registerHook('backOfficeHeader') && $this->registerHook('paymentReturn') &&
		Configuration::updateValue('STRIPE_CAPTURE_TYPE', true) && Configuration::updateValue('STRIPE_MODE', 0) && Configuration::updateValue('STRIPE_SAVE_TOKENS', 1) &&
		Configuration::updateValue('STRIPE_SAVE_TOKENS_ASK', 1) && Configuration::updateValue('STRIPE_PENDING_ORDER_STATUS', (int)Configuration::get('PS_OS_PAYMENT')) &&
		Configuration::updateValue('STRIPE_PAYMENT_ORDER_STATUS', (int)Configuration::get('PS_OS_PAYMENT')) &&
		Configuration::updateValue('STRIPE_CHARGEBACKS_ORDER_STATUS', (int)Configuration::get('PS_OS_ERROR')) &&
		Configuration::updateValue('STRIPE_WEBHOOK_TOKEN', md5(Tools::passwdGen())) && $this->installDb();

		/* The hook "displayMobileHeader" has been introduced in v1.5.x - Called separately to fail silently if the hook does not exist */
		$this->registerHook('displayMobileHeader');

		return $ret;
	}

	/**
	 * Stripe's module database tables installation
	 *
	 * @return boolean Database tables installation result
	 */
	public function installDb()
	{
		return Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'stripepro_customer` (`id_stripe_customer` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`stripe_customer_id` varchar(32) NOT NULL, `token` varchar(32) NOT NULL, `id_customer` int(10) unsigned NOT NULL,
		`cc_last_digits` int(11) NOT NULL, `date_add` datetime NOT NULL, PRIMARY KEY (`id_stripe_customer`), KEY `id_customer` (`id_customer`),
		KEY `token` (`token`)) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1') &&
		Db::getInstance()->Execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'stripepro_transaction` (`id_stripe_transaction` int(11) NOT NULL AUTO_INCREMENT,
		`type` enum(\'payment\',\'refund\') NOT NULL,`source` varchar(32) NOT NULL DEFAULT \'card\',`btc_address` VARCHAR( 50 ) NOT NULL, `id_stripe_customer` int(10) unsigned NOT NULL, `id_cart` int(10) unsigned NOT NULL,
		`id_order` int(10) unsigned NOT NULL, `id_transaction` varchar(32) NOT NULL, `amount` decimal(10,2) NOT NULL, `status` enum(\'paid\',\'unpaid\',\'uncaptured\') NOT NULL,
		`currency` varchar(3) NOT NULL, `cc_type` varchar(16) NOT NULL, `cc_exp` varchar(8) NOT NULL, `cc_last_digits` int(11) NOT NULL,
		`cvc_check` tinyint(1) NOT NULL DEFAULT \'0\', `fee` decimal(10,2) NOT NULL, `mode` enum(\'live\',\'test\') NOT NULL,
		`date_add` datetime NOT NULL, `charge_back` tinyint(1) NOT NULL DEFAULT \'0\', PRIMARY KEY (`id_stripe_transaction`), KEY `idx_transaction` (`type`,`id_order`,`status`))
		ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1') && Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."stripepro_subscription` (
  `id_stripe_subscription` int(10) NOT NULL AUTO_INCREMENT,
  `stripe_subscription_id` varchar(32) NOT NULL,
  `stripe_customer_id` varchar(32) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `stripe_plan_id` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `current_period_start` varchar(32) NOT NULL,
  `current_period_end` varchar(32) NOT NULL,
  `status` enum('trialing','active','past_due','canceled','unpaid') NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_stripe_subscription`)
) ENGINE="._MYSQL_ENGINE_."  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1") && Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."stripepro_plans` (
  `id_stripe_plan` int(10) NOT NULL AUTO_INCREMENT,
  `stripe_plan_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `interval` enum('day','week','month','year') NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(3) NOT NULL,
  `interval_count` varchar(5) NOT NULL,
  `trial_period_days` int(5) NOT NULL,
  PRIMARY KEY (`id_stripe_plan`)
) ENGINE="._MYSQL_ENGINE_."  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
	}

	/**
	 * Stripe's module uninstallation (Configuration values, database tables...)
	 *
	 * @return boolean Uninstall result
	 */
	public function uninstall()
	{
		return parent::uninstall() && Configuration::deleteByName('STRIPE_PUBLIC_KEY_TEST') &&  Configuration::deleteByName('STRIPE_CAPTURE_TYPE') && Configuration::deleteByName('STRIPE_PUBLIC_KEY_LIVE')
		&& Configuration::deleteByName('STRIPE_MODE') && Configuration::deleteByName('STRIPE_PRIVATE_KEY_TEST') && Configuration::deleteByName('STRIPE_PRIVATE_KEY_LIVE') &&
		Configuration::deleteByName('STRIPE_SAVE_TOKENS') && Configuration::deleteByName('STRIPE_SAVE_TOKENS_ASK') && Configuration::deleteByName('STRIPE_CHARGEBACKS_ORDER_STATUS') &&
		Configuration::deleteByName('STRIPE_PENDING_ORDER_STATUS') && Configuration::deleteByName('STRIPE_PAYMENT_ORDER_STATUS') && Configuration::deleteByName('STRIPE_WEBHOOK_TOKEN');
		
	}

	public function hookDisplayMobileHeader()
	{
		return $this->hookHeader();
	}

	/**
	 * Load Javascripts and CSS related to the Stripe's module
	 * Only loaded during the checkout process
	 *
	 * @return string HTML/JS Content
	 */
	public function hookHeader()
	{
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;

		/* Continue only if we are in the checkout process */
		if (Tools::getValue('controller') != 'order-opc' && (!($_SERVER['PHP_SELF'] == __PS_BASE_URI__.'order.php' || $_SERVER['PHP_SELF'] == __PS_BASE_URI__.'order-opc.php' || Tools::getValue('controller') == 'order' || Tools::getValue('controller') == 'orderopc' || Tools::getValue('step') == 3)))
			return;

		/* Load JS and CSS files through CCC */
		$this->context->controller->addCSS($this->_path.'views/css/stripe-prestashop.css');
		
		return '<script src="https://checkout.stripe.com/checkout.js"></script>';
	}

	
	/**
	 * Display the two fieldsets containing Stripe's transactions details
	 * Visible on the Order's detail page in the Back-office only
	 *
	 * @return string HTML/JS Content
	 */
	public function hookBackOfficeHeader()
	{
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;
		
		/* Update the Stripe Plans list */
		if(Tools::isSubmit('SubmitListPlans'))
		 $this->listPlans();
		 
		 /* Update the Stripe Subscriptions for all existing customers */
		if(Tools::isSubmit('SubmitSubSync'))
		 $this->syncAllSubscriptions();
		 
		
		/* Continue if we are on the order's details page (Back-office) */
		
		if(Tools::getIsset('vieworder') && Tools::getIsset('id_order'))
		{
			
			$order = new Order((int)Tools::getValue('id_order'));

		/* If the "Refund" button has been clicked, check if we can perform a partial or full refund on this order */
		if (Tools::isSubmit('SubmitStripeRefund') && Tools::getIsset('stripe_amount_to_refund') && Tools::getIsset('id_transaction_stripe'))
		{
			/* Get transaction details and make sure the token is valid */
			$stripe_transaction_details = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'stripepro_transaction WHERE id_order = '.(int)Tools::getValue('id_order').' AND type = \'payment\' AND status = \'paid\'');
			if (isset($stripe_transaction_details['id_transaction']) && $stripe_transaction_details['id_transaction'] === Tools::getValue('id_transaction_stripe'))
			{
				/* Check how much has been refunded already on this order */
				$stripe_refunded = Db::getInstance()->getValue('SELECT SUM(amount) FROM '._DB_PREFIX_.'stripepro_transaction WHERE id_order = '.(int)Tools::getValue('id_order').' AND type = \'refund\' AND status = \'paid\'');
				if (Tools::getValue('stripe_amount_to_refund') <= number_format($stripe_transaction_details['amount'] - $stripe_refunded, 2, '.', ''))
					$this->processRefund(Tools::getValue('id_transaction_stripe'), (float)Tools::getValue('stripe_amount_to_refund'), $stripe_transaction_details);
				else
					$this->_errors['stripe_refund_error'] = $this->l('You cannot refund more than').' '.Tools::displayPrice($stripe_transaction_details['amount'] - $stripe_refunded).' '.$this->l('on this order');
			}
		}
		
		/* If the "Capture" button has been clicked, check if we can perform a partial or full capture on this order */
		if (Tools::isSubmit('SubmitStripeCapture') && Tools::getIsset('stripe_amount_to_capture') && Tools::getIsset('id_transaction_stripe'))
		{
			/* Get transaction details and make sure the token is valid */
			$stripe_transaction_details = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'stripepro_transaction WHERE id_order = '.(int)Tools::getValue('id_order').' AND type = \'payment\' AND status = \'uncaptured\'');
			if (isset($stripe_transaction_details['id_transaction']) && $stripe_transaction_details['id_transaction'] === Tools::getValue('id_transaction_stripe'))
			{
				if (Tools::getValue('stripe_amount_to_capture') <= number_format($stripe_transaction_details['amount'], 2, '.', ''))
					$this->processCapture(Tools::getValue('id_transaction_stripe'), (float)Tools::getValue('stripe_amount_to_capture'));
				else
					$this->_errors['stripe_capture_error'] = $this->l('You cannot capture more than').' '.Tools::displayPrice($stripe_transaction_details['amount'] - $stripe_refunded).' '.$this->l('on this order');
				
			}
		}

		/* Check if the order was paid with Stripe and display the transaction details */
		if (Db::getInstance()->getValue('SELECT module FROM '._DB_PREFIX_.'orders WHERE id_order = '.(int)Tools::getValue('id_order')) == $this->name)
		{
			/* Get the transaction details */
			$stripe_transaction_details = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'stripepro_transaction WHERE id_order = '.(int)Tools::getValue('id_order').' AND type = \'payment\' AND status IN (\'paid\',\'uncaptured\')');

			/* Get all the refunds previously made (to build a list and determine if another refund is still possible) */
			$stripe_refunded = 0;
			$output_refund = '';
			$stripe_refund_details = Db::getInstance()->ExecuteS('SELECT amount, status, date_add FROM '._DB_PREFIX_.'stripepro_transaction
			WHERE id_order = '.(int)Tools::getValue('id_order').' AND type = \'refund\' ORDER BY date_add DESC');
			foreach ($stripe_refund_details as $stripe_refund_detail)
			{
				$stripe_refunded += ($stripe_refund_detail['status'] == 'paid' ? $stripe_refund_detail['amount'] : 0);
				$output_refund .= '<tr'.($stripe_refund_detail['status'] != 'paid' ? ' style="background: #FFBBAA;"': '').'><td>'.
				Tools::safeOutput($stripe_refund_detail['date_add']).'</td><td style="">'.Tools::displayPrice($stripe_refund_detail['amount'], (int)$order->id_currency).
				'</td><td>'.($stripe_refund_detail['status'] == 'paid' ? $this->l('Processed') : $this->l('Error')).'</td></tr>';
			}
			$currency = new Currency((int)$order->id_currency);
			$c_char = $currency->sign;
			$output = '
			<script type="text/javascript">
				$(document).ready(function() {
					var appendEl;
					if ($(\'select[name=id_order_state]\').is(":visible")) {
						appendEl = $(\'select[name=id_order_state]\').parents(\'form\').after($(\'<div/>\'));
					} else {
						appendEl = $("#status");
					}
					$(\'<div class="panel panel-highlighted" style="padding: 5px 10px;"><fieldset'.(_PS_VERSION_ < 1.5 ? ' style="width: 400px;"' : '').'><legend><img src="../img/admin/money.gif" alt="" />'.$this->l('Stripe Payment Details').'</legend>';

			if (!empty($stripe_transaction_details['id_transaction'])){
				$output .= $this->l('Stripe Transaction ID:').' '.Tools::safeOutput($stripe_transaction_details['id_transaction']).'<br /><br />'.
				$this->l('Status:').' <span style="font-weight: bold; color: '.($stripe_transaction_details['status'] == 'paid' ? 'green;">'.$this->l('Paid') : '#CC0000;">'.$this->l('Unpaid')).'</span><br />'.
				$this->l('Amount:').' '.Tools::displayPrice($stripe_transaction_details['amount'], (int)$order->id_currency).'<br />'.
				$this->l('Processed on:').' '.Tools::safeOutput($stripe_transaction_details['date_add']).'<br />';
				
				if($stripe_transaction_details['source']=='card'){
				$output .= $this->l('Credit card:').' '.Tools::safeOutput($stripe_transaction_details['cc_type']).' ('.$this->l('Exp.:').' '.Tools::safeOutput($stripe_transaction_details['cc_exp']).')<br />'.$this->l('Last 4 digits:').' '.sprintf('%04d', $stripe_transaction_details['cc_last_digits']).' ('.$this->l('CVC Check:').' '.($stripe_transaction_details['cvc_check'] ? $this->l('OK') : '<span style="color: #CC0000; font-weight: bold;">'.$this->l('FAILED').'</span>').')<br />';
				}else
				  $output .= $this->l('Address:').' '.Tools::safeOutput($stripe_transaction_details['btc_address']).'<br />'.
				  $this->l('Bitcoin:').' B⃦'.sprintf('%.8f',$stripe_transaction_details['amount']*.01).' BTC<br />'.
				  $this->l('Filled:').' '.($stripe_transaction_details['cvc_check'] ? $this->l('Yes') : '<span style="color: #CC0000; font-weight: bold;">'.$this->l('No').'</span>').'<br />';
				
				$output .= $this->l('Processing Fee:').' '.Tools::displayPrice($stripe_transaction_details['fee'], (int)$order->id_currency).'<br /><br />'.
				$this->l('Mode:').' <span style="font-weight: bold; color: '.($stripe_transaction_details['mode'] == 'live' ? 'green;">'.$this->l('Live') : '#CC0000;">'.$this->l('Test (You will not receive any payment, until you enable the "Live" mode)')).'</span>';
			}else
				$output .= '<b style="color: #CC0000;">'.$this->l('Warning:').'</b> '.$this->l('The customer paid using Stripe and an error occured (check details at the bottom of this page)');
				
				 $output .= '</fieldset><br />';
				 if(Tools::getIsset('SubmitStripeCapture')){
				 $output .= '<div  class="bootstrap">'.((empty($this->_errors['stripe_capture_error']) && Tools::getIsset('id_transaction_stripe') && Tools::getIsset('SubmitStripeCapture')) ? '<div class="conf confirmation alert alert-success">'.$this->l('Your capture was successfully processed').'</div>' : '').
			(!empty($this->_errors['stripe_capture_error']) ? '<div style="color: #CC0000; font-weight: bold;" class="alert alert-danger">'.$this->l('Error:').' '.Tools::safeOutput($this->_errors['stripe_capture_error']).'</div>' : '').'</div>';
				 }
			
			
           if($stripe_transaction_details['status'] == 'uncaptured'){
			   
			   $date2 = $stripe_transaction_details['date_add']; 
               $diff = strtotime($date2 ."+6 days +21 hours") - strtotime('now');
			   
			   $secondsInAMinute = 60;
			   $secondsInAnHour  = 60 * $secondsInAMinute;
               $secondsInADay    = 24 * $secondsInAnHour;

			  // extract days
			  $days = floor($diff / $secondsInADay);
			  // extract hours
			  $hourSeconds = $diff % $secondsInADay;
			  $hours = floor($hourSeconds / $secondsInAnHour);

			  $timeleft = $days ." days & ". $hours." hrs";
	   
			$output .= '<fieldset'.(_PS_VERSION_ < 1.5 ? ' style="width: 400px;"' : '').'><legend><img src="../img/admin/money.gif" alt="" />'.$this->l('Proceed to a full or partial capture via Stripe').'</legend>';
			if($diff>0){
			$output .= '<form action="" method="post">'.$this->l('Capture:').' $ <input type="text" value="'.number_format($stripe_transaction_details['amount'], 2, '.', '').'" name="stripe_amount_to_capture" style="display: inline-block; width: 60px;" /> <input type="hidden" name="id_transaction_stripe" value="'.Tools::safeOutput($stripe_transaction_details['id_transaction']).'" /><input type="submit" class="button" onclick="return confirm(\\\''.addslashes($this->l('Do you want to proceed to this capture?')).'\\\');" name="SubmitStripeCapture" value="'.$this->l('Process Capture').'" /></form><font style="color:red;font-size:13px;"> <br>'.$this->l('NOTE: Time left to Capture payment:').' <b>'.$timeleft.'</b> '.$this->l('otherwise payment will be automatically refunded.').'</font>';}else
			$output .= '<font style="color:red;"> <b>'.$this->l('7 days has been passed so the payment has been refunded.')."</font></b>";
			
			$output .= '</fieldset><br /></div>\').appendTo(appendEl);
				});
			</script>';
				}else {

			$output .= '</fieldset><br /><fieldset'.(_PS_VERSION_ < 1.5 ? ' style="width: 400px;"' : '').'  class="bootstrap"><legend><img src="../img/admin/money.gif" alt="" />'.$this->l('Proceed to a full or partial refund via Stripe').'</legend>';
			if(Tools::getIsset('SubmitStripeRefund')){
			$output .= ((empty($this->_errors['stripe_refund_error']) &&  Tools::getIsset('id_transaction_stripe')) ? '<div class="conf confirmation alert alert-success">'.$this->l('Your refund was successfully processed').'</div>' : '').
			(!empty($this->_errors['stripe_refund_error']) ? '<div style="color: #CC0000; font-weight: bold;" class="alert alert-danger">'.$this->l('Error:').' '.Tools::safeOutput($this->_errors['stripe_refund_error']).'</div>' : '');}
			$output .= $this->l('Already refunded:').' <b>'.Tools::displayPrice($stripe_refunded, (int)$order->id_currency).'</b><br /><br />'.($stripe_refunded ? '<table class="table" cellpadding="0" cellspacing="0" style="font-size: 12px;"><tr><th>'.$this->l('Date').'</th><th>'.$this->l('Amount refunded').'</th><th>'.$this->l('Status').'</th></tr>'.$output_refund.'</table><br />' : '').
			($stripe_transaction_details['amount'] > $stripe_refunded ? '<form action="" method="post">'.$this->l('Refund:'). ' ' . $c_char .' <input type="text" value="'.number_format($stripe_transaction_details['amount'] - $stripe_refunded, 2, '.', '').
			'" name="stripe_amount_to_refund" style="display: inline-block; width: 60px;" /> <input type="hidden" name="id_transaction_stripe" value="'.
			Tools::safeOutput($stripe_transaction_details['id_transaction']).'" /><input type="submit" class="button" onclick="return confirm(\\\''.addslashes($this->l('Do you want to proceed to this refund?')).'\\\');" name="SubmitStripeRefund" value="'.
			$this->l('Process Refund').'" /></form>' : '').'</fieldset><br /></div>\').appendTo(appendEl);
				});
			</script>';
		}

			return $output;
	   }
		
	  }
	  
	  if(Tools::getIsset('viewcustomer') && Tools::getIsset('id_customer'))
	   {    /* Continue if we are on the Customer's details page (Back-office) */
	   
	     $stripe_customer_id = Db::getInstance()->getValue('SELECT `stripe_customer_id` FROM '._DB_PREFIX_.'stripepro_customer WHERE id_customer = '.(int)Tools::getValue('id_customer'));
		 /* Update the Stripe Subscriptions for all existing customers */
		if(Tools::isSubmit('SubmitCusSubSync'))
		 $this->syncSubscriptions($stripe_customer_id);
		 
			
		  /* "Add Subsciption" button click will perform the task of adding new subscription to the customer */
		if (Tools::isSubmit('SubmitAddSub'))
			$this->addStripeSubscription($stripe_customer_id,Tools::getValue('id_stripe_plan'));
			
			
		   /* "Cancel Subsciption" button click will perform the task of cancelling new subscription to the customer */
		if(Tools::isSubmit('SubmitCancelSub'))
			$this->cancelSubscription($stripe_customer_id,Tools::getValue('stripe_subscription_id'));
		
		  /* Get the subscription details */
			$stripe_subscription_details = Db::getInstance()->executeS("SELECT a.*,CONCAT('<b>',b.`name`,'</b> (',UCASE(b.`currency`),' ',b.`amount`,'/',b.`interval`,')') as plan FROM "._DB_PREFIX_."stripepro_subscription a LEFT JOIN "._DB_PREFIX_."stripepro_plans b  ON a.stripe_plan_id = b.stripe_plan_id WHERE a.id_customer = ".(int)Tools::getValue('id_customer'));
			 
		 $output = '
			<script type="text/javascript">
				$(document).ready(function() {
					var prependEl = $("#container-customer");
					$(\'';
		
		 $output .= '<div class="bootstrap"><div class="col-lg-12"><div class="panel panel-highlighted">';
		 $output .= '<fieldset'.(_PS_VERSION_ < 1.5 ? ' style="width: 400px;"' : '').'><legend><img src="'.$this->_path.'views/img/stripe-icon.gif" alt="">&nbsp;'.$this->l('Stripe Subscriptions').'</legend>';
		 
		 if (!empty($stripe_customer_id)){
			 
			 if(Tools::getIsset('SubmitAddSub') || Tools::getIsset('SubmitCancelSub') || Tools::getIsset('SubmitListPlans') || Tools::getIsset('SubmitCusSubSync') || Tools::getIsset('SubmitSubSync')){
			 $output .= (empty($this->_errors['stripe_subscription_error']) ? '<div class="conf confirmation alert alert-success">'.$this->l('Your request was successfully processed').'</div>' : '').
				(!empty($this->_errors['stripe_subscription_error']) ? '<div style="color: #CC0000; font-weight: bold;" class="alert alert-danger">'.$this->l('Error:').' '.Tools::safeOutput($this->_errors['stripe_subscription_error']).'</div>' : '');}
				
			$output .= '<form action="" method="post" style="float:left;">';
			
			$stripe_plans = Db::getInstance()->ExecuteS("SELECT stripe_plan_id,CONCAT(`name`,' (',UCASE(`currency`),' ',`amount`,'/',`interval`,')') as name FROM "._DB_PREFIX_."stripepro_plans");
			 if(!empty($stripe_plans))
			 { $output .= '<select name="id_stripe_plan" style="width:250px; float:left;"><option value="">Select a Stripe Plan...</option>';
			 foreach($stripe_plans as $plan)
			 $output .= '<option value="'.$plan['stripe_plan_id'].'">'.$plan['name'].'</option>';
			 $output .= '</select>';
					}else
					$output .= '<div style="float:left;">'.$this->l('Please enter Plan ID:').'&nbsp;&nbsp;</div><input type="text" name="id_stripe_plan" style="width:200px;float:left">';
			 $output .= '&nbsp;<input type="submit" class="button btn btn-default" onclick="return confirm(\\\''.addslashes($this->l('Do you want to proceed to add subscription?')).'\\\');" name="SubmitAddSub" value="'.$this->l('Add Subscription').'" />';
			  
			 $output .= '</form><form action="" method="post" style="border-left:2px solid #cdcdcd;margin-left:25px;float:left; padding-left:20px">&nbsp;<input type="submit" class="button btn btn-default" onclick="return confirm(\\\''.addslashes($this->l('Do you want to proceed to update the Stripe Plans list?')).'\\\');" name="SubmitListPlans" value="'.$this->l('Sync Plans from stripe').'" /> &nbsp;-&nbsp;<input type="submit" class="button btn btn-default" onclick="return confirm(\\\''.addslashes($this->l('Do you want to proceed to Sync subscription?')).'\\\');" name="SubmitCusSubSync" value="'.$this->l('Sync Subscriptions for this customer').'" />&nbsp;-&nbsp;<input type="submit" class="button btn btn-default" onclick="return confirm(\\\''.addslashes($this->l('Do you want to proceed to Sync subscriptions for all stripe customers?')).'\\\');" name="SubmitSubSync" value="'.$this->l('Sync Subscriptions for all stripe customers').'" /></form><br /><hr style="clear:both;" />';
			 
			 foreach($stripe_subscription_details as $subscription)
			  {$output .= '<form action="" method="post" style="background:#fff;float:left; border:1px solid #cdcdcd; padding:5px 10px;"><input type="hidden" name="stripe_subscription_id" value="'.$subscription['stripe_subscription_id'].'"><table cellpadding="10" cellspacing="10"><tr><td>'.$this->l('Subscription ID').':</td><td>&nbsp;<b>'.$subscription['stripe_subscription_id'].'</td></tr><tr><td>'.$this->l('Plan ID').':</td><td style="color:brown;">&nbsp;'.($subscription['plan']==''?$subscription['stripe_plan_id']:$subscription['plan']).'</td></tr><tr><td>'.$this->l('Quantity').':</td><td>&nbsp;<b>'.$subscription['quantity'].'</td></tr><tr><td>'.$this->l('Period').':</td><td>&nbsp;<b>'.date('M d, Y',$subscription['current_period_start']).' '.$this->l('to').' '.date('M d, Y',$subscription['current_period_end']).'</td></tr><tr><td>'.$this->l('Started').':</td><td>&nbsp;<b>'.date('M d, Y',$subscription['current_period_start']).'</td></tr><tr><td>'.$this->l('Status').':</td><td style="color:'.($subscription['status']=='active'?'#71B238':'orange').'">&nbsp;<b>'.Tools::strtoupper($subscription['status']).'</b></td></tr></table><br><input type="submit" class="button btn btn-default pull-right" onclick="return confirm(\\\''.addslashes($this->l('Do you want to proceed to Cancel subscription?')).'\\\');" name="SubmitCancelSub" value="'.$this->l('Cancel Subscription').'" /></form>';
			  }
			 $output .= '</fieldset></div></div></div><div class="clear"></div><div class="separation"></div>\').prependTo(prependEl);
								  });
							  </script>';
				}else{
				$output .= '<div style="color: #CC0000; font-weight: bold;" class="alert alert-danger">'.$this->l('This customer do not have any Stripe account.').'</div></fieldset></div></div></div><div class="clear"></div><div class="separation"></div>\').prependTo(prependEl);});</script>';
	   }
				
			return $output;
	}
	
	return true;
	
}
	
	/**
	 * Add subscription to a plan	
	 *
	 * @param string $stripe_customer_id Stripe Customer ID
	 * @param string $stripe_plan_id Stripe Plan ID
	 */

    public function addStripeSubscription($stripe_customer_id, $stripe_plan_id)
	{
		
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;

		include_once(dirname(__FILE__).'/lib/Stripe.php');
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));

		/* Try to process the capture and catch any error message */
		try
		{
			$customer = \Stripe\Customer::retrieve($stripe_customer_id);
			$result_json = $customer->subscriptions->create(array("plan" => $stripe_plan_id));
			
		}
		catch (Exception $e)
		{

			$this->_errors['stripe_subscription_error'] = $e->getMessage();
			if (class_exists('Logger'))
				Logger::addLog($this->l('Stripe - subscription failed').' '.$e->getMessage(), 1, null, 'Customer', (int)Tools::getIsset('id_customer'), true);
		}
		
        if(!isset($this->_errors['stripe_subscription_error']))
		Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'stripepro_subscription (stripe_subscription_id, stripe_customer_id, id_customer, stripe_plan_id, quantity, current_period_start, current_period_end, status, date_add) VALUES (\''.$result_json->id.'\', \''.$stripe_customer_id.'\', '.(int)Tools::getValue('id_customer').',\''.$stripe_plan_id.'\', '.$result_json->quantity.', \''.$result_json->current_period_start.'\',
		\''.$result_json->current_period_end.'\', \''.$result_json->status.'\', NOW())');
		

		
		return true;
	}
	
	/**
	 * Add subscription to a plan	
	 *
	 * @param string $stripe_customer_id Stripe Customer ID
	 * @param string $stripe_plan_id Stripe Plan ID
	 */

    public function cancelSubscription($stripe_customer_id,$stripe_subscription_id)
	{
		
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;

		include_once(dirname(__FILE__).'/lib/Stripe.php');
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));

		/* Try to process the capture and catch any error message */
		try
		{
			$cu = \Stripe\Customer::retrieve($stripe_customer_id);
			$cu->subscriptions->retrieve($stripe_subscription_id)->cancel();
			
		}
		catch (Exception $e)
		{

			$this->_errors['stripe_subscription_error'] = $e->getMessage();
			if (class_exists('Logger'))
				Logger::addLog($this->l('Stripe - subscription cancelation failed').' '.$e->getMessage(), 1, null, 'Customer', (int)Tools::getIsset('id_customer'), true);
		}
		
        if(!isset($this->_errors['stripe_subscription_error']))
		{
			$customer = new Customer((int)Tools::getValue('id_customer'));
			
			$vars = array(
				'{name}' => $customer->firstname.' '.$customer->lastname,
			);
			
			if(Configuration::get('STRIPE_SUBS_CANCEL_MAIL'))
			Mail::Send(
				(int)$this->context->cookie->id_lang,
				'cancel_subscription',
				Mail::l('Subscription Canceled.'),
				$vars,
				$customer->email,
				$customer->firstname.' '.$customer->lastname,
				null,
				null,
				null,
				null,
				dirname(__FILE__).'/mails/');
		Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'stripepro_subscription where stripe_subscription_id=\''. pSQL($stripe_subscription_id).'\'');
		}
		

		
		return true;
	}
	
	/**
	 * Add subscription to a plan	
	 *
	 * @param string $stripe_customer_id Stripe Customer ID
	 * @param string $stripe_plan_id Stripe Plan ID
	 */

    public function listPlans()
	{
		
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;
			
	    include_once(dirname(__FILE__).'/lib/Stripe.php');
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));

		/* Try to process the capture and catch any error message */
		try
		{
			$result_json = \Stripe\Plan::all();
						
		}
		catch (Exception $e)
		{

			$this->_errors['stripe_subscription_error'] = $e->getMessage();
			if (class_exists('Logger'))
				Logger::addLog($this->l('Stripe - Plans list update failed').' '.$e->getMessage(), 1, null, 'Customer', (int)Tools::getIsset('id_customer'), true);
		}
		
		if(!isset($this->_errors['stripe_subscription_error'])){
			Db::getInstance()->Execute('TRUNCATE TABLE '._DB_PREFIX_.'stripepro_plans');
			for($i=0;$i<count($result_json->data); $i++){
			  Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'stripepro_plans (`stripe_plan_id`, `name`, `interval`, `amount`, `currency`, `interval_count`, `trial_period_days`) VALUES (\''.$result_json->data[$i]->id.'\', \''.$result_json->data[$i]->name.'\', \''.$result_json->data[$i]->interval.'\','.sprintf("%.2f", $result_json->data[$i]->amount / 100).', \''.$result_json->data[$i]->currency.'\', '.(int)$result_json->data[$i]->interval_count.','.(int)$result_json->data[$i]->trial_period_days.')');
			 }
			}
	
	return true;
	
	}
	
	/**
	 * Synchronize subscriptions for a customer	
	 *
	 * @param string $stripe_subscription_id Stripe Subscription ID
	 */

    public function syncSubscriptions($stripe_customer_id)
	{
		
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;
			
	    include_once(dirname(__FILE__).'/lib/Stripe.php');
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));
        
		/* Try to process the capture and catch any error message */
		try
		{
			
			$result_json = \Stripe\Customer::retrieve($stripe_customer_id);
						
		}
		catch (Exception $e)
		{

			$this->_errors['stripe_subscription_error'] = $e->getMessage();
			if (class_exists('Logger'))
				Logger::addLog($this->l('Stripe - Subscription update failed').' '.$e->getMessage(), 1, null, 'Customer', (int)Tools::getIsset('id_customer'), true);
		}
		
		//print_r($result_json);die;
		if(!isset($this->_errors['stripe_subscription_error'])){
            $subs = $result_json->subscriptions->data;
			
			Db::getInstance()->Execute('DELETE from '._DB_PREFIX_.'stripepro_subscription where `id_customer`='.(int)Tools::getValue('id_customer'));
			if(count($subs)>0)
			foreach($subs as $sub){
				Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'stripepro_subscription (stripe_subscription_id, stripe_customer_id, id_customer, stripe_plan_id, quantity, current_period_start, current_period_end, status, date_add) VALUES (\''.$sub->id.'\', \''.$stripe_customer_id.'\', '.(int)Tools::getValue('id_customer').',\''.$sub->plan->id.'\', '.(int)$sub->quantity.', \''.$sub->current_period_start.'\',
		\''.$sub->current_period_end.'\', \''.$sub->status.'\', NOW())');
		
			 }
			}
	
	return true;
	
	}
	
	/**
	 * Synchronize subscriptions for all customers
	 */

    public function syncAllSubscriptions()
	{
		
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;
			
	    @ini_set('max_execution_time', 1000);
		
	    include_once(dirname(__FILE__).'/lib/Stripe.php');
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));
        
		$stripe_customers = Db::getInstance()->ExecuteS('SELECT stripe_customer_id, id_customer FROM `'._DB_PREFIX_.'stripepro_customer` UNION SELECT stripe_customer_id,id_customer FROM `'._DB_PREFIX_.'stripepro_subscription` ');
		
		Db::getInstance()->Execute('TRUNCATE TABLE '._DB_PREFIX_.'stripepro_subscription');
		
		foreach($stripe_customers as $stripe_customer){

		/* Try to process the capture and catch any error message */
		try
		{
			$result_json = \Stripe\Customer::retrieve($stripe_customer['stripe_customer_id']);
						
		}
		catch (Exception $e)
		{

			$this->_errors['stripe_subscription_error'] = $e->getMessage();
			if (class_exists('Logger'))
				Logger::addLog($this->l('Stripe - Subscription update failed').' '.$e->getMessage(), 1, null, 'Customer', (int)Tools::getIsset('id_customer'), true);
		}
		
		if(!isset($this->_errors['stripe_subscription_error'])){
			
            $subs = $result_json->subscriptions->data;
			
			foreach($subs as $sub){
			  Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'stripepro_subscription (`stripe_subscription_id`,`stripe_customer_id`,`id_customer`,`stripe_plan_id`,`quantity`,`current_period_start`,`current_period_end`, `status`,`date_add`) VALUES (\''.$sub->id.'\', \''.$stripe_customer['stripe_customer_id'].'\', \''.$stripe_customer['id_customer'].'\','.$sub->plan->id.', \''.$sub->quantity.'\', '.$sub->current_period_start.','.$sub->current_period_end.',\''.$sub->status.'\',NOW())');
			 }
			}		

		}
	return true;
	
	}
	/**
	 * Process a partial or full capture
	 *
	 * @param string $id_transaction_stripe Stripe Transaction ID (token)
	 * @param float $amount Amount to capture
	 * @param array $original_transaction Original transaction details
	 */

    public function processCapture($id_transaction_stripe, $amount)
	{
		
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;

		include_once(dirname(__FILE__).'/lib/Stripe.php');
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));

		/* Try to process the capture and catch any error message */
		try
		{
			$charge = \Stripe\Charge::retrieve($id_transaction_stripe);
			$result_json = $charge->capture(array('amount' => $amount * 100));
			
		}
		catch (Exception $e)
		{

			$this->_errors['stripe_capture_error'] = $e->getMessage();
			if (class_exists('Logger'))
				Logger::addLog($this->l('Stripe - Capture transaction failed').' '.$e->getMessage(), 1, null, 'Cart', (int)$this->context->cart->id, true);
		}
		
		if(!isset($this->_errors['stripe_capture_error']) && $result_json->captured==true){
		    $query = 'UPDATE ' . _DB_PREFIX_ . 'stripepro_transaction SET `status` = \'paid\', `amount` = ' . $amount . ' WHERE `id_transaction` = \''. pSQL($id_transaction_stripe).'\'';
		    if(!Db::getInstance()->Execute($query))
			return false;
		   }
		
		return true;
	}
	
	public function processRefund($id_transaction_stripe, $amount, $original_transaction)
	{
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;

		include(dirname(__FILE__).'/lib/Stripe.php');
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));

		/* Try to process the refund and catch any error message */
		try
		{
			$charge = \Stripe\Charge::retrieve($id_transaction_stripe);
			if($original_transaction['source']=="card")
			$result_json = $charge->refund(array('amount' => $amount * 100));
			else
            $result_json = $charge->refunds->create(array('amount' => $amount * 100,"refund_address" => $original_transaction['btc_address']));
		}
		catch (Exception $e)
		{
			$this->_errors['stripe_refund_error'] = $e->getMessage();
			if (class_exists('Logger'))
				Logger::addLog($this->l('Stripe - Refund transaction failed').' '.$e->getMessage(), 2, null, 'Cart', (int)$this->context->cart->id, true);
		}
		
		if(!isset($this->_errors['stripe_refund_error']))
		Db::getInstance()->Execute('
		INSERT INTO '._DB_PREFIX_.'stripepro_transaction (type, source, id_stripe_customer, id_cart, id_order,
		id_transaction, amount, status, currency, cc_type, cc_exp, cc_last_digits, fee, mode, date_add)
		VALUES (\'refund\',\''.$original_transaction['source'].'\', '.(int)$original_transaction['id_stripe_customer'].', '.(int)$original_transaction['id_cart'].', '.
		(int)$original_transaction['id_order'].', \''.pSQL($id_transaction_stripe).'\',
		\''.(float)$amount.'\', \''.(!isset($this->_errors['stripe_refund_error']) ? 'paid' : 'unpaid').'\', \''.pSQL($result_json->currency).'\',
		\'\', \'\', 0, 0, \''.(Configuration::get('STRIPE_MODE') ? 'live' : 'test').'\', NOW())');
	}
	
	/**
	 * Display the Stripe's payment form
	 *
	 * @return string Stripe's Smarty template content
	 */
	public function hookPayment($params)
	{
		/* If 1.4 and no backward then leave */
		if (!$this->backward)
			return;

	
			/* Retrieve the most recent customer's credit card */
			$customer_credit_card = Db::getInstance()->getValue('SELECT cc_last_digits FROM '._DB_PREFIX_.'stripepro_customer WHERE id_customer = '.(int)$this->context->cookie->id_customer);
			if ($customer_credit_card)
				$this->smarty->assign('stripe_credit_card', (int)$customer_credit_card);
		
		/* If the address check has been enabled by the merchant, we will transmitt the billing address to Stripe */
		if (isset($this->context->cart->id_address_invoice))
		{
			$billing_address = new Address((int)$this->context->cart->id_address_invoice);
			if ($billing_address->id_state)
			{
				$state = new State((int)$billing_address->id_state);
				if (Validate::isLoadedObject($state))
					$billing_address->state = $state->iso_code;
			}
		}
		
		if (!empty($this->context->cookie->stripe_error)) {
			$this->smarty->assign('stripe_error', $this->context->cookie->stripe_error);
			$this->context->cookie->__set('stripe_error', null);
		}

		$this->smarty->assign('validation_url', (Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'index.php?process=validation&fc=module&module=stripepro&controller=default');


		$this->smarty->assign(array(
		 'stripe_cc' => $this->_path."views/img/stripe-cc.png",
		 'stripe_btc' => $this->_path."views/img/stripe-btc.png",
		 'stripe_ps_version' => _PS_VERSION_,
		 'stripe_allow_btc'  => Configuration::get('STRIPE_ALLOW_BTC'),
		 'stripe_pk' => (Configuration::get('STRIPE_MODE')?Configuration::get('STRIPE_PUBLIC_KEY_LIVE'):Configuration::get('STRIPE_PUBLIC_KEY_TEST')),
		 'stripe_save_tokens_ask' => Configuration::get('STRIPE_SAVE_TOKENS_ASK'),
		 'cu_email' => $this->context->customer->email,
		 'currency' => $this->context->currency->iso_code,
		 'shop_name' => Configuration::get('PS_SHOP_NAME'),
		 'cart_total' => $this->context->cart->getOrderTotal() * 100,
		 'logo_url' =>  (Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'img/'.Configuration::get('PS_LOGO')));
		
		return $this->display(__FILE__, './views/templates/hook/payment.tpl');

	}

	
	/**
	 * Display a confirmation message after an order has been placed
	 *
	 * @param array Hook parameters
	 */
	public function hookPaymentReturn($params)
	{
		if (!isset($params['objOrder']) || ($params['objOrder']->module != $this->name))
			return false;
		
		if ($params['objOrder'] && Validate::isLoadedObject($params['objOrder']) && isset($params['objOrder']->valid))

			$this->smarty->assign('stripe_order', array('reference' => isset($params['objOrder']->reference) ? $params['objOrder']->reference : '#'.sprintf('%06d', $params['objOrder']->id), 'valid' => $params['objOrder']->valid));

			// added this so we could present a better/meaningful message to the customer when the charge suceeds, but verifications have failed.
			$pendingOrderStatus = (int)Configuration::get('STRIPE_PENDING_ORDER_STATUS');
			$currentOrderStatus = (int)$params['objOrder']->getCurrentState();

			if ($pendingOrderStatus==$currentOrderStatus)
				$this->smarty->assign('order_pending', true);
			else
				$this->smarty->assign('order_pending', false);

		return $this->display(__FILE__, './views/templates/hook/order-confirmation.tpl');

	}

	/**
	 * Process a payment
	 *
	 * @param string $token Stripe Transaction ID (token)
	 */
	public function processPayment($token)
	{
		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return;
			
		$payment_src = (Tools::substr($token,0,6)=='btcrcv'?'btc':'card');

		include(dirname(__FILE__).'/lib/Stripe.php');
		\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));

		/* Case 1: Charge an existing customer (or create it and charge it) */
		/* Case 2: Just process the transaction, do not save Stripe customer's details */

			/* Get or Create a Stripe Customer */
			$stripe_customer = Db::getInstance()->getRow('
			SELECT id_stripe_customer, stripe_customer_id, token
			FROM '._DB_PREFIX_.'stripepro_customer
			WHERE id_customer = '.(int)$this->context->cookie->id_customer);

			if (!isset($stripe_customer['id_stripe_customer']))
			{
				try
				{
					$stripe_customer_exists = false;
					$customer_stripe = \Stripe\Customer::create(array(
					'description' => $this->l('PrestaShop Customer ID:').' '.(int)$this->context->cookie->id_customer,
					'source' => $token));
					$stripe_customer['stripe_customer_id'] = $customer_stripe->id;
				}
				catch (Exception $e)
				{
					/* If the Credit card is invalid */
					$this->_errors['invalid_customer_card'] = true;
					if (class_exists('Logger'))
						Logger::addLog($this->l('Stripe - Invalid Credit Card'), 1, null, 'Cart', (int)$this->context->cart->id, true);
				}
			}
			else
			{
				$stripe_customer_exists = true;

				/* Update the credit card in the database */
				if ($token && $token != $stripe_customer['token'] && $payment_src=='card')
				{
					try
					{
						$cu = \Stripe\Customer::retrieve($stripe_customer['stripe_customer_id']);
						$cu->source = $token;
						$cu->save();
					}
					catch (Exception $e)
					{
						/* If the new Credit card is invalid, do not replace the old one - no warning or error message required */
						$this->_errors['invalid_customer_card'] = true;
						if (class_exists('Logger'))
							Logger::addLog($this->l('Stripe - Invalid Credit Card (replacing an old card)'), 1, null, 'Cart', (int)$this->context->cart->id, true);
					}
					
					if (!isset($this->_errors['invalid_customer_card']))
					Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'stripepro_customer SET token = \''.$token.'\' WHERE `id_stripe_customer` = '.$stripe_customer['id_stripe_customer']);
				}
			}
		
		try
		{
			
			$charge_details = array('amount' => $this->context->cart->getOrderTotal() * 100, 'currency' => $this->context->currency->iso_code, 'description' => $this->l('PrestaShop Customer ID:').
			' '.(int)$this->context->cookie->id_customer.' - '.$this->l('PrestaShop Cart ID:').' '.(int)$this->context->cart->id, 'capture' => Configuration::get('STRIPE_CAPTURE_TYPE'),"expand" =>array("balance_transaction"));

			/* If we have a Stripe's customer ID for this buyer, charge the customer instead of the card */
			if (isset($stripe_customer['stripe_customer_id']) && !isset($this->_errors['invalid_customer_card']))
				$charge_details['customer'] = $stripe_customer['stripe_customer_id'];
			else
				$charge_details['source'] = $token;
			
			if($payment_src=='btc')
			$charge_details['source'] = $token;

			$result_json = \Stripe\Charge::create($charge_details);

			/* Save the Customer ID in PrestaShop to re-use it later */
			if (isset($stripe_customer_exists) && !$stripe_customer_exists)
				Db::getInstance()->Execute('
				INSERT INTO '._DB_PREFIX_.'stripepro_customer (id_stripe_customer, stripe_customer_id, token, id_customer, cc_last_digits, date_add)
				VALUES (NULL, \''.pSQL($stripe_customer['stripe_customer_id']).'\', \''.pSQL($token).'\', '.(int)$this->context->cookie->id_customer.', '.(int)Tools::substr(Tools::getValue('StripLastDigits'), 0, 4).', NOW())');

		// catch the stripe error the correct way.
		} catch (Exception $e) {
			$message = $e->getMessage();
			if (class_exists('Logger'))
				Logger::addLog($this->l('Stripe - Payment transaction failed').' '.$message, 1, null, 'Cart', (int)$this->context->cart->id, true);

			/* If it's not a critical error, display the payment form again */
			if ($e->getCode() != 'card_declined')
			{
				$this->context->cookie->__set("stripe_error",$e->getMessage());
				$controller = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc.php' : 'order.php';
				Tools::redirect($this->context->link->getPageLink($controller).(strpos($controller, '?') !== false ? '&' : '?').'step=3#stripe_error');
				exit;
			}
		}

		/* Log Transaction details */
		if (!isset($message) || $result_json->status!="failed")
		{
			if (!isset($result_json->application_fee))
				$result_json->application_fee = 0;

			$order_status = (int)Configuration::get('STRIPE_PAYMENT_ORDER_STATUS');
			$message = $this->l('Stripe Transaction Details:')."\n\n".
			$this->l('Stripe Transaction ID:').' '.$result_json->id."\n".
			$this->l('Amount:').' '.($result_json->amount * 0.01)."\n".
			$this->l('Status:').' '.($result_json->paid == 'true' ? $this->l('Paid') : $this->l('Unpaid'))."\n".
			$this->l('Processed on:').' '.strftime('%Y-%m-%d %H:%M:%S', $result_json->created)."\n".
			$this->l('Currency:').' '. Tools::strtoupper($result_json->currency)."\n";
			if($result_json->source->object=="card")
			$message .= $this->l('Credit card:').' '.$result_json->source->brand.' ('.$this->l('Exp.:').' '.$result_json->source->exp_month.'/'.$result_json->source->exp_year.')'."\n".$this->l('Last 4 digits:').' '.sprintf('%04d', $result_json->source->last4).' ('.$this->l('CVC Check:').' '.($result_json->source->cvc_check == 'pass' ? $this->l('OK') : $this->l('NOT OK')).')'."\n";
			else
			 $message .= $this->l('Address:').' '.$result_json->source->inbound_address."\n".
				  $this->l('Bitcoin:').' B⃦'.$result_json->source->bitcoin_amount*.00000001." BTC \n".
				  $this->l('Filled:').' '.($result_json->source->filled?'Yes':'No')."\n";
			
			$message .= $this->l('Processing Fee:').' '.($result_json->balance_transaction->fee * 0.01)."\n".
			$this->l('Mode:').' '.($result_json->livemode == 'true' ? $this->l('Live') : $this->l('Test'))."\n";

			/* In case of successful payment, the address / zip-code can however fail */
			if (isset($result_json->source->address_line1_check) && $result_json->source->address_line1_check == 'fail')
			{
				$message .= "\n".$this->l('Warning: Address line 1 check failed');
				$order_status = (int)Configuration::get('STRIPE_PENDING_ORDER_STATUS');
			}
			if (isset($result_json->source->address_zip_check) && $result_json->source->address_zip_check == 'fail')
			{
				$message .= "\n".$this->l('Warning: Address zip-code check failed');
				$order_status = (int)Configuration::get('STRIPE_PENDING_ORDER_STATUS');
			}
			// warn if cvc check fails
			if (isset($result_json->source->cvc_check) && $result_json->source->cvc_check == 'fail')
			{
				$message .= "\n".$this->l('Warning: CVC verification check failed');
				$order_status = (int)Configuration::get('STRIPE_PENDING_ORDER_STATUS');
			}
		}
		else
			$order_status = (int)Configuration::get('PS_OS_ERROR');

		/* Create the PrestaShop order in database */
		$this->validateOrder((int)$this->context->cart->id, (int)$order_status, ($result_json->amount * 0.01), $this->displayName, $message, array(), null, false, $this->context->customer->secure_key);

		/** @since 1.5.0 Attach the Stripe Transaction ID to this Order */
		if (version_compare(_PS_VERSION_, '1.5', '>='))
		{
			$new_order = new Order((int)$this->currentOrder);
			if (Validate::isLoadedObject($new_order))
			{
				$payment = $new_order->getOrderPaymentCollection();
				if (isset($payment[0]))
				{
					$payment[0]->transaction_id = pSQL($result_json->id);
					$payment[0]->save();
				}
			}
		}

		/* Store the transaction details */
		if (isset($result_json->id))
			Db::getInstance()->Execute('
			INSERT INTO '._DB_PREFIX_.'stripepro_transaction (type,source,btc_address, id_stripe_customer, id_cart, id_order,
			id_transaction, amount, status, currency, cc_type, cc_exp, cc_last_digits, cvc_check, fee, mode, date_add)
			VALUES (\'payment\',"'.$result_json->source->object.'","'.($payment_src=='btc'?$result_json->source->inbound_address:'').'", '.(isset($stripe_customer['id_stripe_customer']) ? (int)$stripe_customer['id_stripe_customer'] : 0).', '.(int)$this->context->cart->id.', '.(int)$this->currentOrder.', \''.pSQL($result_json->id).'\',
			\''.($result_json->amount * 0.01).'\', \''.($result_json->paid == 'true' ? ($result_json->captured ? 'paid' : 'uncaptured'): 'unpaid').'\', \''.pSQL($result_json->currency).'\',
			\''.pSQL($result_json->source->brand).'\', \''.(int)$result_json->source->exp_month.'/'.(int)$result_json->source->exp_year.'\', '.(int)$result_json->source->last4.',
			'.($result_json->source->object == 'card'?($result_json->source->cvc_check == 'pass' ? 1 : 0):($result_json->source->filled == true ? 1 : 0)).', \''.($result_json->balance_transaction->fee * 0.01).'\', \''.($result_json->livemode == 'true' ? 'live' : 'test').'\', NOW())');

		/* Redirect the user to the order confirmation page / history */
		if (_PS_VERSION_ < 1.5)
			$redirect = __PS_BASE_URI__.'order-confirmation.php?id_cart='.(int)$this->context->cart->id.'&id_module='.(int)$this->id.'&id_order='.(int)$this->currentOrder.'&key='.$this->context->customer->secure_key;
		else
			$redirect = __PS_BASE_URI__.'index.php?controller=order-confirmation&id_cart='.(int)$this->context->cart->id.'&id_module='.(int)$this->id.'&id_order='.(int)$this->currentOrder.'&key='.$this->context->customer->secure_key;

		Tools::redirect($redirect);
		exit;
	}

	/**
	 * Delete a Customer's Credit Card
	 *
	 * @return integer Credit Card deletion result (1 = worked, 0 = did not worked)
	 */
	public function deleteCreditCard()
	{
		if (isset($this->context->cookie->id_customer) && $this->context->cookie->id_customer)
			return (int)Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'stripepro_customer WHERE id_customer = '.(int)$this->context->cookie->id_customer);

		return 0;
	}

	/**
	 * Check settings requirements to make sure the Stripe's module will work properly
	 *
	 * @return boolean Check result
	 */
	public function checkSettings()
	{
		if (Configuration::get('STRIPE_MODE'))
			return Configuration::get('STRIPE_PUBLIC_KEY_LIVE') != '' && Configuration::get('STRIPE_PRIVATE_KEY_LIVE') != '';
		else
			return Configuration::get('STRIPE_PUBLIC_KEY_TEST') != '' && Configuration::get('STRIPE_PRIVATE_KEY_TEST') != '';
	}

	/**
	 * Check technical requirements to make sure the Stripe's module will work properly
	 *
	 * @return array Requirements tests results
	 */
	public function checkRequirements()
	{
		$tests = array('result' => true);
		$tests['curl'] = array('name' => $this->l('PHP cURL extension must be enabled on your server'), 'result' => extension_loaded('curl'));
		$tests['mbstring'] = array('name' => $this->l('PHP Multibyte String extension must be enabled on your server'), 'result' => extension_loaded('mbstring'));
		if (Configuration::get('STRIPE_MODE'))
			$tests['ssl'] = array('name' => $this->l('SSL must be enabled on your store (before entering Live mode)'), 'result' => Configuration::get('PS_SSL_ENABLED') || (!empty($_SERVER['HTTPS']) && Tools::strtolower($_SERVER['HTTPS']) != 'off'));
		$tests['php52'] = array('name' => $this->l('Your server must run PHP 5.3.3 or greater'), 'result' => version_compare(PHP_VERSION, '5.3.3', '>='));
		$tests['configuration'] = array('name' => $this->l('You must sign-up for Stripe and configure your account settings in the module (publishable key, secret key...etc.)'), 'result' => $this->checkSettings());

		if (_PS_VERSION_ < 1.5)
		{
			$tests['backward'] = array('name' => $this->l('You are using the backward compatibility module'), 'result' => $this->backward, 'resolution' => $this->backward_error);
			$tmp = Module::getInstanceByName('mobile_theme');
			if ($tmp && isset($tmp->version) && !version_compare($tmp->version, '0.3.8', '>='))
				$tests['mobile_version'] = array('name' => $this->l('You are currently using the default mobile template, the minimum version required is v0.3.8').' (v'.$tmp->version.' '.$this->l('detected').' - <a target="_blank" href="http://addons.prestashop.com/en/mobile-iphone/6165-prestashop-mobile-template.html">'.$this->l('Please Upgrade').'</a>)', 'result' => version_compare($tmp->version, '0.3.8', '>='));
		}

		foreach ($tests as $k => $test)
			if ($k != 'result' && !$test['result'])
				$tests['result'] = false;

		return $tests;
	}

	/**
	 * Display the Back-office interface of the Stripe's module
	 *
	 * @return string HTML/JS Content
	 */
	public function getContent()
	{
		 
		$output = '';
		if (version_compare(_PS_VERSION_, '1.5', '>'))
			$this->context->controller->addJQueryPlugin('fancybox');
		else
			$output .= '
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/jquery.fancybox-1.3.4.js"></script>
		  	<link type="text/css" rel="stylesheet" href="'.__PS_BASE_URI__.'css/jquery.fancybox-1.3.4.css" />';
	
		$errors = array();
		/* Update Configuration Values when settings are updated */
		if (Tools::isSubmit('SubmitStripe'))
		{	
			if (strpos(Tools::getValue('stripe_public_key_test'), "sk") !== false || strpos(Tools::getValue('stripe_public_key_live'), "sk") !== false ) {
				$errors[] = "You've entered your private key in the public key field!";
			}
			if (empty($errors)) {
				$configuration_values = array(
					'STRIPE_MODE' => Tools::getValue('stripe_mode'), 
					'STRIPE_CAPTURE_TYPE' => Tools::getValue('STRIPE_CAPTURE_TYPE'),
					'STRIPE_ALLOW_BTC' =>Tools::getValue('STRIPE_ALLOW_BTC'),
					'STRIPE_SAVE_TOKENS_ASK' =>Tools::getValue('stripe_save_tokens_ask'), 
					'STRIPE_PUBLIC_KEY_TEST' => trim(Tools::getValue('stripe_public_key_test')),
					'STRIPE_PUBLIC_KEY_LIVE' => trim(Tools::getValue('stripe_public_key_live')), 
					'STRIPE_PRIVATE_KEY_TEST' => trim(Tools::getValue('stripe_private_key_test')),
					'STRIPE_PRIVATE_KEY_LIVE' => trim(Tools::getValue('stripe_private_key_live')), 
					'STRIPE_PENDING_ORDER_STATUS' => (int)Tools::getValue('stripe_pending_status'),
					'STRIPE_PAYMENT_ORDER_STATUS' => (int)Tools::getValue('stripe_payment_status'), 
					'STRIPE_CHARGEBACKS_ORDER_STATUS' => (int)Tools::getValue('stripe_chargebacks_status'),
					'STRIPE_SUBS_CANCEL_MAIL' => (int)Tools::getValue('STRIPE_SUBS_CANCEL_MAIL')
				);

				foreach ($configuration_values as $configuration_key => $configuration_value)
					Configuration::updateValue($configuration_key, $configuration_value);
			}
		}
		
		$requirements = $this->checkRequirements();

		$output .= '
		<script type="text/javascript">
			/* Fancybox */
			$(\'a.stripe-module-video-btn\').live(\'click\', function(){
			    $.fancybox({\'type\' : \'iframe\', \'href\' : this.href.replace(new RegExp(\'watch\\?v=\', \'i\'), \'embed/\') + \'?rel=0&autoplay=1\',
			    \'swf\': {\'allowfullscreen\':\'true\', \'wmode\':\'transparent\'}, \'overlayShow\' : true, \'centerOnScroll\' : true,
			    \'speedIn\' : 100, \'speedOut\' : 50, \'width\' : 853, \'height\' : 480 });
			    return false;
			});
		</script>
		<link href="'.$this->_path.'views/css/stripe-prestashop-admin.css" rel="stylesheet" type="text/css" media="all" />
		<div class="stripe-module-wrapper bootstrap">
			'.(Tools::isSubmit('SubmitStripe') || Tools::isSubmit('SubmitListPlans') || Tools::isSubmit('SubmitSubSync') ? '<div class="conf confirmation alert alert-success">'.$this->l('Settings successfully saved').'<img src="http://www.prestashop.com/modules/'.$this->name.'.png?api_user='.urlencode($_SERVER['HTTP_HOST']).'" style="display: none;" /></div>' : '').'
			<div class="stripe-module-header">
				<a href="https://stripe.com/signup" rel="external"><img src="'.$this->_path.'views/img//stripe-logo.gif" alt="stripe" class="stripe-logo" /></a>
				<span class="stripe-module-intro">'.$this->l('Stripe makes it easy to start accepting credit/debit cards & Bitcoin on the web today.').'</span>
				<a href="https://stripe.com/signup" rel="external" class="stripe-module-create-btn"><span>'.$this->l('Create an Account').'</span></a>
			</div>
			<div class="stripe-module-wrap">
				<div class="stripe-module-col1 floatRight">
					<div class="stripe-module-wrap-video">
						<h3>'.$this->l('Easy to setup').'</h3>
						<p>'.$this->l('Follow these simple steps to setup your module and start accepting payments with Stripe.').'</p>
						<a href="http://www.youtube.com/embed/A-cLaIHgSeA?hd=1" class="stripe-module-video-btn"><img src="'.$this->_path.'views/img/stripe-dashboard.png" alt="stripe dashboard" class="stripe-dashboard" /><img src="'.$this->_path.'views/img/stripe-btn-video.png" alt="" class="stripe-video-btn" /></a>
					</div>
				</div>
				<div class="stripe-module-col2">
					<div class="stripe-module-col1inner">
						<h3>'.$this->l('You\'ll love to use Stripe Pro').'</h3>
						<ul>
						    <li>'.$this->l('Interactive checkout experience').'</li>
							<li>'.$this->l('Accept Bitcoin payments').'</li>
							<li>'.$this->l('Manage/Sync subscriptions for recurring payments').'</li>
							<li>'.$this->l('Ability to store credit card aliases').'</li>
							<li>'.$this->l('Ability to handle chargebacks/disputes').'</li>
							<li>'.$this->l('Address & zip-code checked against fraud').'</li>
							<li>'.$this->l('Full transactions details (Back Office)').'</li>
							<li>'.$this->l('Ability to perform full or partial refunds').'</li>
						</ul>
					</div>
					<div class="stripe-module-col1inner floatRight">
						<h3>'.$this->l('Pricing like it should be').'</h3>
						<p><strong>'.$this->l('2.9% + 30 cents per successful charge.').'</strong></p>
						<p>'.$this->l('No setup fees, no monthly fees, no card storage fees, no hidden costs: you only get charged when you earn money.').'</p>
					</div>
					<div class="stripe-module-col2inner">
						<h3>'.$this->l('Accept payments worldwide using all major credit/debit cards & Bitcoin.').'</h3>
						<p><img src="'.$this->_path.'views/img/stripe-cc.png" alt="stripe" /><img src="'.$this->_path.'views/img/stripe-btc.png" alt="stripe" class="stripe-cc" /> <a href="https://stripe.com/signup" class="stripe-module-btn"><strong>'.$this->l('Create a FREE Account!').'</strong></a></p>
					</div>
				</div>
			</div>
			<fieldset>
				<legend><img src="'.$this->_path.'views/img/checks-icon.gif" alt="" />'.$this->l('Technical Checks').'</legend>
				<div class="'.($requirements['result'] ? 'conf confirm confirmation alert alert-success">'.$this->l('Good news! All the checks were successfully performed. You can now configure your module and start using Stripe.') :
				'warn alert error alert-danger">'.$this->l('Unfortunately, at least one issue is preventing you from using Stripe. Please fix the issue and reload this page.')).'</div>
				<table cellspacing="0" cellpadding="0" class="stripe-technical">';
				foreach ($requirements as $k => $requirement)
					if ($k != 'result')
						$output .= '
						<tr>
							<td><img src="../img/admin/'.($requirement['result'] ? 'ok' : 'forbbiden').'.gif" alt="" /></td>
							<td>'.$requirement['name'].(!$requirement['result'] && isset($requirement['resolution']) ? '<br />'.Tools::safeOutput($requirement['resolution'], true) : '').'</td>
						</tr>';
				$output .= '
				</table>
			</fieldset>
		<br />';

		if (!empty($errors)) {
			$output .= '
			<fieldset>
				<legend>Errors</legend>
				<table cellspacing="0" cellpadding="0" class="stripe-technical">
						<tbody>
						';
					foreach ($errors as $error) {
							$output .= '
						<tr>
							<td><img src="../img/admin/forbbiden.gif" alt=""></td>
							<td>'. $error .'</td>
						</tr>';
					}
				$output .= '		
				</tbody></table>
			</fieldset>';
		}



		/* If 1.4 and no backward, then leave */
		if (!$this->backward)
			return $output;
			
		$plans = Db::getInstance()->getValue("SELECT count(*) FROM "._DB_PREFIX_."stripepro_plans");
		$subs = Db::getInstance()->getValue("SELECT count(*) FROM "._DB_PREFIX_."stripepro_subscription");

		$statuses = OrderState::getOrderStates((int)$this->context->cookie->id_lang);
		$output .= '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset class="stripe-settings">
				<legend><img src="'.$this->_path.'views/img/technical-icon.gif" alt="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Transaction Mode').'</label>
				<input type="radio" name="stripe_mode" value="0"'.(!Configuration::get('STRIPE_MODE') ? ' checked="checked"' : '').' /> Test
				<input type="radio" name="stripe_mode" value="1"'.(Configuration::get('STRIPE_MODE') ? ' checked="checked"' : '').' /> Live
				<br /><br />
				<label>'.$this->l('Charge Mode:').'</label>
				<select name="STRIPE_CAPTURE_TYPE" style="width:150px">
				<option value="false"'.(Configuration::get('STRIPE_CAPTURE_TYPE')=='false' ? ' selected="selected"' : '').'>'.$this->l('Authorize').'</option>
				<option value="true"'.(Configuration::get('STRIPE_CAPTURE_TYPE')=='true' || Configuration::get('STRIPE_CAPTURE_TYPE') != 'false' ? ' selected="selected"' : '').'>'.$this->l('Authorize & Capture').'</option>
				</select><div style="margin-left:255px"><code style="color: #BC821B !important;">
				'.$this->l('Choose whether to authorize payments and manually capture them later, or to both authorize and capture (i.e. fully charge) payments when orders are placed. You can capture a payment that is only Authorized by using Stripe payment tab for the order.').'</code></div>
				
				<table cellspacing="0" cellpadding="0" class="stripe-settings">
				  <tr><td colspan="2">&nbsp;</td></tr>
				  <tr>
						<td align="right" valign="top" width="50%"><label>'.$this->l('Accept Bitcoin payments on checkout').'</label><br>To process live Bitcoin payments, you need to <br><a href="https://dashboard.stripe.com/account/bitcoin/enable" class="btc_link" target="_blank">enable the live Bitcoin API on your account</a></td>
						<td align="left" valign="top" class="td-right">
							<input type="radio" name="STRIPE_ALLOW_BTC" value="1"'.(Configuration::get('STRIPE_ALLOW_BTC') ? ' checked="checked"' : '').' /> Yes &nbsp;
							<input type="radio" name="STRIPE_ALLOW_BTC" value="0"'.(!Configuration::get('STRIPE_ALLOW_BTC') ? ' checked="checked"' : '').' /> No
							<br><code style="color: #BC821B !important;">'.$this->l('(Only USD is currently supported)').'</code>
							</td>
					</tr>
					<tr class="stripe_save_token_tr">
						<td align="right" valign="middle"><label>'.$this->l('Give customers the option to choose whether or not to store their credit card aliases').'</label></td>
						<td align="left" valign="middle" class="td-right">
							<input type="radio" name="stripe_save_tokens_ask" value="1"'.(Configuration::get('STRIPE_SAVE_TOKENS_ASK') ? ' checked="checked"' : '').' /> Yes &nbsp;
							<input type="radio" name="stripe_save_tokens_ask" value="0"'.(!Configuration::get('STRIPE_SAVE_TOKENS_ASK') ? ' checked="checked"' : '').' /> No</td>
					</tr>
					<tr>
						<td align="right" valign="middle" width="50%"><label>'.$this->l('E-mail to customer if subscription canceled').'</label></td>
						<td align="left" valign="middle" class="td-right">
							<input type="radio" name="STRIPE_SUBS_CANCEL_MAIL" value="1"'.(Configuration::get('STRIPE_SUBS_CANCEL_MAIL') ? ' checked="checked"' : '').' /> Yes &nbsp;
							<input type="radio" name="STRIPE_SUBS_CANCEL_MAIL" value="0"'.(!Configuration::get('STRIPE_SUBS_CANCEL_MAIL') ? ' checked="checked"' : '').' /> No</td>
					</tr>
					<tr>
						<td align="center" valign="middle" colspan="2">
							<table cellspacing="0" cellpadding="0" class="innerTable">
								<tr>
									<td align="right" valign="middle">'.$this->l('Test Publishable Key').'</td>
									<td align="left" valign="middle"><input type="text" name="stripe_public_key_test" value="'.Tools::safeOutput(Configuration::get('STRIPE_PUBLIC_KEY_TEST')).'" /></td>
									<td width="15"></td>
									<td width="15" class="vertBorder"></td>
									<td align="left" valign="middle">'.$this->l('Live Publishable Key').'</td>
									<td align="left" valign="middle"><input type="text" name="stripe_public_key_live" value="'.Tools::safeOutput(Configuration::get('STRIPE_PUBLIC_KEY_LIVE')).'" /></td>
								</tr>
								<tr>
									<td align="right" valign="middle">'.$this->l('Test Secret Key').'</td>
									<td align="left" valign="middle"><input type="password" name="stripe_private_key_test" value="'.Tools::safeOutput(Configuration::get('STRIPE_PRIVATE_KEY_TEST')).'" /></td>
									<td width="15"></td>
									<td width="15" class="vertBorder"></td>
									<td align="left" valign="middle">'.$this->l('Live Secret Key').'</td>
									<td align="left" valign="middle"><input type="password" name="stripe_private_key_live" value="'.Tools::safeOutput(Configuration::get('STRIPE_PRIVATE_KEY_LIVE')).'" /></td>
								</tr>
							</table>
						</td>
					</tr>';

					$statuses_options = array(array('name' => 'stripe_payment_status', 'label' => $this->l('Order status in case of sucessfull payment:'), 'current_value' => Configuration::get('STRIPE_PAYMENT_ORDER_STATUS')),
					array('name' => 'stripe_pending_status', 'label' => $this->l('Order status in case of unsucessfull address/zip-code check:'), 'current_value' => Configuration::get('STRIPE_PENDING_ORDER_STATUS')),
					array('name' => 'stripe_chargebacks_status', 'label' => $this->l('Order status in case of a chargeback (dispute):'), 'current_value' => Configuration::get('STRIPE_CHARGEBACKS_ORDER_STATUS')));
					foreach ($statuses_options as $status_options)
					{
						$output .= '
						<tr>
							<td align="right" valign="middle"><label>'.$status_options['label'].'</label></td>
							<td align="left" valign="middle" class="td-right">
								<select name="'.$status_options['name'].'">';
									foreach ($statuses as $status)
										$output .= '<option value="'.(int)$status['id_order_state'].'"'.($status['id_order_state'] == $status_options['current_value'] ? ' selected="selected"' : '').'>'.Tools::safeOutput($status['name']).'</option>';
						$output .= '
								</select>
							</td>
						</tr>';
					}

					$output .= '
					<tr>
						<td colspan="2" class="td-noborder save"><input type="submit" class="button" name="SubmitStripe" value="'.$this->l('Save Settings').'" /></td>
					</tr>
				</table>
			</fieldset>
			</form>
			<form method="post" action="">
			<fieldset>
				<legend><img src="'.$this->_path.'views/img/refresh-icon.png" width="20px" alt="" />'.$this->l('Syncronize Stripe Data').'</legend>
				<table cellspacing="0" cellpadding="0" width="100%">
				  <tbody>
					<tr><td align="center"><input type="submit" class="button" onclick="return confirm(\''.addslashes($this->l('Do you want to proceed to update the Stripe Plans list?')).'\');" name="SubmitListPlans" value="'.$this->l('Sync Plans from stripe').' - ('.$plans.')" /></form></td></tr>
					<tr><td align="center"><b>'.$this->l('OR').'</b></td></tr>
					<tr><td align="center"><input type="submit" class="button" onclick="return confirm(\''.addslashes($this->l('Do you want to proceed to update the Subscriptions of all stripe customers?')).'\');" name="SubmitSubSync" value="'.$this->l('Sync Subscriptions of all stripe customers').' - ('.$subs.')" /></td></tr>
				  </tbody>
				</table>
			</fieldset>
			</form>
			<br />
			<form method="post" action="">
			<fieldset class="stripe-cc-numbers">
				<legend><img src="'.$this->_path.'views/img/cc-icon.gif" alt="" />'.$this->l('Test Credit Card Numbers').'</legend>
				<table cellspacing="0" cellpadding="0" class="stripe-cc-numbers" width="100%">
				  <thead>
					<tr>
					  <th>'.$this->l('Number').'</th>
					  <th>'.$this->l('Card type').'</th>
					</tr>
				  </thead>
				  <tbody>
					<tr><td class="number"><code>4242424242424242</code></td><td>Visa</td></tr>
					<tr><td class="number"><code>5555555555554444</code></td><td>MasterCard</td></tr>
					<tr><td class="number"><code>378282246310005</code></td><td>American Express</td></tr>
					<tr><td class="number"><code>6011111111111117</code></td><td>Discover</td></tr>
					<tr><td class="number"><code>30569309025904</code></td><td>Diner\'s Club</td></tr>
					<tr><td class="number last"><code>3530111333300000</code></td><td class="last">JCB</td></tr>
				  </tbody>
				</table>
			</fieldset>
			<div class="clear"></div>
			<br />
			<fieldset style="display:none;">
				<legend><img src="'.$this->_path.'views/img/checks-icon.gif" alt="" />'.$this->l('Webhooks').'</legend>
				'.$this->l('In order to receive chargeback information from Stripe, you must provide a Webhook link in Stripe\'s admin panel.').'<br />
				'.$this->l('To get started, please visit Stripe and setup the following Webhook:').'<br /><br />
			  <strong>'.(Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'index.php?process=webhook&fc=module&module=stripepro&controller=default&token='.Tools::safeOutput(Configuration::get('STRIPE_WEBHOOK_TOKEN')).'</strong>
			</fieldset>

		</div>
		</form>
		<script type="text/javascript">
			function updateStripeSettings()
			{
				if ($(\'input:radio[name=stripe_mode]:checked\').val() == 1)
					$(\'fieldset.stripe-cc-numbers\').hide();
				else
					$(\'fieldset.stripe-cc-numbers\').show(1000);

			}

			$(\'input:radio[name=stripe_mode]\').click(function() { updateStripeSettings(); });
			$(document).ready(function() { updateStripeSettings(); });
		</script>';

		return $output;
	}
}
