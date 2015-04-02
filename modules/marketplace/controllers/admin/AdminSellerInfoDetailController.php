<?php
	class AdminSellerInfoDetailController extends ModuleAdminController 
	{
		public function __construct() 
		{
			$this->bootstrap = true;
			$this->table       = 'marketplace_seller_info';
			$this->className   = 'SellerInfoDetail';
			$this->lang        = false;
		    $this->context     = Context::getContext();			
			
			$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_customer` mpc ON (mpc.`marketplace_seller_id` = a.`id`)';
			$this->_select = 'mpc.`is_seller`,mpc.`id_customer`';
			$hook_res = Hook::exec('displayAdminSellerInfoJoin', array('flase' => 1));
			if($hook_res) 
			{	
				$this->_join .=$hook_res;
				$hook_sel_res = Hook::exec('displayAdminSellerInfoSelect', array('flase' => 1));
				$this->_select .= $hook_sel_res;
			}
			
			$this->fields_list = array();
			$this->fields_list['id'] = array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'class' => 'fixed-width-xs'
			);
			
			$this->fields_list['id_customer'] = array(
				'title' => $this->l('Id customer'),
				'align' => 'center'
			);
			
			$this->fields_list['business_email'] = array(
				'title' => $this->l('Business email'),
				'align' => 'center'
			);
			
			$this->fields_list['seller_name'] = array(
				'title' => $this->l('Seller Name'),
				'align' => 'center'
			);
			
			$this->fields_list['shop_name'] = array(
				'title' => $this->l('Shop name'),
				'align' => 'center'
			);
			
			$this->fields_list['phone'] = array(
				'title' => $this->l('Phone'),
				'align' => 'center'
			);

			$this->fields_list['date_add'] = array(
				'title' => $this->l('Registration'),
				'type' => 'date',
				'align' => 'center'
			);
			
			if($hook_res) {	
				$this->fields_list['plan_name'] = array(
					'title' => $this->l('Plan Name'),
					'align' => 'center'
				);
			}
			
			$this->fields_list['is_seller'] =array(
					'title' => $this->l('Status'),
					'active' => 'status',
					'align' => 'center',
					'type' => 'bool',
					'orderby' => false
				);
			
			$this->identifier  = 'id';

			$this->bulk_actions = array(
									'delete' => array('text' => $this->l('Delete selected'),
													 'confirm' => $this->l('Delete selected items?')),
									'enableSelection' => array(
														'text' => $this->l('Enable selection'),
														'icon' => 'icon-power-off text-success'),
									'disableSelection' => array(
											'text' => $this->l('Disable selection'),
											'icon' => 'icon-power-off text-danger'),
									);
			parent::__construct();
			
		}

		public function renderList() 
		{
			$this->addRowAction('edit');
			$this->addRowAction('delete');
			$this->addRowAction('view');
			return parent::renderList();

		}

		

		public function initToolbar() 
		{
			parent::initToolbar();
			$this->page_header_toolbar_btn['new'] = array(
				'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
				'desc' => $this->l('Add new seller')
			);
		}	
		
		

		public function postProcess() 
		{

			if (!$this->loadObject(true))
				return;
				
			$this->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
			$this->addJS(_PS_JS_DIR_ . 'tinymce.inc.js');
			
			if (Tools::isSubmit('statusmarketplace_seller_info')) 
			{
				$id = Tools::getValue('id');
				$this->make_seller_patner();
			} 
			else if($this->tabAccess['delete'] === '1' && Tools::isSubmit('deletemarketplace_seller_info')) {

				$id = (int)Tools::getValue('id');

				$this->delete_seller_info($id);

			} 
			
			if($this->display == 'view') 
			{
	
				$this->context       = Context::getContext();
				$id                  = Tools::getValue('id');

				$req_border_color        = Configuration::get('req-border-color');
				$req_heading_font_family = Configuration::get('req-heading-font-family');
				$req_heading_color       = Configuration::get('req-heading-color');
				$req_heading_size        = Configuration::get('req-heading-size');
				$req_text_font_family    = Configuration::get('req-text-font-family');
				$req_text_color          = Configuration::get('req-text-color');
				$req_text_size           = Configuration::get('req-text-size');
				
				$this->context->smarty->assign("req_border_color", $req_border_color);
				$this->context->smarty->assign("req_heading_font_family", $req_heading_font_family);
				$this->context->smarty->assign("req_heading_color", $req_heading_color);
				$this->context->smarty->assign("req_heading_size", $req_heading_size);
				$this->context->smarty->assign("req_text_font_family", $req_text_font_family);
				$this->context->smarty->assign("req_text_color", $req_text_color);
				$this->context->smarty->assign("req_text_size", $req_text_size);
				
				$market_place_seller_id = Tools::getValue('id');
				$obj_mp_cus = new MarketplaceCustomer();
				$id_customer = $obj_mp_cus->getCustomerId($market_place_seller_id);
				$obj_cus_payment_detail = new MarketplaceCustomerPaymentDetail();
				$payment_detail = $obj_cus_payment_detail->getPaymentDetailByCustomerId($id_customer);
				if($payment_detail) {
					$this->context->smarty->assign('payment_detail',$payment_detail);
				} else {
					$this->context->smarty->assign('payment_detail',0);
				}
				$selle_info = new SellerInfoDetail();
				$market_place_seller_info = $selle_info->sellerDetail($market_place_seller_id);
				$this->context->smarty->assign('market_place_seller_info',$market_place_seller_info);
				$this->context->smarty->assign('set','0');
				$this->context->smarty->assign('market_place_seller_id',$market_place_seller_info['id']);
				$this->context->smarty->assign('shop_name',$market_place_seller_info['shop_name']);
				$this->context->smarty->assign('business_email',$market_place_seller_info['business_email']);
				$this->context->smarty->assign('phone',$market_place_seller_info['phone']);
				$this->context->smarty->assign('seller_name',$market_place_seller_info['seller_name']);
				$this->context->smarty->assign('fax',$market_place_seller_info['fax']);
				$this->context->smarty->assign('address',$market_place_seller_info['address']);
				$this->context->smarty->assign('about_shop',$market_place_seller_info['about_shop']);
				$this->context->smarty->assign('facebook_id',$market_place_seller_info['facebook_id']);
				$this->context->smarty->assign('twitter_id',$market_place_seller_info['twitter_id']);
				
			}
			parent::postProcess();

		}

		public function renderForm() 
		{
			
			$req_border_color        = Configuration::get('req-border-color');
			$req_heading_font_family = Configuration::get('req-heading-font-family');
			$req_heading_color       = Configuration::get('req-heading-color');
			$req_heading_size        = Configuration::get('req-heading-size');
			$req_text_font_family    = Configuration::get('req-text-font-family');
			$req_text_color          = Configuration::get('req-text-color');
			$req_text_size           = Configuration::get('req-text-size');
			
			$this->context->smarty->assign("req_border_color", $req_border_color);
			$this->context->smarty->assign("req_heading_font_family", $req_heading_font_family);
			$this->context->smarty->assign("req_heading_color", $req_heading_color);
			$this->context->smarty->assign("req_heading_size", $req_heading_size);
			$this->context->smarty->assign("req_text_font_family", $req_text_font_family);
			$this->context->smarty->assign("req_text_color", $req_text_color);
			$this->context->smarty->assign("req_text_size", $req_text_size);
				
			//tinymce setup
			$this->context->smarty->assign('path_css',_THEME_CSS_DIR_);
			$this->context->smarty->assign('ad',__PS_BASE_URI__.basename(_PS_ADMIN_DIR_));//__PS_BASE_URI__.basename(_PS_ADMIN_DIR_)
			$this->context->smarty->assign('autoload_rte',true);
            $this->context->smarty->assign('lang',true);	
				
			if($this->display == 'add')	
			{
				$customer_info =Db::getInstance()->executeS("SELECT cus.`id_customer`,cus.`email` FROM `"._DB_PREFIX_."customer` cus LEFT OUTER JOIN `"._DB_PREFIX_."marketplace_customer` mcus ON ( cus.id_customer = mcus.id_customer ) WHERE mcus.id_customer IS NULL");
				$this->context->smarty->assign('set','1');
				$this->context->smarty->assign('customer_info',$customer_info);
				$this->context->smarty->assign('phone_digit', Configuration::get('MP_PHONE_DIGIT'));

				$this->tpl_form_vars = array('add' => 1);
				$this->fields_form = array(
					'submit' => array(
					'title' => $this->l('    Save   '),
					'class' => 'button'
					)
				);
			} 
			else if($this->display == 'edit') 
			{
				
				$market_place_seller_id = Tools::getValue('id');
				$selle_info = new SellerInfoDetail();
				$market_place_seller_info = $selle_info->sellerDetail($market_place_seller_id);
				
				$this->context->smarty->assign('set','0');
				$this->context->smarty->assign('market_place_seller_id',$market_place_seller_info['id']);
				$this->context->smarty->assign('shop_name',$market_place_seller_info['shop_name']);
				$this->context->smarty->assign('business_email',$market_place_seller_info['business_email']);
				$this->context->smarty->assign('phone',$market_place_seller_info['phone']);
				$this->context->smarty->assign('seller_name',$market_place_seller_info['seller_name']);
				$this->context->smarty->assign('fax',$market_place_seller_info['fax']);
				$this->context->smarty->assign('address',$market_place_seller_info['address']);
				$this->context->smarty->assign('about_shop',$market_place_seller_info['about_shop']);
				$this->context->smarty->assign('facebook_id',$market_place_seller_info['facebook_id']);
				$this->context->smarty->assign('twitter_id',$market_place_seller_info['twitter_id']);
				$this->context->smarty->assign('phone_digit',Configuration::get('MP_PHONE_DIGIT'));
				
				//For default image 
					
					$shopimage = $market_place_seller_info['id']."-".$market_place_seller_info['shop_name'].".jpg";
					$dirshop = '../modules/marketplace/img/shop_img/'.$shopimage;
					$sellerimage = $market_place_seller_info['id'].".jpg";
					$dirseller = '../modules/marketplace/img/seller_img/'.$sellerimage;
		
					if(file_exists($dirshop))
						$shopimagepath = _MODULE_DIR_. 'marketplace/img/shop_img/'.$shopimage;
					else
						$shopimagepath = _MODULE_DIR_. 'marketplace/img/shop_img/defaultshopimage.jpg';
					
					
					if(file_exists($dirseller))
						$sellerimagepath = _MODULE_DIR_. 'marketplace/img/seller_img/'.$sellerimage;
					else
						$sellerimagepath = _MODULE_DIR_. 'marketplace/img/seller_img/defaultimage.jpg';	
						
						
					$this->context->smarty->assign('shopimagepath',$shopimagepath);	
					$this->context->smarty->assign('sellerimagepath',$sellerimagepath);	
					//------
				
				$this->tpl_form_vars = array(
										'add' => 0
											);
				$this->fields_form = array(
					'legend' => array(
						'title' =>	$this->l('Edit Shop'),
						),
					
					'submit' => array(
						'title' => $this->l('   Save   '),
						'class' => 'button'
					)
				);
			}
			return parent::renderForm();
		}
		
		
		public function processSave() 
		{
			//set==1 for add new
			//set == 0 for edit existing shop
			
			$is_proceess = Tools::getValue('set');
			$shop_name = Tools::getValue('shop_name');
			$about_business = Tools::getValue('about_business');
			$person_name = Tools::getValue('person_name');
			$phone = Tools::getValue('phone');
			$fax = Tools::getValue('fax');
			$fb_id = Tools::getValue('fb_id');
			$tw_id = Tools::getValue('tw_id');
			$address = Tools::getValue('address');
			$business_email_id = Tools::getValue('business_email_id');

			if (!Validate::isEmail($business_email_id))
				$this->errors[] = Tools::displayError('Invalid email ID.');
			
			if(trim($shop_name) == '')
				$this->errors[] = Tools::displayError('Shop name is requried field.');
				
			if(trim($person_name) == '')
				$this->errors[] = Tools::displayError('Seller name is requried field.');
			
			if(trim($phone) == '')
				$this->errors[] = Tools::displayError('Phone is requried field and must be numeric.');
			else
			{
				if(!is_numeric($phone))
					$this->errors[] = Tools::displayError('Phone must be numeric.');
			}
			
			if($is_proceess==1) 
			{
				if (empty($this->errors)) 
				{	
					$customer_id = Tools::getValue('shop_customer');
					$obj_seller_info = new SellerInfoDetail();
					$obj_seller_info->business_email = $business_email_id;
					$obj_seller_info->seller_name = $person_name;
					$obj_seller_info->shop_name = $shop_name;
					$obj_seller_info->phone = $phone;
					$obj_seller_info->fax = $fax;
					$obj_seller_info->address = $address;
					$obj_seller_info->address = $address;
					$obj_seller_info->about_shop = $about_business;
					$obj_seller_info->facebook_id = $fb_id;
					$obj_seller_info->twitter_id = $tw_id;
					
					$marketplace_seller_id = $obj_seller_info->save();
					$approve_type = Configuration::getGlobalValue('SELLER_APPROVE');
					$obj_marketplace_cus = new MarketplaceCustomer();
					if($approve_type == 'admin'){
						$is_mpcustomer_insert = $obj_marketplace_cus->insertMarketplaceCustomer($marketplace_seller_id,$customer_id);
					}else{
						// creating seller shop when admin setting is default
						$is_mpcustomer_insert = $obj_marketplace_cus->insertActiveMarketplaceCustomer($marketplace_seller_id,$customer_id);
							if($is_mpcustomer_insert){
								$obj_seller_info->make_seller_patner($marketplace_seller_id);
							}
					}
					
					if($_FILES['upload_logo']) {
						 if ($_FILES['upload_logo']['error'] > 0) {
							
						 } else {
							$validExtensions = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
							$image_name            = $shop_name. ".jpg";
							
							$fileExtension   = strrchr($_FILES['upload_logo']['name'], ".");
							if (in_array($fileExtension, $validExtensions)) {
								ImageManager::resize($_FILES['upload_logo']['tmp_name'], '../modules/marketplace/img/shop_img/'.$marketplace_seller_id.'-'. $image_name, 100,100);
							}
						}
					}
					if($_FILES['upload_seller_logo']) 
					{
						
						if (!$_FILES['upload_seller_logo']['error'] > 0) 
						{
							$validExtensions = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
							$image_name   = $marketplace_seller_id.".jpg";
							$fileExtension   = strrchr($_FILES['upload_seller_logo']['name'], ".");
							
							if (in_array($fileExtension, $validExtensions)) 
							{
								$dir = '../modules/marketplace/img/seller_img/';
								ImageManager::resize($_FILES['upload_seller_logo']['tmp_name'], $dir.$image_name, 200,200);
								
								
							}
						}
						
					}
					Hook::exec('actionAddshopExtrafield', array('marketplace_seller_id' => $marketplace_seller_id));
					
				} 
				else {
					$this->display = 'add';
				}
			} //edit process
			else 
			{
				if (empty($this->errors)) 
				{
					$seller_id = Tools::getValue('market_place_seller_id');
					$id_shop = Tools::getValue('id');
					$obj_seller_info = new SellerInfoDetail($seller_id);
					$obj_seller_info->business_email = $business_email_id;
					$obj_seller_info->seller_name = $person_name;
					$obj_seller_info->shop_name = $shop_name;
					$obj_seller_info->phone = $phone;
					$obj_seller_info->fax = $fax;
					$obj_seller_info->address = $address;
					$obj_seller_info->address = $address;
					$obj_seller_info->about_shop = $about_business;
					$obj_seller_info->facebook_id = $fb_id;
					$obj_seller_info->twitter_id = $tw_id;
					
					$marketplace_seller_id = $obj_seller_info->save();

					//Update marketplace shop
					$shop_rewrite = Tools::link_rewrite($shop_name);
					$obj_mpcustomer = new MarketplaceCustomer();
					$customer_id = $obj_mpcustomer->getCustomerId($seller_id);
					
					//update marketplace shop table
					$obj_mp_shop = new MarketplaceShop($id_shop);
					$obj_mp_shop->shop_name = $shop_name;
					$obj_mp_shop->link_rewrite = $shop_rewrite;
					$obj_mp_shop->id_customer = $customer_id;
					$obj_mp_shop->about_us = $about_business;
					$obj_mp_shop->save();
					
					if($_FILES['upload_logo']) {
						
						if ($_FILES['upload_logo']['error'] > 0) {
							$pre_shop_name = Tools::getValue('pre_shop_name');
							if($pre_shop_name!=$shop_name) {
								$new_image_name = $seller_id.'-'.$shop_name.".jpg";
								$old_image_name = $seller_id.'-'.$pre_shop_name.".jpg";
								$dir = '../modules/marketplace/img/shop_img/';
								rename($dir.$old_image_name,$dir.$new_image_name);
							}
						} else {
							$validExtensions = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
							$image_name   = $seller_id.'-'.$shop_name.".jpg";
							
							$fileExtension   = strrchr($_FILES['upload_logo']['name'], ".");
							if (in_array($fileExtension, $validExtensions)) {
								$dir = '../modules/marketplace/img/shop_img/';
								$pre_shop_name = Tools::getValue('pre_shop_name');
								
								$old_image_name = $seller_id.'-'.$pre_shop_name.".jpg";
								if(file_exists($dir.$old_image_name))
								unlink($dir.$old_image_name);
								ImageManager::resize($_FILES['upload_logo']['tmp_name'], $dir.$image_name, 100,100);
								
								
							}
						}
					} 
						if($_FILES['upload_seller_logo']) 
					{
						
						if (!$_FILES['upload_seller_logo']['error'] > 0) 
						{
							$validExtensions = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
							$image_name   = $seller_id.".jpg";
							$fileExtension   = strrchr($_FILES['upload_seller_logo']['name'], ".");
							
							if (in_array($fileExtension, $validExtensions)) 
							{
								$dir = '../modules/marketplace/img/seller_img/';
								ImageManager::resize($_FILES['upload_seller_logo']['tmp_name'], $dir.$image_name, 200,200);
							}
						}
						
					}
					Hook::exec('actionUpdateshopExtrafield', array('marketplace_seller_id' =>$seller_id));
					Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
				} else {
					$this->display = 'edit';	
				}
			}
			
		}

		public function confirm_seller_patner($id,$currentindex,$token)
		{
			echo '<script type="text/javascript">
				var con = confirm("Are You Sure");
				var id = '.$id.';
				var token ="'.$token.'";
				var currentindex = "'.$currentindex.'";
				if(con == false)
				{
				alert("You Cancelled");
				}
				else if(con == true )
				{
				var url=currentindex+"&vab=Arraymarketplace_seller_info1&token="+token+"&id="+id;
				window.location.href = url;
				}
				</script>';
		}
				

		public function make_seller_patner($id=false,$come_from=false) 
		{
			if(!$id)
				$id = Tools::getValue('id');
			$market_place_seller_active = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_customer` where `marketplace_seller_id`=".$id);
			$obj_mp_shop = new MarketplaceShop();
			if($market_place_seller_active) 
			{

				$is_seller = $market_place_seller_active['is_seller'];
				//market place customer id is orginal cutomer id from custmer table
				$market_place_cutomer_id = $market_place_seller_active['id_customer'];
				if($is_seller==0) 
				{

					$is_update = Db::getInstance()->update('marketplace_customer', array('is_seller' =>1),'marketplace_seller_id='.$id);

					$market_place_seller_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_seller_info` where `id`=".$id);

					if($is_update) 
					{
						$shop_name = $market_place_seller_info['shop_name'];
						$shop_rewrite = Tools::link_rewrite($shop_name);

						$is_shop_created = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_shop` where `id_customer`=".$market_place_cutomer_id);

						if($is_shop_created) 
						{
							//enable shop
							$is_inserted_shop_name = Db::getInstance()->update('marketplace_shop', array('is_active' =>1),'id_customer='.$market_place_cutomer_id);

							//fetch product for seller
							$market_place_shop_id = $is_shop_created['id'];
							$is_inserted_shop_name = Db::getInstance()->update('marketplace_seller_product', array('active' =>1),'id_shop='.$market_place_shop_id);
							$total_product_detail = Db::getInstance()->executeS("select `id_product` from `"._DB_PREFIX_."marketplace_shop_product` where `id_shop`=$market_place_shop_id");

							if($total_product_detail) 
							{
								foreach($total_product_detail as $total_product_detail1) 
								{
									$is_product_present = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."product` where `id_product`=".$total_product_detail1['id_product']);
									if($is_product_present) 
									{
										$is_inserted_shop_name = Db::getInstance()->update('product', array('active' =>1),'id_product='.$total_product_detail1['id_product']);
									}

								}

							}
							$obj_seller_info = new SellerInfoDetail();
							$obj_seller_info->callMailFunction(Tools::getValue('id'),'Approve seller request',1);
							
							if(!$come_from) {
								$redirect = self::$currentIndex.'&conf=5&token='.$this->token;
								$this->redirect_after = $redirect;
							}

						}
						else 
						{
							$obj_mp_shop->shop_name = $shop_name;
							$obj_mp_shop->link_rewrite = $shop_rewrite;
							$obj_mp_shop->id_customer = $market_place_cutomer_id;
							$obj_mp_shop->about_us = $market_place_seller_info['about_shop'];
							$obj_mp_shop->is_active = 1;
							$obj_mp_shop->save();
							$is_inserted_shop_name = $obj_mp_shop->id;

							if($is_inserted_shop_name)	
							{
								Hook::exec('actionActiveSellerPlan', array('mp_id_seller' => Tools::getValue('id')));
								$obj_seller_info = new SellerInfoDetail();
								$obj_seller_info->callMailFunction(Tools::getValue('id'),'Approve seller request',1);
								if(!$come_from) {
									$redirect = self::$currentIndex.'&conf=5&token='.$this->token;
									$this->redirect_after = $redirect;
								}
							} 
							else 
							{
								$is_update = Db::getInstance()->update('marketplace_customer', array('is_seller' =>0),'marketplace_seller_id='.$id);
								Tools::displayError($this->l('Some error occurs'));
							}
						}
					}
					else
						Tools::displayError($this->l('Some error occurs'));
				} 
				else 
				{
					$is_update = Db::getInstance()->update('marketplace_customer', array('is_seller' =>0),'marketplace_seller_id='.$id);

					if($is_update) {
						$obj_seller_info = new SellerInfoDetail();
						$obj_seller_info->callMailFunction(Tools::getValue('id'),'Approve seller request',2);
						
						Db::getInstance()->update('marketplace_shop', array('is_active' =>0),'id_customer='.$market_place_cutomer_id);

						$is_shop_created = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_shop` where `id_customer`=".$market_place_cutomer_id);

						

						$market_place_shop_id = $is_shop_created['id'];

						Db::getInstance()->update('marketplace_seller_product', array('active' =>0),'id_shop='.$market_place_shop_id);

						

						$total_product_detail = Db::getInstance()->executeS("select `id_product` from `"._DB_PREFIX_."marketplace_shop_product` where `id_shop`=$market_place_shop_id");

						if($total_product_detail) {

							foreach($total_product_detail as $total_product_detail1) {

								$is_product_present = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."product` where `id_product`=".$total_product_detail1['id_product']);

								if($is_product_present) {

									$is_inserted_shop_name = Db::getInstance()->update('product', array('active' =>0),'id_product='.$total_product_detail1['id_product']);

								} 

							}
							if(!$come_from) {
								$redirect = self::$currentIndex.'&conf=5&token='.$this->token;

								$this->redirect_after = $redirect;
							}

						} else {

							//Tools::displayError($this->l('Some error occurs'));

							//$this->displayInformation($this->l('Disable sucessfully'));
							if(!$come_from) {
								$redirect = self::$currentIndex.'&conf=5&token='.$this->token;

								$this->redirect_after = $redirect;
							}

						}

						

					} else

						Tools::displayError($this->l('Some error occurs'));
				}

			} else {

				Tools::displayError($this->l('Some error occurs'));

			}

		}

		public function delete_seller_info($id) 
		{
			Db::getInstance()->delete('marketplace_seller_info','id='.$id);
			//find id_customer 
			$id_customer_value = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_customer` where `marketplace_seller_id`=".$id);
			//real customer id present in customer table
			$market_place_cutomer_id = $id_customer_value['id_customer'];

			//delete data form marketplace customer

			Db::getInstance()->delete('marketplace_customer','marketplace_seller_id='.$id);

			//find shop id for that seller

			$id_shop_value = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_shop` where `id_customer`=".$market_place_cutomer_id);

			if($id_shop_value) {

				$market_place_shop_id = $id_shop_value['id'];

				Db::getInstance()->delete('marketplace_shop','id_customer='.$market_place_cutomer_id);

			

				//find product id for that seller

				$total_product_detail = Db::getInstance()->executeS("select `id_product` from `"._DB_PREFIX_."marketplace_shop_product` where `id_shop`=$market_place_shop_id");

				if($total_product_detail) {

					//delete all entry from main table provided by prestashop

					foreach($total_product_detail as $total_product_detail1) {

						$is_product_present = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."product` where `id_product`=".$total_product_detail1['id_product']);

						if($is_product_present) {

							Db::getInstance()->delete('product','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_attachment','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_attribute','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_carrier','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_country_tax','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_download','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_group_reduction_cache','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_lang','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_sale','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_shop','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_supplier','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('product_tag','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('category_product','id_product='.$total_product_detail1['id_product']);

							Db::getInstance()->delete('stock_available','id_product='.$total_product_detail1['id_product']);

							$delete_row_from_image_lang = "DELETE FROM t2 USING `"._DB_PREFIX_."image`  t1 INNER JOIN `"._DB_PREFIX_."image_lang` t2 WHERE t1.`id_product`=".$total_product_detail1['id_product']." AND t1.`id_image`=t2.`id_image`";

							Db::getInstance()->Execute($delete_row_from_image_lang);	

						

							Db::getInstance()->delete('image','id_product='.$total_product_detail1['id_product']);

						} 

					}

				}

				//delete data from market place shop product

				Db::getInstance()->delete('marketplace_shop_product','id_shop='.$market_place_shop_id);

			

				//delete row from market place seller product

				$delete_row_from_marketplace_product_image = "DELETE FROM t2 USING `"._DB_PREFIX_."marketplace_seller_product`  t1 INNER JOIN `"._DB_PREFIX_."marketplace_product_image` t2 WHERE t1.`id_seller`=$id AND t1.`id`=t2.`seller_product_id`";

				Db::getInstance()->Execute($delete_row_from_marketplace_product_image);	

				Db::getInstance()->delete('marketplace_seller_product','id_shop='.$market_place_shop_id);

			}

			Tools::redirectAdmin(self::$currentIndex.'&conf=1&token='.$this->token);

			

		}

		public function deleteSelection($data) 
		{
			$return = 1;
			if (is_array($data) && (count($data))) 
			{
				//Deleting data
				foreach ($data as $id)
					$this->delete_seller_info((int)$id);
			}
			return $return;
		}


		protected function processBulkEnableSelection()
		{
			return $this->processBulkStatusSelection(1);
		}
		
		protected function processBulkDisableSelection()
		{
			return $this->processBulkStatusSelection(0);
		}

		protected function processBulkStatusSelection($status)
		{
			
			if($status==1) {
				if (is_array($this->boxes) && !empty($this->boxes))
				{
					foreach ($this->boxes as $id)
					{
						$market_place_seller_active = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_customer` where `marketplace_seller_id`=".$id);
						if($market_place_seller_active) {
							if($market_place_seller_active['is_seller']==0)
								$this->make_seller_patner($id,true);
						}
						else {
							$this->active_seller_product($id,true);
						}


					}
				}
			} else if($status==0){
				
				if (is_array($this->boxes) && !empty($this->boxes))
				{
					foreach ($this->boxes as $id)
					{
						$market_place_seller_active = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from`"._DB_PREFIX_."marketplace_customer` where `marketplace_seller_id`=".$id);
						if($market_place_seller_active) {
							if($market_place_seller_active['is_seller']==1)
								$this->make_seller_patner($id,true);
						}
					}
				}
			}
		}
	}

?>