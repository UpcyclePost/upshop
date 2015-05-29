<?php
class marketplaceRegistrationprocessModuleFrontController extends ModuleFrontController 
{
    public function initContent() 
    {
    	$link = new link();
    	$customer_id = $this->context->cookie->id_customer;
    	$id_lang = $this->context->language->id;
    	$result = $this->validateShopLogoSize($_FILES['upload_logo']);
		$shop_name = Tools::getValue('shop_name');
		$seller_name = Tools::getValue('person_name');
		$phone = Tools::getValue('phone');
		$business_email_id = Tools::getValue('business_email_id');
		$bank = Tools::getValue('bank');
		$routing = Tools::getValue('routing');
		$cc = Tools::getValue('cc');
		$fb_id = Tools::getValue('fb_id');
		$tw_id = Tools::getValue('tw_id');
		$fax = Tools::getValue('fax');
		$about_business = Tools::getValue('about_business');
		$address = Tools::getValue('address');
		$context = Context::getContext();
		$context->cookie->__set('c_mp_shop_name',$shop_name);
		$context->cookie->__set('c_mp_shop_desc',$about_business);
        $context->cookie->__set('c_mp_seller_name',$seller_name);
        $context->cookie->__set('c_mp_phone',$phone);
        $context->cookie->__set('c_mp_fax',$fax);
        $context->cookie->__set('c_mp_business_email',$business_email_id);
        $context->cookie->__set('c_mp_address',$address);
		$context->cookie->__set('c_mp_bank',$bank);
        $context->cookie->__set('c_mp_routing',$routing);
        $context->cookie->__set('c_mp_cc',$cc);
        $context->cookie->__set('c_mp_facebook',$fb_id);
        $context->cookie->__set('c_mp_twitter',$tw_id);
		if($result == 1)
		{	

			if(trim($shop_name) == '')
			{
				$url = $link->getModuleLink('marketplace','sellerrequest', array('mp_error' => 1));
				Tools::redirect($url);
			}
			elseif(trim($seller_name) == '')
			{
				$url = $link->getModuleLink('marketplace','sellerrequest', array('mp_error' => 2));
				Tools::redirect($url);
			}
			elseif(trim($phone) == '')
			{
				$url = $link->getModuleLink('marketplace','sellerrequest', array('mp_error' => 3));
				Tools::redirect($url);
			}
			elseif(trim($business_email_id) == '')
			{
				$url = $link->getModuleLink('marketplace','sellerrequest', array('mp_error' => 4));
				Tools::redirect($url);
			}
			elseif(!Validate::isEmail($business_email_id))
			{
				$url = $link->getModuleLink('marketplace','sellerrequest', array('mp_error' => 5));
				Tools::redirect($url);
			}elseif(trim($bank) == '')
			{
				$url = $link->getModuleLink('marketplace','sellerrequest', array('mp_error' => 6));
				Tools::redirect($url);
			}elseif(trim($routing) == '')
			{
				$url = $link->getModuleLink('marketplace','sellerrequest', array('mp_error' => 7));
				Tools::redirect($url);
			}
			
			/*************Create Stripe manage account*******************/
			include_once(_PS_MODULE_DIR_.'stripepro/lib/Stripe.php');
		    \Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));
			
			
			/* Try to process the manage account and catch any error message */
			try
			{
				$result_json = \Stripe\Account::create(array("managed" => true,"country" => "US","email" => $business_email_id,"business_name" => $shop_name,"debit_negative_balances" => true, "bank_account" => array("country"=>"US","currency"=>'USD',"account_number"=>$bank,"routing_number"=>$routing), "tos_acceptance" => array('date'=>time(),"ip"=>$_SERVER['REMOTE_ADDR'])));
				
			}
			catch (Exception $e)
			{
				$this->_errors['stripe_error'] = $e->getMessage();	
			}
			
			if(!isset($this->_errors['stripe_error'])){
				
			Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'stripepro_sellers (stripe_seller_id, id_customer, secret, publishable, status, date_add) VALUES (\''.$result_json->id.'\', '.$customer_id.', \''.$result_json->keys->secret.'\', \''.$result_json->keys->publishable.'\', \'unverified\', NOW())');
			
			  $account = \Stripe\Account::retrieve($result_json->id);
				$account->legal_entity->ssn_last_4  = Tools::getValue('ssn');
				$account->legal_entity->type = Tools::getValue('type');
				$account->legal_entity->first_name = Tools::getValue('fname');
				$account->legal_entity->last_name = Tools::getValue('lname');
				$account->legal_entity->dob = array("day"=>Tools::getValue('day'),"month"=>Tools::getValue('month'),"year"=>Tools::getValue('year'));
				$account->save();
				
			}else{
				
				$url = $link->getModuleLink('marketplace','sellerrequest', array('mp_error' => $this->_errors['stripe_error']));
				Tools::redirect($url);
				}
				
			Hook::exec('actionBeforeAddSeller');
			/**
			*Saving seller details
			**/
			$obj_seller_detail = new SellerInfoDetail();
			$obj_seller_detail->seller_name = $seller_name;
			$obj_seller_detail->shop_name = $shop_name;
			$obj_seller_detail->phone = $phone;
			$obj_seller_detail->fax = $fax;
			$obj_seller_detail->about_shop = $about_business;
			$obj_seller_detail->address = $address;
			$obj_seller_detail->facebook_id = $fb_id;
			$obj_seller_detail->twitter_id = $tw_id;
			$obj_seller_detail->business_email = $business_email_id;
			$obj_seller_detail->save();
			$mp_seller_id = $obj_seller_detail->id;
			
			//for checking
			$obj_mp_cust = new MarketplaceCustomer();
			$approve_type = Configuration::getGlobalValue('SELLER_APPROVE');
			if($approve_type == 'admin')
				$obj_mp_cust->insertMarketplaceCustomer($mp_seller_id, $customer_id);
			else
			{
				// creating seller shop when admin setting is default
				$is_mpcustomer_insert = $obj_mp_cust->insertActiveMarketplaceCustomer($mp_seller_id, $customer_id);
				if($is_mpcustomer_insert)
					$obj_seller_detail->make_seller_patner($mp_seller_id);
			}
			
	
			//Upload Shop Logo
			$uploadlogo = $this->uploadShopLogo($_FILES['upload_logo'], $shop_name, $mp_seller_id);
			if (!$uploadlogo)
				//$error = 'Error in uploading shop logo image.';
			
		
			//Mail to admin
			$this->mailToAdminWhenSellerRequest($seller_name, $shop_name, $business_email_id, $phone, $id_lang);
			
			Hook::exec('actionAddshopExtrafield', array('marketplace_seller_id' => $mp_seller_id));
			$redirect_link = $link->getModuleLink('marketplace','sellerrequest');
			Tools::redirect($redirect_link);
		}
		else 
		{
			$param = array('img_size_error' => 1);
			$redirect_link = $link->getModuleLink('marketplace','sellerrequest', $param);
			Tools::redirect($redirect_link);
		}
		$context->cookie->__unset('c_mp_shop_name');
		$context->cookie->__unset('c_mp_shop_desc');
        $context->cookie->__unset('c_mp_seller_name');
        $context->cookie->__unset('c_mp_phone');
		$context->cookie->__unset('c_mp_bank');
        $context->cookie->__unset('c_mp_routing');
        $context->cookie->__unset('c_mp_cc');
        $context->cookie->__unset('c_mp_fax');
        $context->cookie->__unset('c_mp_business_email');
        $context->cookie->__unset('c_mp_address');
        $context->cookie->__unset('c_mp_facebook');
        $context->cookie->__unset('c_mp_twitter');  	
    }

    public function validateShopLogoSize($upload_logo)
    {
    	if(!empty($upload_logo['name']))
		{
			list($width, $height) = getimagesize($upload_logo['tmp_name']);
			if($width == 0 || $height == 0)
				$flag = 1;
			else if($width < 200 || $height < 200)
				$flag = 0;
			else
				$flag = 1;
		}
		else
			$flag = 1;

		return $flag;
    }

    public function uploadShopLogo($upload_logo, $shop_name, $mp_seller_id)
    {
    	$image_name = $shop_name.'.jpg';
		//$filename = $upload_logo['tmp_name'];

		if ($upload_logo['error'] > 0)
			return false;
		
		$valid_extensions = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
		$file_extension   = strrchr($upload_logo['name'], ".");
		if (in_array($file_extension, $valid_extensions))
		{
			$newpath = _PS_MODULE_DIR_.'marketplace/img/shop_img/';
            $width = '200';
            $height = '200';
            ImageManager::resize($upload_logo['tmp_name'], $newpath.$mp_seller_id.'-'.$image_name, $width, $height);
		}
		
    }

    public function mailToAdminWhenSellerRequest($seller_name, $shop_name, $business_email_id, $phone, $id_lang)
    {
    	$obj_emp = new Employee(1);    //1 for superadmin
		if(Configuration::get('MP_SUPERADMIN_EMAIL'))
			$admin_email = Configuration::get('MP_SUPERADMIN_EMAIL');
		else
			$admin_email = $obj_emp->email;
		
		$seller_vars = array(
			'{seller_name}' => $seller_name,
			'{seller_shop}' => $shop_name,
			'{seller_email_id}' => $business_email_id,
			'{seller_phone}' => $phone
		);
		
		$template_path = _PS_MODULE_DIR_."/marketplace/mails/";
		Mail::Send(
			(int)$id_lang,
			'mail_for_seller_request',
			Mail::l('New seller request', (int)$id_lang),
			$seller_vars,
			$admin_email,
			null,
			null,
			null,
			null,
			null,
			$template_path,
			false,
			null,
			null
		);
    }
}
?>