<?php
	if (!defined('_PS_VERSION_'))
	exit;
	class mpshippingaddnewshippingModuleFrontController extends ModuleFrontController	
	{
		public function initContent() 
		{
			parent::initContent();	
			$link = new Link();

			$currency_details = Currency::getCurrency(Context::getContext()->cookie->id_currency);
			$this->context->smarty->assign('currency_details',$currency_details);
			
			$shipping_ajax_link = $link->getModuleLink('mpshipping','shippingajax');
			$this->context->smarty->assign('shipping_ajax_link',$shipping_ajax_link);

			$shipping_ajax_range = $link->getModuleLink('mpshipping','shoprange');
			$this->context->smarty->assign('shipping_ajax_range_link',$shipping_ajax_range);
			
			$id_customer = $this->context->cookie->id_customer;
			if($id_customer)
			{
				//check customer is marketplace customer or not
				$mp_customer = new MarketplaceCustomer();
				$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);

				if($mp_customer_info) 
				{
					$is_seller = $mp_customer_info['is_seller'];

					//is_seller 1 means seller active
					if($is_seller==1) 
					{
						//marketplace shop info by customer id
						$obj_mp_shop = new MarketplaceShop();
						
						$mp_shop_info = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($id_customer);
						$mp_id_shop = $mp_shop_info['id'];						

						//if erorr occur
						$is_main_error = Tools::getValue('is_main_error');						
						if($is_main_error) 
							$this->context->smarty->assign('is_main_error',$is_main_error);
						else 
							$this->context->smarty->assign('is_main_error',-1);
						
						//shipping_id
						$mp_shipping_id = Tools::getValue('id_shipping');
						if($mp_shipping_id) 
							$this->context->smarty->assign('mp_shipping_id',$mp_shipping_id);

						//steps
						$step_count = Tools::getValue('step');
						if($step_count) 
							$this->context->smarty->assign('step_count',$step_count);
						else 
						{
							$step_count = 1;
							$this->context->smarty->assign('step_count',1);
						}

						//get total zone available in prestashop
						$zone_detail = Zone::getZones();
						$this->context->smarty->assign('zones',$zone_detail);

						//if isset $mp_shipping_id means user create shipping method and is on second step or third step or may return back on first step
						if($mp_shipping_id) 
						{
							$obj_mpshipping_method = new Mpshippingmethod($mp_shipping_id);

							$this->context->smarty->assign('mp_shipping_name',$obj_mpshipping_method->mp_shipping_name);
							$this->context->smarty->assign('transit_delay',$obj_mpshipping_method->transit_delay);
							$this->context->smarty->assign('shipping_method',$obj_mpshipping_method->shipping_method);
							$this->context->smarty->assign('tracking_url',$obj_mpshipping_method->tracking_url);
							$this->context->smarty->assign('grade',$obj_mpshipping_method->grade);
							$this->context->smarty->assign('is_free',$obj_mpshipping_method->is_free);
							$this->context->smarty->assign('shipping_handling',$obj_mpshipping_method->shipping_handling);
							$this->context->smarty->assign('shipping_handling_charge',Configuration::get('PS_SHIPPING_HANDLING'));
							$this->context->smarty->assign('max_width',$obj_mpshipping_method->max_width);
							$this->context->smarty->assign('max_height',$obj_mpshipping_method->max_height);
							$this->context->smarty->assign('max_depth',$obj_mpshipping_method->max_depth);
							$this->context->smarty->assign('max_weight',$obj_mpshipping_method->max_weight);
							$this->context->smarty->assign('shipping_policy',$obj_mpshipping_method->shipping_policy);
							
							$id_shop = $mp_id_shop;
							$param = array('shop'=>$id_shop);
							
							$link_store        = $link->getModuleLink('marketplace', 'shopstore',array('shop'=>$id_shop,'shop_name'=>$name_shop));
							$add_product       = $link->getModuleLink('marketplace', 'addproduct',$param);
							$account_dashboard = $link->getModuleLink('marketplace', 'marketplaceaccount',$param);
							$edit_profile    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>2,'edit-profile'=>1));
							$product_list    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>3));
							$my_order    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>4));
							
							$this->context->smarty->assign("id_shop", $id_shop);
							$this->context->smarty->assign("id_customer", $customer_id);
							$this->context->smarty->assign("link_store", $link_store);
							$this->context->smarty->assign("add_product", $add_product);
							$this->context->smarty->assign("account_dashboard", $account_dashboard);
							$this->context->smarty->assign("edit_profile", $edit_profile);
							$this->context->smarty->assign("product_list", $product_list);
							$this->context->smarty->assign("my_order", $my_order);
						
							$this->context->smarty->assign('is_seller',$is_seller);							
							
							//@shipping_method==1 billing accroding to weight
							//@shipping_method==2 billing accroding to price
							$shipping_method = $obj_mpshipping_method->shipping_method;
							$ranges = array();
							if($obj_mpshipping_method->shipping_method==1) 
							{
								//find range by weight
								$obj_range_weight = new Mprangeweight();
								$obj_range_weight->mp_shipping_id = $mp_shipping_id;
								$different_range = $obj_range_weight->getAllRangeAccordingToShippingId();
								if($different_range)
									$ranges = $different_range;
								else
									$this->context->smarty->assign('different_range', -1);
							}
							elseif($obj_mpshipping_method->shipping_method==2) 
							{
								// find range by price
								$obj_range_price = new Mprangeprice();
								$obj_range_price->mp_shipping_id = $mp_shipping_id;
								$different_range = $obj_range_price->getAllRangeAccordingToShippingId();
								if($different_range) 
									$ranges = $different_range;
								else 
									$this->context->smarty->assign('different_range',-1);
							}
							
							if (!count($ranges))
								$ranges[] = array('id_range' => 0, 'delimiter1' => 0, 'delimiter2' => 0);
							$this->context->smarty->assign('ranges',$ranges);
							
							//find zone where shipping method deliver product
							$obj_mp_delivery = new Mpshippingdelivery();
							$id_zone_detail = $obj_mp_delivery->getIdZoneByShiipingId($mp_shipping_id);
							
							if($id_zone_detail) 
							{
								$fields_value = array();
								foreach($id_zone_detail as $id_zo_det) 
								{
									$fields_value['zones'][$id_zo_det['id_zone']]=1;
								}
								$this->context->smarty->assign('fields_value',$fields_value);
								
								//get delivery details by shipping id its provide price for different range
								$delivery_shipping_detail = $obj_mp_delivery->getDeliveryDetailByShiipingId($mp_shipping_id);
								
								if($delivery_shipping_detail) 
								{
									$price_by_range = array();
									foreach($delivery_shipping_detail as $delivery_shipping) 
									{
										if($shipping_method==2)
											$price_by_range[$delivery_shipping['mp_id_range_price']][$delivery_shipping['id_zone']] = round($delivery_shipping['base_price'],2);
										else
											$price_by_range[$delivery_shipping['mp_id_range_weight']][$delivery_shipping['id_zone']] = round($delivery_shipping['base_price'],2);
									}
									
									$this->context->smarty->assign('price_by_range',$price_by_range);
								}
							}
						} 
						else 
						{
							//when user first time comes then comes here
							$this->context->smarty->assign('shipping_method',2);
							$this->context->smarty->assign('mp_shipping_name','');
							$this->context->smarty->assign('transit_delay','');
							$this->context->smarty->assign('tracking_url','');
							$this->context->smarty->assign('grade',0);
							$this->context->smarty->assign('max_width',0);
							$this->context->smarty->assign('max_height',0);
							$this->context->smarty->assign('max_depth',0);
							$this->context->smarty->assign('max_weight',0);
						}
						
						
						$extra = array('shop'=>(int)$mp_id_shop);
						$dash_board_link = $link->getModuleLink('marketplace','marketplaceaccount',$extra);
						
						$mpshippingprocess_link = $link->getModuleLink('mpshipping','mpshippingprocess',array('shop'=>(int)$mp_id_shop));
						$mpshippingprocess_link_step1 = $link->getModuleLink('mpshipping','mpshippingprocess',array('shop'=>(int)$mp_id_shop,'submitAddcarrier'=>1));
						$mpshippingprocess_link_step2 = $link->getModuleLink('mpshipping','mpshippingprocess',array('shop'=>(int)$mp_id_shop,'submitAddcarrier'=>2));
						$mpshippingprocess_link_step3 = $link->getModuleLink('mpshipping','mpshippingprocess',array('shop'=>(int)$mp_id_shop,'submitAddcarrier'=>3));
						$mpshippingprocess_link_step4 = $link->getModuleLink('mpshipping','mpshippingprocess',array('shop'=>(int)$mp_id_shop,'submitAddcarrier'=>4));
						$mpshippingprocess_link_update = $link->getModuleLink('mpshipping','mpshippingprocess',array('shop'=>(int)$mp_id_shop,'submitupdatecarrier'=>1));

						
						$sellershippinglist_link = $link->getModuleLink('mpshipping','sellershippinglist');
								
						$this->context->smarty->assign('self',dirname(__FILE__));
						$this->context->smarty->assign('sellershippinglist_link',$sellershippinglist_link);
						$this->context->smarty->assign('dash_board_link',$dash_board_link);
						$this->context->smarty->assign('mpshippingprocess_link',$mpshippingprocess_link);

						$this->context->smarty->assign('mpshippingprocess_link_step1',$mpshippingprocess_link_step1);
						$this->context->smarty->assign('mpshippingprocess_link_step2',$mpshippingprocess_link_step2);
						$this->context->smarty->assign('mpshippingprocess_link_step3',$mpshippingprocess_link_step3);
						$this->context->smarty->assign('mpshippingprocess_link_step4',$mpshippingprocess_link_step4);
						$this->context->smarty->assign('mpshippingprocess_link_update',$mpshippingprocess_link_update);
						
						$currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
						$this->context->smarty->assign('currency_sign', $currency->sign);
						$this->context->smarty->assign('PS_WEIGHT_UNIT', Configuration::get('PS_WEIGHT_UNIT'));
							

						$this->addCSS(_MODULE_DIR_.'mpshipping/css/sellershippinglist.css');
						$this->addCSS(_MODULE_DIR_.'marketplace/css/marketplace_account.css');
				
						// Jquery for the mobile menu
						if ($this->context->getMobileDevice()){
							$this->addJS(array(
										_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.min.js',
									));
							$this->addCSS(array(
										_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.structure.min.css',
										_MODULE_DIR_.'marketplace/js/mobile/jquery.mobile.custom.theme.min.css'
									));
							}

						$this->addJs(_MODULE_DIR_.'mpshipping/js/mpshipping.js');
						// $this->setTemplate('addmpshipping.tpl');
						$this->setTemplate('wkshipping.tpl');
					}
				}
			}
		}
		
		public function setMedia() 
		{
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_.'mpshipping/css/sellershippinglist.css');
			$this->addJs(_MODULE_DIR_.'mpshipping/js/mpshipping.js');
			$this->addJqueryPlugin('typewatch');
		}
	}
?>