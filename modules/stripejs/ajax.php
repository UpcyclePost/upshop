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

$context = Context::getContext();

$stripe = new StripeJs();
$customer = new Customer((int)$context->cookie->id_customer);
if (!Validate::isLoadedObject($customer))
	die('0');

if (!Tools::getIsset('token') || Tools::getValue('token') != $customer->secure_key)
	die('0');

/* Check that the module is active and that we have the token */
$stripe = new StripeJs();
if ($stripe->active && Tools::getIsset('action'))
{
	switch (Tools::getValue('action'))
	{
		case 'delete_card':
			echo (int)$stripe->deleteCreditCard();
			break;
	}
}