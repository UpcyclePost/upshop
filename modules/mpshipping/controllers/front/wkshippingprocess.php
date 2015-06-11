<?php
	if (!defined('_PS_VERSION_'))
		exit;
	class mpShippingWkShippingProcessModuleFrontController extends ModuleFrontController	
	{
		public function initContent()
		{
			parent::initContent();
			$link = new Link();

			$my_account_link = $link->getPageLink('my-account');
			$id_customer = $this->context->cookie->id_customer;
			if ($id_customer) 
			{
				$ps_id_shop = $this->context->shop->id;
				$mp_customer = new MarketplaceCustomer();
				$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);
				if($mp_customer_info) 
				{
					$is_seller = $mp_customer_info['is_seller'];
					if($is_seller==1) 
					{
						$mp_id_seller = $mp_customer_info['marketplace_seller_id'];
						$obj_mp_shop = new MarketplaceShop();
						$mp_shop_info = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($id_customer);						
						$mp_id_shop = $mp_shop_info['id'];

						$mp_id_shippping = 0;
						if (Tools::getValue('mpshipping_id'))
							$mp_id_shippping = Tools::getValue('mpshipping_id');

						$shipping_name = Tools::getValue('shipping_name');
						$transit_time = Tools::getValue('transit_time');
						$tracking_url = Tools::getValue('tracking_url');
						
						$north_america_ship = Tools::getValue('n_america_ship');
						$else_ship = Tools::getValue('else_ship');

						if (!$north_america_ship && !$else_ship)
							$is_free = 1;
						else
							$is_free = 0;

						$ship_policy = Tools::getValue('ship_policy');

						$shipping_method = 2;
						$grade = 1;
						$range_inf = 0;
						$range_sup = 10000000000;
						$shipping_handling = 0;
						$max_height =  0;
						$max_width =  0;
						$max_depth =  0;
						$max_weight =  0;
						
						$is_valid_shipping_name = Validate::isCarrierName($shipping_name);
						if(!$is_valid_shipping_name) 
						{
							if ($mp_id_shippping)
								$params = array('is_main_error'=>'1', 'id_shipping' => $mp_id_shippping);
							else
								$params = array('is_main_error'=>'1');

							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}
						
						$is_valid_transit_time = Validate::isGenericName($transit_time);
						if(!$is_valid_transit_time) 
						{
							if ($mp_id_shippping)
								$params = array('is_main_error'=>'2', 'id_shipping' => $mp_id_shippping);
							else
								$params = array('is_main_error'=>'2');

							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}
						
						$is_valid_tracking_url = Validate::isAbsoluteUrl($tracking_url);
						if(!$is_valid_tracking_url) 
						{
							if ($mp_id_shippping)
								$params = array('is_main_error'=>'5', 'id_shipping' => $mp_id_shippping);
							else
								$params = array('is_main_error'=>'5');

							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}
						if(!Validate::isPrice($north_america_ship) || !Validate::isPrice($else_ship))
						{
							if ($mp_id_shippping)
								$params = array('is_main_error'=>'6', 'id_shipping' => $mp_id_shippping);
							else
								$params = array('is_main_error'=>'6');

							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}

						if ($mp_id_shippping)
							$obj_mpshipping_method = new Mpshippingmethod($mp_id_shippping);
						else
							$obj_mpshipping_method = new Mpshippingmethod();

						$obj_mpshipping_method->mp_shipping_name = $shipping_name;
						$obj_mpshipping_method->transit_delay = $transit_time;
						$obj_mpshipping_method->tracking_url = $tracking_url;
						$obj_mpshipping_method->grade = $grade;
						$obj_mpshipping_method->shipping_method = $shipping_method;
						$obj_mpshipping_method->deleted = 0;
						$obj_mpshipping_method->mp_id_seller = $mp_id_seller;
						$obj_mpshipping_method->mp_id_shop = $mp_id_shop;
						$obj_mpshipping_method->ps_id_shop = $ps_id_shop;
						
						$obj_mpshipping_method->is_free = (int)$is_free;
						$obj_mpshipping_method->shipping_handling = $shipping_handling;

						$obj_mpshipping_method->max_height = $max_height;
						$obj_mpshipping_method->max_width = $max_width;
						$obj_mpshipping_method->max_depth = $max_depth;
						$obj_mpshipping_method->max_weight = $max_weight;

						$obj_mpshipping_method->is_done = 1;
						$obj_mpshipping_method->shipping_policy = trim($ship_policy);

						$obj_mpshipping_method->save();

						$mpshipping_id = $obj_mpshipping_method->id;
						$obj_mpshipping_method->id_reference = $mpshipping_id;
						$obj_mpshipping_method->save();

						$is_ps_carrier = $obj_mpshipping_method->active;
						
						if (!$mp_id_shippping)
						{
							$img_dir = 'modules/mpshipping/img/logo/';
							Tools::copy(_PS_SHIP_IMG_DIR_.'2.jpg',$img_dir.$mpshipping_id.'.jpg');
						}

						$zone_detail = Zone::getZones();
						$obj_range_obj = new Mprangeprice();
						$range_info = $obj_range_obj->getRangeDetailsByShippingId($mpshipping_id);

						if ($range_info) 
							$obj_range_obj = new Mprangeprice($range_info['id']);

						if($is_free)
						{
							foreach($zone_detail as $zone)
							{
								$zone_id = $zone['id_zone'];
								$obj_mpshipping_del = new Mpshippingdelivery();
								$ship_zone_dtl = $obj_mpshipping_del->getDliveryMethodByIdZone($zone_id,$mpshipping_id);
								if ($ship_zone_dtl)
									$obj_mpshipping_del = new Mpshippingdelivery($ship_zone_dtl['id']);

								if (!$range_info)
								{
									$obj_range_obj->delimiter1 = (float)0;
									$obj_range_obj->delimiter2 = (float)0;
									$obj_range_obj->mp_shipping_id = $mpshipping_id;
									$obj_range_obj->add();
								}

								if (!$ship_zone_dtl)
								{
									$obj_mpshipping_del->mp_shipping_id = $mpshipping_id;
									$obj_mpshipping_del->id_zone = $zone_id;
									$obj_mpshipping_del->mp_id_range_price = $obj_range_obj->id;
									$obj_mpshipping_del->mp_id_range_weight = 0;
								}

								$obj_mpshipping_del->base_price = (float)0;
								$obj_mpshipping_del->save();
							}
						}
						else
						{
							if (!$range_info)
							{
								$obj_range_obj->delimiter1 = $range_inf;
								$obj_range_obj->delimiter2 = $range_sup;
								$obj_range_obj->mp_shipping_id = $mpshipping_id;
								$obj_range_obj->add();
							}
							foreach($zone_detail as $zone)
							{
								$zone_id = $zone['id_zone'];

								$obj_mpshipping_del = new Mpshippingdelivery();
								$ship_zone_dtl = $obj_mpshipping_del->getDliveryMethodByIdZone($zone_id,$mpshipping_id);
								if ($ship_zone_dtl)
									$obj_mpshipping_del = new Mpshippingdelivery($ship_zone_dtl['id']);
								
								if (!$ship_zone_dtl)
								{
									$obj_mpshipping_del->mp_shipping_id = $mpshipping_id;
									$obj_mpshipping_del->id_zone = $zone_id;
									$obj_mpshipping_del->mp_id_range_price = $obj_range_obj->id;
									$obj_mpshipping_del->mp_id_range_weight = 0;
								}

								if ($zone_id == 2) 
								{
									if ($north_america_ship)
										$obj_mpshipping_del->base_price = (float)$north_america_ship;
									else
										$obj_mpshipping_del->base_price = 0;

									$obj_mpshipping_del->save();
								}
								else
								{
									if ($else_ship)
										$obj_mpshipping_del->base_price = (float)$else_ship;
									else
										$obj_mpshipping_del->base_price = 0;

									$obj_mpshipping_del->save();
								}
							}
						}

						if (!$mp_id_shippping && !$is_ps_carrier) 
						{
							if (!Configuration::get('MP_SHIPPING_ADMIN_APPROVE')) 
							{
								$obj_mpshipping_method = new Mpshippingmethod($mpshipping_id);
								$obj_mp_map = new Mpshippingmap();
								$is_mapped = $obj_mp_map->isAllreadyMapByShippingID($mpshipping_id);
								if(!$is_mapped) 
								{
									$obj_mpshipping_method->active = 1;
									$obj_mpshipping_method->save();
									$is_added = $obj_mpshipping_method->addToCarrier($mpshipping_id);
									$obj_mp_map->mp_shipping_id	 = $mpshipping_id;
									$obj_mp_map->ps_id_carriers	 = $is_added;
									$obj_mp_map->save();
									$img_dir = _PS_MODULE_DIR_.$this->module->name.'/img/logo/';
									if(file_exists($img_dir.$mpshipping_id.'.jpg')) 
									{
										Tools::copy($img_dir.$mpshipping_id.'.jpg',_PS_IMG_DIR_.'s/'.$is_added.'.jpg');
									}
								}
							}
						}

						if ($mp_id_shippping && $is_ps_carrier) 
						{
							$obj_mpshipping_del = new Mpshippingdelivery();
							$obj_mpshipping_method = new Mpshippingmethod($mp_id_shippping);
							$obj_mp_map = new Mpshippingmap();

							$is_mapped = $obj_mp_map->isAllreadyMapByShippingID($mp_id_shippping);

							$ps_id_carriers = $is_mapped['ps_id_carriers'];
							$obj_carrier = new Carrier($ps_id_carriers);

							$obj_carrier->name = $obj_mpshipping_method->mp_shipping_name;
							$obj_carrier->url = $obj_mpshipping_method->tracking_url;

							foreach (Language::getLanguages(true) as $lang)
							{
								$obj_carrier->delay[$lang['id_lang']] = $obj_mpshipping_method->transit_delay;
							}

							$obj_carrier->save();

							$mpship_dlv_dtl = $obj_mpshipping_del->getDeliveryDetailByShiipingId($mpshipping_id);

							foreach ($mpship_dlv_dtl as $key => $value)
							{
								Db::getInstance()->update('delivery', array('price' => $value['base_price']), 'id_carrier='.$ps_id_carriers.' AND id_zone='.$value['id_zone']);
							}
						}

						$params = array('su'=>'1');
						$sellershippinglist_link = $link->getModuleLink('mpshipping','sellershippinglist',$params);
						Tools::redirect($sellershippinglist_link);
					} 
					else 
						Tools::redirect($my_account_link);
				} 
				else 
					Tools::redirect($my_account_link);
			}
			else 
				Tools::redirect($my_account_link);
		}
	}
?>