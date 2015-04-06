<?php
class SellerInfoDetail extends ObjectModel
{
	public $id;
	public $business_email;
	public $seller_name;
	public $shop_name;
	public $phone;
	public $fax;
	public $address;
	public $about_shop;
	public $facebook_id;
	public $twitter_id;
	public $date_add;
	public $date_upd;
	
	public static $definition = array(
		'table' => 'marketplace_seller_info',
		'primary' => 'id',
		'fields' => array(
			'business_email' => array('type' => self::TYPE_STRING, 'validate' => 'isEmail'),
			'seller_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
			'shop_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
			'phone' => 		array('type' => self::TYPE_STRING,'required' => true),
			'fax' => 		array('type' => self::TYPE_STRING),
			'address' => array('type' => self::TYPE_STRING),
			'about_shop' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
			'facebook_id' => array('type' => self::TYPE_STRING),
			'twitter_id' => array('type' => self::TYPE_STRING),
			'date_add' => 	array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
			'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
		),
	);
	
	public function add($autodate = true, $null_values = false)
	{
		if (!parent::add($autodate, $null_values))
			return false;
		return Db::getInstance()->Insert_ID();
	}

	public function delete()
	{
		if (!SellerInfoDetail::deleteAllProductOfSellerBySellerId($this->id) || !parent::delete())
			return false;
		return true;
	}
	
	public function update($null_values = false)
	{
		Cache::clean('getContextualValue_'.$this->id.'_*');
		$success = parent::update($null_values);
		return $success;
	}
	
	public function insertSellerDetail($date_add,$bussiness_email,$shop_name,$seller_name,$phone,$address,$about_business,$fax,$fb_id,$tw_id) {
		$result  = Db::getInstance()->insert('marketplace_seller_info', array(
            'date_add' => pSQL($date_add),
            'business_email' => pSQL($bussiness_email),
            'shop_name' => pSQL($shop_name),
            'seller_name' => pSQL($seller_name),
            'phone' => (int)$phone,
            'address' => pSQL($address),
            'about_shop' => pSQL(trim($about_business)),
            'fax' => $fax,
            'facebook_id' => pSQL($fb_id),
            'twitter_id' => pSQL($tw_id)
        ));
		
		if($result) {
			 return Db::getInstance()->Insert_ID();
		} else {
			return false;
		}
	}

	public function sellerDetail($seller_id) {
		$seller_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `"._DB_PREFIX_."marketplace_seller_info` where `id`=".$seller_id);
		if(!empty($seller_info)) {
			return $seller_info;
		} else {
			return false;
		}
	}
	public function getMarketPlaceSellerIdByCustomerId($id_customer) {
			$isseller = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "marketplace_customer` where id_customer =".$id_customer);
			if(!empty($isseller)) {
				return $isseller;
			} else {
				return false;
			}
	}
	
	public function isShopNameExist($name){
		$name = addslashes($name);
		$name = Db::getInstance()->getRow("SELECT * FROM `" ._DB_PREFIX_ ."marketplace_seller_info` WHERE shop_name ='$name'");
		if(empty($name))
			return false;
		return true;	
	}
		
	public function getmarketPlaceSellerInfo($marketplace_sellerid) {
		$marketplace_sellerinfo = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `". _DB_PREFIX_."marketplace_seller_info` where id =". $marketplace_sellerid);
		
		if(!empty($marketplace_sellerinfo)) {
			return $marketplace_sellerinfo;
		} else {
			return false;
		}
	}
		public function findAllActiveSeller() {
			$seller_info = Db::getInstance()->executeS("select mpsi.* from `". _DB_PREFIX_."marketplace_seller_info` mpsi left join `". _DB_PREFIX_."marketplace_customer` mpc on (mpsi.`id`=mpc.`marketplace_seller_id`) where is_seller =1");
			if(empty($seller_info)) {
				return false;
			} else {
				return $seller_info;
			}
		}
		
		public function findAllActiveSellerInfoByLimit($start_point=0,$limit_point=7,$like=false,$all=false,$like_word='a') {
			if($like==false && $all==false) { 
				$seller_info = Db::getInstance()->executeS("select mpsi.*,mpc.`id_customer`,ms.`id` as mp_shop_id from `". _DB_PREFIX_."marketplace_seller_info` mpsi left join `". _DB_PREFIX_."marketplace_customer` mpc on (mpsi.`id`=mpc.`marketplace_seller_id`) left join `". _DB_PREFIX_."marketplace_shop` ms on (ms.`id_customer`=mpc.`id_customer`) where is_seller =1 limit ".$start_point.",".$limit_point);
			} else if($like==false && $all==true) {
				$seller_info = Db::getInstance()->executeS("select mpsi.*,mpc.`id_customer`,ms.`id` as mp_shop_id from `". _DB_PREFIX_."marketplace_seller_info` mpsi left join `". _DB_PREFIX_."marketplace_customer` mpc on (mpsi.`id`=mpc.`marketplace_seller_id`) left join `". _DB_PREFIX_."marketplace_shop` ms on (ms.`id_customer`=mpc.`id_customer`) where is_seller =1");
			} else if($like==true && $all==false) {
				//no limit
				$seller_info = Db::getInstance()->executeS("select mpsi.*,mpc.`id_customer`,ms.`id` as mp_shop_id from `". _DB_PREFIX_."marketplace_seller_info` mpsi left join `". _DB_PREFIX_."marketplace_customer` mpc on (mpsi.`id`=mpc.`marketplace_seller_id`) left join `". _DB_PREFIX_."marketplace_shop` ms on (ms.`id_customer`=mpc.`id_customer`) where is_seller =1 and LOWER( ms.`shop_name`) like '".$like_word."%'");
			} else if($like==true && $all==true) {
				$seller_info = Db::getInstance()->executeS("select mpsi.*,mpc.`id_customer`,ms.`id` as mp_shop_id from `". _DB_PREFIX_."marketplace_seller_info` mpsi left join `". _DB_PREFIX_."marketplace_customer` mpc on (mpsi.`id`=mpc.`marketplace_seller_id`) left join `". _DB_PREFIX_."marketplace_shop` ms on (ms.`id_customer`=mpc.`id_customer`) where is_seller =1 and LOWER( ms.`shop_name`) like '%".$like_word."%'");
			}
			if(empty($seller_info)) {
				return false;
			} else {
				return $seller_info;
			}
		}
		
		public function callMailFunction($mp_id_seller,$sub,$mail_for=false) 
		{	
			$id_lang = Context::getContext()->cookie->id_lang;
			$main_shop_id = Context::getContext()->shop->id;
			if($mail_for==1) {
				$mail_reason = 'Active';
			} else if($mail_for==2){
				$mail_reason = 'Deactive';
			} else if($mail_for==3) {
				$mail_reason = 'Delete';
			} else {
				$mail_reason = 'Active';
			}
			
			$obj_seller = new SellerInfoDetail($mp_id_seller);
			$obj_mp_customer = new MarketplaceCustomer();
			$mp_seller_name = $obj_seller->seller_name;
			$business_email = $obj_seller->business_email;
			$mp_shop_name = $obj_seller->shop_name;
			$phone = $obj_seller->phone;
			if($business_email=='') {
				$id_customer = $obj_mp_customer->getCustomerId($mp_id_seller);
				$obj_cus = new Customer($id_customer);
				$business_email = $obj_cus->email;
			}
			
			
			$obj_shop = new Shop($main_shop_id);
			$ps_shop_name = $obj_shop->name;
			if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
				$secure_connection = "https://";
			} else {
				$secure_connection = "http://";
			}
			$shop_url = $secure_connection.$obj_shop->domain.$obj_shop->physical_uri.$obj_shop->virtual_uri;
			
			$template='seller';
			$templateVars = array(
							'{seller_name}' => $mp_seller_name,
							'{mp_shop_name}' => $mp_shop_name,
							'{mail_reason}' => $mail_reason,
							'{business_email}' => $business_email,
							'{phone}' => $phone,
							'{ps_shop_name}' => $ps_shop_name,
							'{shop_url}' => $shop_url
						);
			
			$temp_path = _PS_MODULE_DIR_.'marketplace/mails/';
			Mail::Send($id_lang,$template,$sub,$templateVars,$business_email,$mp_seller_name,null,'Marketplace',null,null,$temp_path,false,null,null);

			return true;
	}

	public static function make_seller_patner($id)
	{
		Db::getInstance()->update('marketplace_customer', array('is_seller' =>1),'marketplace_seller_id='.$id);
		$market_place_customer = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_customer` where `marketplace_seller_id`=".$id);
		$market_place_cutomer_id = $market_place_customer['id_customer'];
		$market_place_seller_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_seller_info` where `id`=".$id);

		$shop_name = $market_place_seller_info['shop_name'];
		$shop_rewrite = Tools::link_rewrite($shop_name);

		$is_shop_created = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_shop` where `id_customer`=".$market_place_cutomer_id);

		if($is_shop_created)
		{
			//enable shop
			Db::getInstance()->update('marketplace_shop', array('is_active' =>1),'id_customer='.$market_place_cutomer_id);
			//fetch product for seller
			$market_place_shop_id = $is_shop_created['id'];
			Db::getInstance()->update('marketplace_seller_product', array('active' =>1),'id_shop='.$market_place_shop_id);
			

			$total_product_detail = Db::getInstance()->executeS("select `id_product` from `"._DB_PREFIX_."marketplace_shop_product` where `id_shop`=$market_place_shop_id");
			if($total_product_detail)
			{
				foreach($total_product_detail as $total_product_detail1)
				{
					$is_product_present = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."product` where `id_product`=".$total_product_detail1['id_product']);

					if($is_product_present)
						Db::getInstance()->update('product', array('active' =>1),'id_product='.$total_product_detail1['id_product']);
				}

			}
			$obj_seller_info = new SellerInfoDetail();
			$obj_seller_info->callMailFunction($id,'Approve seller request',1);
		}
		else
		{
			Db::getInstance()->insert('marketplace_shop', array('shop_name' =>pSQL($shop_name),
																'link_rewrite' => pSQL($shop_rewrite),
																'id_customer' =>(int)$market_place_cutomer_id,
																'about_us' =>pSQL(trim($market_place_seller_info['about_shop'])),
																'is_active'=>1,
															));
		}
	}

	/**
	 * Fetch the content of $template_name inside the folder marketplace/mails/current_iso_lang/ if found
	 *
	 * @param string  $template_name template name with extension
	 * @param integer $mail_type     Mail::TYPE_HTML or Mail::TYPE_TXT
	 * @param array   $var           list send to smarty
	 *
	 * @return string
	 */
	public function getMpEmailTemplateContent($template_name, $mail_type, $var)
	{
		$email_configuration = Configuration::get('PS_MAIL_TYPE');
		if ($email_configuration != $mail_type && $email_configuration != Mail::TYPE_BOTH)
			return '';

		$default_mail_template_path = _PS_MODULE_DIR_.'marketplace/mails/'.DIRECTORY_SEPARATOR.Context::getContext()->language->iso_code.DIRECTORY_SEPARATOR.$template_name;

		if (Tools::file_exists_cache($default_mail_template_path))
		{
			Context::getContext()->smarty->assign('list', $var);
			return Context::getContext()->smarty->fetch($default_mail_template_path);
		}

		return '';
	}
	public static function deleteAllProductOfSellerBySellerId($id)
	{
		Hook::exec('actionDeleteSellerProfile', array('marketplace_seller_id' => $id));
		Hook::exec('actionBulkDeleteProductBySellerId', array('mp_seller_id' => $id));
		Db::getInstance()->delete('marketplace_seller_info','id='.$id);
		//find id_customer 
		$id_customer_value = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_customer` where `marketplace_seller_id`=".$id);
		//real customer id present in customer table
		$market_place_cutomer_id = $id_customer_value['id_customer'];

		//delete data form marketplace customer

		Db::getInstance()->delete('marketplace_customer','marketplace_seller_id='.$id);

		//find shop id for that seller

		$id_shop_value = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_shop` where `id_customer`=".$market_place_cutomer_id);

		if($id_shop_value)
		{
			$market_place_shop_id = $id_shop_value['id'];
			Db::getInstance()->delete('marketplace_shop','id_customer='.$market_place_cutomer_id);
			//find product id for that seller
			$total_product_detail = Db::getInstance()->executeS("select `id_product` from `"._DB_PREFIX_."marketplace_shop_product` where `id_shop`=$market_place_shop_id");

			if($total_product_detail)
			{
				//delete all entry from main table provided by prestashop
				foreach($total_product_detail as $total_product_detail1)
				{
					$obj_product = new Product($total_product_detail1['id_product']);
					$is_deleted = $obj_product->delete();
				}
			}

			//delete data from market place shop product
			Db::getInstance()->delete('marketplace_shop_product','id_shop='.$market_place_shop_id);

			//delete row from market place seller product
			$delete_row_from_marketplace_product_image = "DELETE FROM t2 USING `"._DB_PREFIX_."marketplace_seller_product`  t1 INNER JOIN `"._DB_PREFIX_."marketplace_product_image` t2 WHERE t1.`id_seller`=$id AND t1.`id`=t2.`seller_product_id`";

			Db::getInstance()->Execute($delete_row_from_marketplace_product_image);	
			Db::getInstance()->delete('marketplace_seller_product','id_shop='.$market_place_shop_id);
		}
		return true;
	}
}