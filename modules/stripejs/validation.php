<?php
/**
*Copyright (c) 2014. All rights reserved - NTS
*You are NOT allowed to modify the software. 
*It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*/

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/stripejs.php');

if (!defined('_PS_VERSION_'))
	exit;


/* Check that the Stripe's module is active and that we have the token */
$stripe = new StripeJs();
$context = Context::getContext();

if ($stripe->active && Tools::getIsset('stripeToken')) {
 	$stripe->processPayment(Tools::getValue('stripeToken'));
}
else {
	$context->cookie->__set("stripe_error", 'There was a problem with your payment');
	$controller = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc.php' : 'order.php';
	$location = $context->link->getPageLink($controller).(strpos($controller, '?') !== false ? '&' : '?').'step=3#stripe_error';
	Tools::redirect($location);
}