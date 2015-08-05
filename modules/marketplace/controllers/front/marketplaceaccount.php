<?php
include_once 'modules/marketplace/classes/MarketplaceClassInclude.php'; 
class marketplaceMarketplaceaccountModuleFrontController extends ModuleFrontController 
{
	public function initContent() 
	{
        parent::initContent();
        $link     = new link();
		if(Tools::getIsset('orderby')) {
			$orderby  = Tools::getValue('orderby');
        }
		else {
			$orderby  = 'quantity';
		}
		
		if(Tools::getIsset('orderway')) {
			$orderway  = Tools::getValue('orderway');
        }
		else {
			$orderway  = 'desc';
		}
		
		if ($orderby == 'name') {
            $orderby = 'product_name';
        } elseif ($orderby == '') {
            $orderby = 'quantity';
        }
        if ($orderway == '') {
            $orderway = 'desc';
        }
		$page_no = Tools::getValue('p');
		if(isset($page_no)){
			$page_no = $page_no;
		}else{
			$page_no = 1;
		}
		 $id_lang     = $this->context->cookie->id_lang;
		 
		$this->context->smarty->assign("phone_digit", Configuration::get('MP_PHONE_DIGIT'));
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) 
			$this->context->smarty->assign("browser", "ie");
		else
			$this->context->smarty->assign("browser", "notie");
		 
        $new_link = $link->getModuleLink('marketplace', 'sellerrequest');
        $this->context->smarty->assign("new_link", $new_link);
				 

		$this->context->smarty->assign("id_lang", $id_lang);		 
		$this->context->smarty->assign("title_bg_color", Configuration::get('MP_TITLE_COLOR'));		 
		$this->context->smarty->assign("title_text_color", Configuration::get('MP_TITLE_TEXT_COLOR'));		 
		
		//////////
        if (isset($this->context->cookie->id_customer)) 
        {
            //current customer id whose login at tha time
            $customer_id     = $this->context->cookie->id_customer;
            //check whether request has been sent or not..........
			$obj_mp_customer = new MarketplaceCustomer();
			$obj_mp_seller = new SellerInfoDetail();
			$obj_mp_shop = new MarketplaceShop();
			$obj_mp_seller_product = new SellerProductDetail();
			
            $already_request = $obj_mp_customer->findMarketPlaceCustomer($customer_id);
            if ($already_request) 
            {
                //is_seller set to -1 when customer not request for market place yet 
                //@is_seller = 0 customer send requset for market place but admin not approve yet
                //@is_seller =1 admin approve market place seller request 
                $is_seller = $already_request['is_seller'];
                if ($is_seller == 1) 
                {
                    $marketplace_seller_id   = $already_request['marketplace_seller_id'];
					$marketplace_seller_info = $obj_mp_seller->sellerDetail($marketplace_seller_id);
					$market_place_shop = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($customer_id);
                   
                    if ($market_place_shop) 
                    {
                        $id_shop   = $market_place_shop['id'];
                        $obj_ps_shop = new MarketplaceShop($id_shop);
						$name_shop = $obj_ps_shop->link_rewrite;
                        
						//shop link
						$param = array('shop'=>$id_shop);
						$payment_detail     = $link->getModuleLink('marketplace', 'customerPaymentDetail',$param);
                        $link_store        = $link->getModuleLink('marketplace', 'shopstore',array('shop'=>$id_shop,'shop_name'=>$name_shop));
                        $link_collection   = $link->getModuleLink('marketplace', 'shopcollection',array('shop'=>$id_shop,'shop_name'=>$name_shop));
                        $add_product       = $link->getModuleLink('marketplace', 'addproduct',$param);
                        $account_dashboard = $link->getModuleLink('marketplace', 'marketplaceaccount',$param);
                        $seller_profile    = $link->getModuleLink('marketplace', 'sellerprofile',$param);
						$edit_profile    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>2,'edit-profile'=>1));
						$product_list    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>3));
						$my_order    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>4));
						$payment_details    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'id_cus'=>$customer_id,'l'=>5));
						$this->context->smarty->assign("account_dashboard", $account_dashboard);
                        $this->context->smarty->assign("link_store", $link_store);
                        $this->context->smarty->assign("seller_profile", $seller_profile);
                        $this->context->smarty->assign("link_collection", $link_collection);
                        $this->context->smarty->assign("add_product", $add_product);
                        $this->context->smarty->assign("is_seller", $is_seller);
                        $this->context->smarty->assign("edit_profile", $edit_profile);
                        $this->context->smarty->assign("product_list", $product_list);
                        $this->context->smarty->assign("my_order", $my_order);
                        $this->context->smarty->assign("payment_details", $payment_details);
						
                        
                        
                        if (Tools::getIsset('l'))
                        { 
                        	if(Tools::getValue('l') != '')
	                            $logic = Tools::getValue('l');
                        }
                        else
                            $logic = 1;
                        
						
						$this->context->smarty->assign("payment_detail", $payment_detail);
						$this->context->smarty->assign("customer_id", $customer_id);
						$this->context->smarty->assign("id_shop", $id_shop);
						$this->context->smarty->assign("logic", $logic);
						
                      
                        
                        $this->context->smarty->assign("seller_name", $marketplace_seller_info['seller_name']);
                       
                        if ($logic == 1) 
                        {
                            				
							$dashboard      = Db::getInstance()->executeS("SELECT ordd.`product_price` as total_price,ordd.`product_quantity` as qty, ordd.`id_order` as id_order,ord.`id_customer` as order_by_cus,ord.`payment` as payment_mode,cus.`firstname` as name,ord.`date_add`,ords.`name`as order_status,ord.`id_currency` as id_currency, ord.`reference` as `ref` from `" . _DB_PREFIX_ . "marketplace_shop_product` msp join `" . _DB_PREFIX_ . "order_detail` ordd on (ordd.`product_id`=msp.`id_product`) join `"._DB_PREFIX_."orders` ord on (ordd.`id_order`=ord.`id_order`) join `"._DB_PREFIX_."marketplace_seller_product` msep on (msep.`id` = msp.`marketplace_seller_id_product`) join `"._DB_PREFIX_."marketplace_customer` mkc on (mkc.`marketplace_seller_id` = msep.`id_seller`) join `" . _DB_PREFIX_ . "customer` cus on (mkc.`id_customer`=cus.`id_customer`) join `" . _DB_PREFIX_ . "order_state_lang` ords on (ord.`current_state`=ords.`id_order_state`) where ords.id_lang=".$id_lang." and cus.`id_customer`=" . $customer_id . "  order by ord.`current_state` asc, ord.`date_add` desc  limit 10");
							
                            
                            $count = count($dashboard);
							
                            $order_by_cus = array();

							foreach($dashboard as $dashboard1)
							{
								$order_by_cus[]= Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from `"._DB_PREFIX_."customer` where id_customer=".$dashboard1['order_by_cus']);
							}
							if (isset($order_by_cus))
							{
								if (is_array($order_by_cus))
								{
									$this->context->smarty->assign("order_by_cus", $order_by_cus);
								}
							}

							
							//// for statics - Asia/Kolkata - Time Zone Information - Daylight ...
							date_default_timezone_set('Asia/Calcutta');
							if(Tools::getIsset('from_date') && Tools::getIsset('to_date'))
							{
								if( Tools::getValue('from_date') == '' || Tools::getValue('to_date') == '' )
								{
									$time_stamp = time();
									$dat = getdate($time_stamp);
									$j=29;
								}
								else
								{
									   
										$end_date = Tools::getValue('to_date');
										$from_date = strtotime(Tools::getValue('from_date'));
										//echo $end_date;
										
										$todate = strtotime($end_date);
										if($todate<$from_date)
										  {
											 $error = "To date must be greater than From date";
											// echo $error;
											$this->context->smarty->assign("error", $error);
											 $time_stamp=time();
											 $dat = getdate($time_stamp);
											 $j=29;
										  }
										else
										  {
											
											// echo "<br />".$todate;
											$time_stamp=$todate;
											$dat = getdate($time_stamp);
											 $total_difffernce_btwn_date = ($todate-$from_date)/86400;
											 $j = (int)$total_difffernce_btwn_date;
																			 
										  }
									 }
							   }
							else
							   {
								  $time_stamp=time()+86400;
								  $dat = getdate($time_stamp);
								  $j=29;
							   }
							
							$newdate =array();
							$time_stamp_date = array();
							for($i=$j;$i>=0;$i--)
								{
									$time_stamp_date[$i] = $time_stamp-$i*86400;
									$dat = getdate($time_stamp-$i*86400);
									$newdate[$i] = $dat['year'].'-'.$dat['mon'].'-'.$dat['mday'];
								}
							$todate = $newdate[0];
							$from_date = $newdate[$j];
							$l= $j;
							
							$this->context->smarty->assign("newdate", $newdate);
							$product_price_detail = array();
							$count_order_detail = array();
							for($i=$l;$i>0;$i--) {
								$prev= $newdate[$i];
								$j = $i-1;
								$next = $newdate[$j];
								
							
								$total_price = Db::getInstance()->executeS("SELECT IFNULL(SUM(ordd.`product_price`),0) as total_price,ordd.`product_quantity` as qty, ordd.`id_order` as id_order,ord.`id_customer` as order_by_cus,ord.`payment` as payment_mode,cus.`firstname` as name,ord.`date_add`,ords.`name`as order_status from `" . _DB_PREFIX_ . "marketplace_shop_product` msp join `" . _DB_PREFIX_ . "order_detail` ordd on (ordd.`product_id`=msp.`id_product`) join `"._DB_PREFIX_."orders` ord on (ordd.`id_order`=ord.`id_order`) join `"._DB_PREFIX_."marketplace_seller_product` msep on (msep.`id` = msp.`marketplace_seller_id_product`) join `"._DB_PREFIX_."marketplace_customer` mkc on (mkc.`marketplace_seller_id` = msep.`id_seller`) join `" . _DB_PREFIX_ . "customer` cus on (mkc.`id_customer`=cus.`id_customer`) join `" . _DB_PREFIX_ . "order_state_lang` ords on (ord.`current_state`=ords.`id_order_state`) where ords.id_lang=".$id_lang." and cus.`id_customer`=" . $customer_id . " and ord.`date_add` between '".$prev."' and '".$next."'"); 
							
								$count_order = Db::getInstance()->executeS("SELECT IFNULL(count(ord.`id_order`),0) as total_order from `" . _DB_PREFIX_ . "marketplace_shop_product` msp join `" . _DB_PREFIX_ . "order_detail` ordd on (ordd.`product_id`=msp.`id_product`) join `"._DB_PREFIX_."orders` ord on (ordd.`id_order`=ord.`id_order`) join `"._DB_PREFIX_."marketplace_seller_product` msep on (msep.`id` = msp.`marketplace_seller_id_product`) join `"._DB_PREFIX_."marketplace_customer` mkc on (mkc.`marketplace_seller_id` = msep.`id_seller`) join `" . _DB_PREFIX_ . "customer` cus on (mkc.`id_customer`=cus.`id_customer`) join `" . _DB_PREFIX_ . "order_state_lang` ords on (ord.`current_state`=ords.`id_order_state`) where ords.id_lang=".$id_lang." and cus.`id_customer`=" . $customer_id . " and ord.`date_add` between '".$prev."' and '".$next."'"); 
								
								$product_price_detail[$i] = Tools::ps_round($total_price[0]['total_price'],2);
								$count_order_detail[$i] = $count_order[0]['total_order'];
							}
							
							
							
							$this->context->smarty->assign("product_price_detail", $product_price_detail);
							$this->context->smarty->assign("count_order_detail", $count_order_detail);
							
							$this->context->smarty->assign("loop_exe", $l);
							
							$this->context->smarty->assign("to_date", $todate);
							$this->context->smarty->assign("from_date", $from_date);
                            $this->context->smarty->assign("dashboard", $dashboard);
                            $this->context->smarty->assign("count", $count);
                            $this->setTemplate('marketplace_account1.tpl');
                        } 
						elseif ($logic == 2) 
						{
                            /*if (Tools::getIsset('edit-profile')) 
							{*/
							
							/*************Get Stripe manage account details*******************/
							include_once(_PS_MODULE_DIR_.'stripepro/lib/Stripe.php');
							\Stripe\Stripe::setApiKey(Configuration::get('STRIPE_MODE') ? Configuration::get('STRIPE_PRIVATE_KEY_LIVE') : Configuration::get('STRIPE_PRIVATE_KEY_TEST'));
							$account_id = Db::getInstance()->getValue('select `stripe_seller_id` from '._DB_PREFIX_.'stripepro_sellers where `id_customer` = '.$customer_id, false);
						    if(trim($account_id)!=''){
						    $account = \Stripe\Account::retrieve($account_id);
							$this->context->smarty->assign('stripestatus',$account->legal_entity->verification->status);
							$this->context->smarty->assign('bank_data',$account->bank_accounts->data[0]);
							$this->context->smarty->assign('ssn',$account->legal_entity->ssn_last_4);
							$this->context->smarty->assign('type',$account->legal_entity->type);
							$this->context->smarty->assign('fname',$account->legal_entity->first_name);
							$this->context->smarty->assign('lname',$account->legal_entity->last_name);
							$this->context->smarty->assign('dob',$account->legal_entity->dob);
							}else{
							$this->context->smarty->assign('stripestatus','');
							$this->context->smarty->assign('bank_data',array());
							$this->context->smarty->assign('ssn','');
							$this->context->smarty->assign('type','');
							$this->context->smarty->assign('fname','');
							$this->context->smarty->assign('lname','');
							$this->context->smarty->assign('dob',array());
								}
							if( Tools::getValue('update') != '' && Tools::getValue('update') != 0 && Tools::getValue('stripe_error') != '')
                            $this->context->smarty->assign('stripe_error', Tools::getValue('stripe_error'));
				
							
                                $this->context->smarty->assign("edit", 1);
                                $editprofile = $link->getModuleLink('marketplace', 'editProfile', $param);
                               
								if(Tools::getIsset('img_shop'))
									$this->context->smarty->assign("shop_img_size_error",1);
								else
									$this->context->smarty->assign("shop_img_size_error",0);

								if(Tools::getIsset('img_seller'))
									$this->context->smarty->assign("seller_img_size_error",1);
								else
									$this->context->smarty->assign("seller_img_size_error",0);

								$this->context->smarty->assign("editprofile", $editprofile);
                            /*} 
							else
							{
                            	$this->context->smarty->assign("edit", 0);*/
                                if (Tools::getIsset('update'))
                                {
                                	if( Tools::getValue('update') != '' && Tools::getValue('update') != 0 )
                                    	$this->context->smarty->assign('is_profile_updated', 1);
                                }
                                else
									$this->context->smarty->assign('is_profile_updated', 0);
							//}
                           
                            $logo_path = _MODULE_DIR_ . 'marketplace/img/shop_img/'.$marketplace_seller_id . '-' . $marketplace_seller_info['shop_name'] . '.jpg';
                            $this->context->smarty->assign("logo_path", $logo_path);
                            $this->context->smarty->assign("marketplace_address", trim($marketplace_seller_info['address']));
                            $this->context->smarty->assign("marketplace_seller_info", $marketplace_seller_info);
                            $this->context->smarty->assign("market_place_shop", $market_place_shop);

                            $old_seller_logo_path = _PS_MODULE_DIR_.'marketplace/img/seller_img/'.$marketplace_seller_id.'.jpg';
                            if (file_exists($old_seller_logo_path))
                            	$old_seller_logo_path = _MODULE_DIR_.'marketplace/img/seller_img/'.$marketplace_seller_id.'.jpg';
                            else
                            	$old_seller_logo_path = _MODULE_DIR_.'marketplace/img/seller_img/defaultimage.jpg';

                            $shop_name = $marketplace_seller_info['shop_name'];
                            $old_shop_logo_path = _PS_MODULE_DIR_.'marketplace/img/shop_img/'.$marketplace_seller_id.'-'.$shop_name.'.jpg';
                            if (file_exists($old_shop_logo_path))
                            	$old_shop_logo_path = _MODULE_DIR_.'marketplace/img/shop_img/'.$marketplace_seller_id.'-'.$shop_name.'.jpg';
                            else
                            	$old_shop_logo_path = _MODULE_DIR_.'marketplace/img/shop_img/defaultshopimage.jpg';
					  		$this->context->smarty->assign('old_seller_logo_path', $old_seller_logo_path);
					  		$this->context->smarty->assign('old_shop_logo_path', $old_shop_logo_path);

                            $this->setTemplate('marketplace_account1.tpl');
                        }
                        elseif ($logic == 3)
                        {
							if (Tools::isSubmit('duplicate'))
							{
							$id_product = (int)Tools::getValue('id_product');
							$p = Db::getInstance()->getRow("select * from `". _DB_PREFIX_."marketplace_seller_product` where `id`=".$id_product,false);
							Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_.'marketplace_seller_product`
							 (`id_seller`,`price`,`quantity`,`product_name`,`id_category`,`short_description`,`description`,`ps_id_shop`,`id_shop`,`date_add`,`date_upd`) 
							VALUES('.$p['id_seller'].',"'.$p['price'].'","'.$p['quantity'].'","'.$p['product_name'].'",'.$p['id_category'].',"'.$p['short_description'].'","'.$p['description'].'",'.$p['ps_id_shop'].','.$p['id_shop'].',NOW(),NOW())');
							$new_id_product = Db::getInstance()->Insert_ID();
							$cat = Db::getInstance()->executeS("select * from `". _DB_PREFIX_."marketplace_seller_product_category` where `id_seller_product`=".$id_product,false);
							foreach($cat as $c)
								Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_.'marketplace_seller_product_category`
								 (`id_category`,`id_seller_product`,`is_default`) values('.$c['id_category'].','.$new_id_product.','.$c['is_default'].')');
							
							$img = Db::getInstance()->executeS("select * from `". _DB_PREFIX_."marketplace_product_image` where `seller_product_id`=".$id_product,false);
							foreach($img as $i)
								Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_.'marketplace_product_image`
								 (`seller_product_id`,`seller_product_image_id`,`active`) values('.$new_id_product.',"'.$i['seller_product_image_id'].'",0)');
								
							$ship = Db::getInstance()->getRow("select * from `". _DB_PREFIX_."mp_shipping_product_map` where `mp_product_id`=".$id_product,false);
							if(isset($ship['mp_shipping_id']))
							Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_.'mp_shipping_product_map`
								 (`mp_shipping_id`,`ps_id_carriers`,`mp_product_id`,`date_add`,`date_upd`) 
								 values('.$ship['mp_shipping_id'].','.$ship['ps_id_carriers'].','.$new_id_product.', NOW(),NOW())');
								 
							 $this->context->smarty->assign("duplicate_conf", 1);
								}
								
							if(Tools::getIsset('del'))
							{
								$is_deleted   = Tools::getValue('del');
							}
							else
							{
								$is_deleted = 0;
							}
						
							if(Tools::getIsset('edit'))
							{
							   $is_edited = Tools::getValue('edit');
							}
							else
							{
								$is_edited = 0;
							}
							
                            
                            $link         = new link();
							$param = array('flag'=>1);
                            $pro_upd_link = $link->getModuleLink('marketplace', 'productupdate',$param);
							$proimageeditlink = $link->getModuleLink('marketplace', 'productimageedit',$param);
							
							
                            $this->context->smarty->assign("pro_upd_link", $pro_upd_link);
                            $this->context->smarty->assign("proimageeditlink", $proimageeditlink);
                            $pinfosql 		= "SELECT * from`" . _DB_PREFIX_ . "marketplace_seller_product` where id_seller=" . $marketplace_seller_id;
							$product_info   = Db::getInstance()->ExecuteS($pinfosql, true, false);
                    
                            $count = count($product_info);
							
                            $this->context->smarty->assign("product_info", $product_info);
                            $this->context->smarty->assign("count", $count);
                            $this->context->smarty->assign("is_deleted", $is_deleted);
                            $this->context->smarty->assign("is_edited", $is_edited);
							
							//Link
							
							$product_details_link = $link->getModuleLink('marketplace', 'productdetails',$param);
							$this->context->smarty->assign("product_details_link", $product_details_link);
							
							//Pagination
							$NoOfProduct = count($product_info);
							$this->pagination($NoOfProduct);
							
							
							$productList = $obj_mp_seller_product->getProductList($marketplace_seller_id,$orderby,$orderway,$this->p, $this->n);
							if(!$productList)
							{
								$productList = 0;
							}
							else
								$productList = $this->getProductDetails($productList);

							$param = array('l'=>$logic);
							$this->context->smarty->assign('param',$param);
							$sortingLink = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>$logic,'p'=>$page_no));
							$this->context->smarty->assign("sorting_link", $sortingLink);
							$paginationLink = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>$logic,'orderby'=>$orderby,'orderway'=>$orderway));
							$this->context->smarty->assign("pagination_link", $paginationLink);
							$this->context->smarty->assign("page_no", $page_no);
							$this->context->smarty->assign(array(
															'pages_nb' => ceil($NoOfProduct / (int)$this->n),
															'nbProducts' => $NoOfProduct,
															'recordperpage' => (int)$this->n,
															'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
															'product_lists' => $productList,
															));
							$imageediturl = $link->getModuleLink('marketplace','productimageedit');	
							$this->context->smarty->assign('imageediturl',$imageediturl);
                            $this->setTemplate('marketplace_account1.tpl');
                        }
                        elseif ($logic == 4) // for seller's my order
                        {						
                            $customer_id = $this->context->customer->id;							
                            $dashboard = Db::getInstance()->executeS("SELECT ordd.`id_order_detail` as `id_order_detail`,
                            	ordd.`product_name` as `ordered_product_name`,
                            	ordd.`product_price` as total_price,
                            	ordd.`product_quantity` as qty,
                            	ordd.`id_order` as id_order,
                            	ord.`id_customer` as order_by_cus,
                            	ord.`payment` as payment_mode,
                            	ord.`reference` AS ref,
                            	cus.`firstname` as name,
                            	ord.`date_add`,
                            	ords.`name`as order_status,
                            	ord.`id_currency` as `id_currency`
                            	FROM `"._DB_PREFIX_."marketplace_commision_calc` mcc
                            	JOIN `" . _DB_PREFIX_ . "order_detail` ordd on (ordd.`product_id`= mcc.`product_id`)
                            	JOIN `"._DB_PREFIX_."orders` ord on (ordd.`id_order`=ord.`id_order`)
                            	JOIN `"._DB_PREFIX_."marketplace_customer` mkc on (mkc.`id_customer` = mcc.`customer_id`)
                            	JOIN `" . _DB_PREFIX_ . "customer` cus on (mkc.`id_customer`=cus.`id_customer`)
                            	JOIN `" . _DB_PREFIX_ . "order_state_lang` ords on (ord.`current_state`=ords.`id_order_state`) 
                            	WHERE ords.id_lang=".$id_lang." and cus.`id_customer`=" . $customer_id . "  GROUP BY ordd.`id_order` order by ordd.`id_order` desc");
							
							$message = Db::getInstance()->executeS("SELECT ordd.`product_name` as product_name,cusmsg.`message` as message,
								cus.`firstname` as firstname,
								cusmsg.`date_add` as date_add,
								ord.`id_currency` as `id_currency` 
								FROM `"._DB_PREFIX_."marketplace_commision_calc` mcc 
								JOIN `"._DB_PREFIX_."marketplace_customer` mkc ON (mkc.`marketplace_seller_id` = mcc.`id_seller_order`)
								JOIN  `"._DB_PREFIX_."order_detail` ordd ON ( ordd.`product_id` = mcc.`product_id`)
								JOIN  `"._DB_PREFIX_."orders` ord ON ( ordd.`id_order` = ord.`id_order`)
								JOIN `"._DB_PREFIX_."customer_thread` custh ON (custh.`id_order` = ord.`id_order`)
								JOIN `"._DB_PREFIX_."customer_message` cusmsg ON (custh.`id_customer_thread` = cusmsg.`id_customer_thread`)
								JOIN `"._DB_PREFIX_."customer` cus ON (cus.`id_customer` = custh.`id_customer`) 
								WHERE mkc.`id_customer` =".$customer_id);

							$count_msg =count($message);
							 							 
							$a=0;
							$order_by_cus = array();
							$order_currency = array();
							foreach($dashboard as $dashboard1)
							{
								$order_by_cus[]= Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from `"._DB_PREFIX_."customer` where id_customer=".$dashboard1['order_by_cus']);
								$currency_detail = Currency::getCurrency($dashboard1['id_currency']);
								$order_currency[] = $currency_detail['sign'];
								$a++;
							}
							 
							if(isset($order_by_cus))
							{
								if(is_array($order_by_cus))
								{
									$this->context->smarty->assign("order_by_cus", $order_by_cus);
									$this->context->smarty->assign("order_currency", $order_currency);
								}
							}
							
							$link = new link();
                        	$param = array('flag'=>'1');
							$order_view_link = $link->getModuleLink('marketplace','marketplaceaccount',$param);
							$this->context->smarty->assign("order_view_link", $order_view_link);
		
                            $count = count($dashboard);
                     
                           	$this->context->smarty->assign("id_customer", $customer_id);
							$this->context->smarty->assign("id_shop", $id_shop);
                            $this->context->smarty->assign("dashboard", $dashboard);
							$this->context->smarty->assign("message", $message);
							$this->context->smarty->assign("count_msg",$count_msg);
							
                            $this->context->smarty->assign("count", $count);
                            $this->setTemplate('marketplace_account1.tpl');
                        }						
						elseif($logic == 5)						
						{
							$link = new link();
							$param = array('flag'=>'1');
							$payPro_link = $link->getModuleLink('marketplace','paymentprocess',$param);
							$pay_mode = Db::getInstance()->ExecuteS("SELECT * FROM `"._DB_PREFIX_."marketplace_payment_mode`");
							$obj_pay_details = new PaymentDetails();
							$seller_payment_detail = $obj_pay_details->getSellerPaymentDetails($customer_id);
							if($seller_payment_detail)
							{
								$seller_payment_mode = Db::getInstance()->getValue("SELECT `payment_mode` FROM `"._DB_PREFIX_."marketplace_payment_mode` where `id`=".$seller_payment_detail['payment_mode_id']."");
								
								$this->context->smarty->assign('seller_payment_mode',$seller_payment_mode);
								$this->context->smarty->assign('seller_payment_details',$seller_payment_detail);
							}
							$this->context->smarty->assign("pay_mode",$pay_mode);
							$this->context->smarty->assign("payPro_link",$payPro_link);
							$this->setTemplate('marketplace_account1.tpl');
						}
						
						elseif($logic == 6)
						{
							$id_order = Tools::getValue('id_order');
							if (!$id_order)
								$id_order = "";

							$id_shop = Tools::getValue('shop');
							//$id_order_detail = $_GET['id_order_detail'];
							$dashboard   = Db::getInstance()->executeS("SELECT cntry.`name` as `country`,
								cntry.`name` as `country`,
								stat.`name` as `state`,
								ads.`postcode` as `postcode`,
								ads.`city` as `city`,
								ads.`phone` as `phone`,
								ads.`phone_mobile` as `mobile`,
								ordd.`id_order_detail` as `id_order_detail`,
								ordd.`product_name` as `ordered_product_name`,
								ordd.`product_price` as total_price,
								ordd.`product_quantity` as qty, 
								ordd.`id_order` as id_order,
								ord.`id_customer` as order_by_cus,
								ord.`payment` as payment_mode,
								ord.`current_state` as current_state, 
								ord.`total_products` as total_products, 
								ord.`total_shipping` as total_shipping, 
								ord.`total_paid_tax_incl` as total_paid, 
								cus.`firstname` as name,
								cus.`lastname` as lastname,
								cus.`email` as email, 
								cus.`website` as ws, 
								ord.`date_add` as `date`, 
								ord.`reference` as `ref`, 
								ord.`secure_key` as `secure_key`, 
								ords.`name`as order_status,
								ads.`address1` as `address1`,
								ads.`address2` as `address2` 
								FROM  `"._DB_PREFIX_."order_detail` ordd 
								JOIN `"._DB_PREFIX_."orders` ord ON (ord.`id_order` = ordd.`id_order`) 
								JOIN `"._DB_PREFIX_."customer` cus on (cus.`id_customer`= ord.`id_customer`)
								JOIN `"._DB_PREFIX_."order_state_lang` ords on (ord.`current_state`= ords.`id_order_state`) 
								JOIN `"._DB_PREFIX_."address` ads on (ads.`id_customer`= cus.`id_customer`)
								JOIN `"._DB_PREFIX_."state` stat on (stat.`id_state`= ads.`id_state`) 
								JOIN `"._DB_PREFIX_."country_lang` cntry on (cntry.`id_country`= ads.`id_country`) 
								WHERE ordd.`id_order`=".$id_order." and cntry.`id_lang`=".$id_lang);
							if(empty($dashboard))
							{
								$dashboard   = Db::getInstance()->executeS("SELECT 
									cntry.`name` as `country`,
									ads.`postcode` as `postcode`,
									ads.`city` as `city`,
									ads.`phone` as `phone`,
									ads.`phone_mobile` as `mobile`,
									ordd.`id_order_detail` as `id_order_detail`,
									ordd.`product_name` as `ordered_product_name`,
									ordd.`product_price` as total_price,
									ordd.`product_quantity` as qty, 
									ordd.`id_order` as id_order,
									ord.`id_customer` as order_by_cus,
									ord.`payment` as payment_mode,
									ord.`current_state` as current_state,
									ord.`date_add` as `date`,
									ord.`reference` as `ref`, 
									ord.`secure_key` as `secure_key`, 
									ord.`total_products` as total_products, 
									ord.`total_shipping` as total_shipping, 
									ord.`total_paid_tax_incl` as total_paid, 
									cus.`firstname` as name,
									cus.`lastname` as lastname,
									cus.`email` as email,
									cus.`website` as ws,
									ords.`name`as order_status,
									ads.`address1` as `address1`,
									ads.`address2` as `address2` 
									FROM  `"._DB_PREFIX_."order_detail` ordd 
									JOIN `"._DB_PREFIX_."orders` ord ON (ord.`id_order` = ordd.`id_order`) 
									JOIN `"._DB_PREFIX_."customer` cus on (cus.`id_customer`= ord.`id_customer`) 
									JOIN `"._DB_PREFIX_."order_state_lang` ords on (ord.`current_state`= ords.`id_order_state`) 
									JOIN `"._DB_PREFIX_."address` ads on (ads.`id_customer`= cus.`id_customer`) 
									JOIN `"._DB_PREFIX_."country_lang` cntry on (cntry.`id_country`= ads.`id_country`) 
									WHERE ordd.`id_order`=".$id_order." and cntry.`id_lang`=".$id_lang);

								$dashboard_state = "N/A";
							}
							else
								$dashboard_state = $dashboard[0]['state'];
												
							$a=0;
							$dash_price = array();
							foreach($dashboard as $dashboard1)
							{
								$dash_price[] = number_format($dashboard1['total_price'], 2, '.', '');
								$a++;
							}
							$param = array(
								'flag' => (Tools::getValue('flag')) ? (Tools::getValue('flag')) : "",
								'shop' => (Tools::getValue('shop')) ? (Tools::getValue('shop')) : "",
								'l' => (Tools::getValue('l')) ? (Tools::getValue('l')) : "",
								'id_order' => (Tools::getValue('id_order')) ? (Tools::getValue('id_order')) : "");
						
							$shipping_link = $link->getModuleLink('finalshipping','shippingdetails',$param);
						
							$id_customer = $this->context->customer->id;
	                       	$order_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * 
	                       		FROM `'._DB_PREFIX_.'order_detail` od 
	                       		JOIN `'._DB_PREFIX_.'marketplace_commision_calc` mcc ON (mcc.`product_id`= od.`product_id`) 
	                       		WHERE od.`id_order` = '.$id_order.' and mcc.customer_id ='.$id_customer);
	                       	//$order_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($order_info);
							$this->context->smarty->assign("order_info",$order_info);
							//$this->context->smarty->assign("current_state",$current_state['current_state']);
							$count_dashboard = count($dashboard);
							$this->context->smarty->assign("shipping_link",$shipping_link); 
							$this->context->smarty->assign("dash_price",$dash_price);
							$this->context->smarty->assign("count_dashboard",$count_dashboard);
							$this->context->smarty->assign("dashboard",$dashboard);
							$this->context->smarty->assign("dashboard_state",$dashboard_state);
							$this->context->smarty->assign("id_shop",$id_shop);
							$this->setTemplate('marketplace_account1.tpl');  
						}
                    }
                }
                elseif ($is_seller == 0)
                {
                    $this->context->smarty->assign("is_seller", $is_seller);
                    $this->setTemplate('marketplace_account1.tpl');
                } // end of is_seller =2
            } else {
                //is_seller set to -1 when customer not request for market place yet 
                //@is_seller = 0 customer send requset for market place but admin not approve yet
                //@is_seller =1 admin approve market place seller request 
                $this->context->smarty->assign("is_seller", -1);
                $this->setTemplate('marketplace_account1.tpl');
            }
        }
        else
        {
        	$myaccountpage = $link->getPageLink('my-account');
        	Tools::redirect($myaccountpage);
        }
    }

    public function getProductDetails($productList)
	{
		$obj_mp_shop_product = new MarketplaceShopProduct();
		$obj_mp_product = new SellerProductDetail();
		$id_lang = $this->context->language->id;
		foreach ($productList as $key => $product)
		{
			$ps_product = $obj_mp_shop_product->findMainProductIdByMppId($product['id']);
			if ($ps_product) // if product activated
			{
				$obj_product = new Product($ps_product['id_product'], false, $id_lang);
				$cover = Product::getCover($ps_product['id_product']);

				if ($cover)
				{
					$obj_image = new Image($cover['id_image']);
					$productList[$key]['image_path'] = _THEME_PROD_DIR_.$obj_image->getExistingImgPath().'.jpg';
					$productList[$key]['cover_image'] = $ps_product['id_product'].'-'.$cover['id_image'];
				}

				$productList[$key]['id_product'] = $ps_product['id_product'];
				$productList[$key]['id_lang'] = $this->context->language->id;
				$productList[$key]['lang_iso'] = $this->context->language->iso_code;
				$productList[$key]['obj_product'] = $obj_product;
			}
			else //if product not active
			{
				$productList[$key]['price'] = Tools::convertPrice($product['price']); //convert price for multiple currency
				$unactive_image = $obj_mp_product->unactiveImage($product['id']);
				// product is unactive so by default first image is taken because no one is cover image
				if ($unactive_image)
					$productList[$key]['unactive_image'] = $unactive_image[0]['seller_product_image_id'];
			}

			$obj_prod = new Product($ps_product['id_product']);
			$ps_carriers = $obj_prod->getCarriers();
			if (empty($ps_carriers))
				$productList[$key]['shipping'] = 0;
			else
				$productList[$key]['shipping'] = 1;
		}
		return $productList;
	}

    public function setMedia()
    {
        parent::setMedia();
		$this->addCSS(_MODULE_DIR_.'marketplace/css/my_request.css');
        $this->addCSS(_MODULE_DIR_.'marketplace/css/marketplace_account.css');
        $this->addJS(_MODULE_DIR_.'marketplace/js/mp_form_validation.js');
		
		//tinyMCE
        if(Configuration::get('PS_JS_THEME_CACHE')==0)
	        $this->addJS(array(
	                    _MODULE_DIR_ .'marketplace/js/tinymce/tinymce.min.js',
	                    _MODULE_DIR_ .'marketplace/js/tinymce/tinymce_wk_setup.js'
	            ));
        
		$this->addJqueryUI(array('ui.datepicker'));
		$this->addJqueryPlugin(array('fancybox','tablednd'));
		$this->addJqueryPlugin(array('footable','footable-sort'));
		
		Media::addJsDef(array('iso' => $this->context->language->iso_code));

		//datepicker
		$this->addJS(array(
					_MODULE_DIR_.'marketplace/js/jquerydatepicker/jquery-ui.js',
					_MODULE_DIR_.'marketplace/js/jquerydatepicker/jquery-ui-sliderAccess.js',
					_MODULE_DIR_.'marketplace/js/jquerydatepicker/jquery-ui-timepicker-addon.js',
				));
		$this->addCSS(_MODULE_DIR_.'marketplace/js/jquerydatepicker/jquery-ui-timepicker-addon.css');

		$this->addJS(_MODULE_DIR_.'marketplace/views/js/imageedit.js');

		//Jquery mobile (for the marketplace menu)
		if ($this->context->getMobileDevice()){
			$this->addJS(array(
						_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.min.js',
					));
			$this->addCSS(array(
						_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.structure.min.css',
						_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.theme.min.css'
					));
			}
		
    }
}
?>
