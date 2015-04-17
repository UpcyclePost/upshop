<?php
if (!defined('_PS_VERSION_'))
	exit;
class mpshippingmpshippingupdatebasicModuleFrontController extends ModuleFrontController
{
	public function initContent() 
	{
		parent::initContent();
		$link = new Link();
		$my_account_link = $link->getPageLink('my-account');
		$submitupdatecarrier = Tools::getValue('submitupdatecarrier');
		$id_customer = $this->context->cookie->id_customer;
		$img_dir = 'modules/mpshipping/img/logo/';
		if($id_customer)
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
					if($submitupdatecarrier == 1)
					{
						$old_mpshipping_id = Tools::getValue('mpshipping_id');
						$shipping_name = Tools::getValue('shipping_name');
						$transit_time = Tools::getValue('transit_time');
						$shipping_method = Tools::getValue('edit_shipping_method');
						$grade = Tools::getValue('grade');
						$tracking_url = Tools::getValue('tracking_url');
						$shipping_handling = Tools::getValue('shipping_handling');
						$is_free =  Tools::getValue('is_free');
						$max_height =  Tools::getValue('max_height');
						$max_width =  Tools::getValue('max_width');
						$max_depth =  Tools::getValue('max_depth');
						$max_weight =  Tools::getValue('max_weight');
						
						$is_valid_shipping_name = Validate::isCarrierName($shipping_name);
						if(!$is_valid_shipping_name)
						{
							$params = array('is_main_error'=>'1');
							$updateshipping_link = $link->getModuleLink('mpshipping','basiceditshipping',$params);
							Tools::redirect($updateshipping_link);
						}
						
						$is_valid_transit_time = Validate::isGenericName($transit_time);
						if(!$is_valid_transit_time)
						{
							$params = array('is_main_error'=>'2');
							$updateshipping_link = $link->getModuleLink('mpshipping','basiceditshipping',$params);
							Tools::redirect($updateshipping_link);
						}
						
						$is_new_image = false;
						if(isset($_FILES['shipping_logo']))
						{
							if($_FILES['shipping_logo']['size']>0 && $_FILES['shipping_logo']['tmp_name']!='')
							{
								$image_type = array('jpg','jpeg','png');
								$extention = explode('.',$_FILES['shipping_logo']['name']);
								$ext = Tools::strtolower($extention['1']);
								if(!in_array($ext,$image_type))
								{
									$params = array('is_main_error'=>'3');
									$updateshipping_link = $link->getModuleLink('mpshipping','basiceditshipping',$params);
									Tools::redirect($updateshipping_link);
								}
								else
								{
									list($width, $height) = getimagesize($_FILES['shipping_logo']['tmp_name']);
									if($width >125 ||  $height >125)
									{
										$params = array('is_main_error'=>'3');
										$updateshipping_link = $link->getModuleLink('mpshipping','basiceditshipping',$params);
										Tools::redirect($updateshipping_link);
									}
									$is_new_image = true;
								}
							}
						}
						
						$is_valid_grade = Validate::isUnsignedInt($grade);
						if(!$is_valid_grade)
						{
							$params = array('is_main_error'=>'4');
							$updateshipping_link = $link->getModuleLink('mpshipping','basiceditshipping', $params);
							Tools::redirect($updateshipping_link);
						}
						else if($grade>9)
						{
							$params = array('is_main_error'=>'4');
							$updateshipping_link = $link->getModuleLink('mpshipping','basiceditshipping', $params);
							Tools::redirect($updateshipping_link);
						}
						
						
						
						$is_valid_tracking_url = Validate::isAbsoluteUrl($tracking_url);
						if(!$is_valid_tracking_url)
						{
							$params = array('is_main_error'=>'5');
							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}

						if($max_height == "")
							$max_height = (int)0;

						$is_valid_max_height = Validate::isUnsignedInt($max_height);
						if(!$is_valid_max_height)
						{
							$params = array('is_main_error'=>'6','mpsp_id'=>$mpshipping_id,'step'=>3);
							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}
						
						if($max_width == "")
							$max_width = (int)0;

						$is_valid_max_width = Validate::isUnsignedInt($max_width);
						if(!$is_valid_max_width)
						{
							$params = array('is_main_error'=>'7','mpsp_id'=>$mpshipping_id,'step'=>3);
							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}
						
						if($max_depth == "")
							$max_depth = (int)0;

						$is_valid_max_depth = Validate::isUnsignedInt($max_depth);
						if(!$is_valid_max_depth)
						{
							$params = array('is_main_error'=>'8','mpsp_id'=>$mpshipping_id,'step'=>3);
							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}
						
						if($max_weight == "")
							$max_weight = (float)0;

						$is_valid_max_weight = Validate::isFloat($max_weight);
						if(!$is_valid_max_weight)
						{
							$params = array('is_main_error'=>'9','mpsp_id'=>$mpshipping_id,'step'=>3);
							$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
							Tools::redirect($addnewshipping_link);
						}
						
						//set deleted 1 for old mpshipping method.
						$obj_old_mpshipping_method = new Mpshippingmethod($old_mpshipping_id);
						$obj_old_mpshipping_method->deleted = 1;
						$obj_old_mpshipping_method->save();
						
						//create new shipping method and set reference to old shipping method reference
						$obj_mpshipping_method = new Mpshippingmethod();
						
						$obj_mpshipping_method->mp_shipping_name = $shipping_name;
						$obj_mpshipping_method->transit_delay = $transit_time;
						$obj_mpshipping_method->grade = $grade;
						$obj_mpshipping_method->shipping_handling = $shipping_handling;
						$obj_mpshipping_method->is_free = $is_free;
						$obj_mpshipping_method->deleted = 0;
						$obj_mpshipping_method->mp_id_seller = $mp_id_seller;
						$obj_mpshipping_method->mp_id_shop = $mp_id_shop;
						$obj_mpshipping_method->ps_id_shop = $ps_id_shop;
						$obj_mpshipping_method->id_reference = $obj_old_mpshipping_method->id_reference;
						$obj_mpshipping_method->max_height = $max_height;
						$obj_mpshipping_method->max_width = $max_width;
						$obj_mpshipping_method->max_depth = $max_depth;
						$obj_mpshipping_method->max_weight = $max_weight;
						
						if ($is_free)
							$obj_mpshipping_method->shipping_method = 2;
						else
							$obj_mpshipping_method->shipping_method = $shipping_method;
						$obj_mpshipping_method->is_done = 1;
						$obj_mpshipping_method->save();
						
						$mpshipping_id = $obj_mpshipping_method->id;
						if($is_new_image == true)
							move_uploaded_file($_FILES['shipping_logo']['tmp_name'],$img_dir.$mpshipping_id.'.jpg');
						
						$zone_detail = Zone::getZones();
						
						
						$range_inf = Tools::getValue('range_inf');
						$range_sup = Tools::getValue('range_sup');
						
						
						if($shipping_method == 2) //obj for price
							$obj_range_obj = new Mprangeprice();
						else if($shipping_method==1) //obj for weight
							$obj_range_obj = new Mprangeweight();
						


						if($is_free)
						{
							foreach($zone_detail as $zone)
							{
								$obj_mpshipping_del = new Mpshippingdelivery();
								$zone_id = $zone['id_zone'];
								$post_name = 'zone_'.$zone_id;
								$is_fee_set = Tools::getValue($post_name);
								if($is_fee_set)
								{
									$obj_mpshipping_del->mp_shipping_id = $mpshipping_id;
									$obj_mpshipping_del->id_zone = $zone_id;
									$obj_mpshipping_del->mp_id_range_price = 0;
									$obj_mpshipping_del->mp_id_range_weight = 0;
									$obj_mpshipping_del->base_price = (float)0;
									$obj_range_obj->delimiter1 = (float)0;
									$obj_range_obj->delimiter2 = (float)0;

									$obj_range_obj->mp_shipping_id = $mpshipping_id;
									$is_available = $obj_range_obj->isRangeInTableByShippingId();
									if($is_available)
									{
										$id_range = $is_available[0]['id'];
										$delivery_id = $obj_mpshipping_del->getDeliveryId($zone_id,$mpshipping_id,$id_range,$shipping_method);
										if($delivery_id)
										{
											$obj_mpshipping_del->id = $delivery_id;
											$obj_mpshipping_del->mp_shipping_id  = $mpshipping_id;
											$obj_mpshipping_del->id_zone  = $zone_id;
											$obj_mpshipping_del->save();
										}
									}
									else
									{
										$obj_range_obj->add();
										$obj_mpshipping_del->mp_id_range_price = $obj_range_obj->id;
									}
									$obj_mpshipping_del->save();
								}
							}
						}
						else
						{
							if ($obj_range_obj)
							{
								foreach($range_inf as $key=>$value)
								{
									$obj_range_obj->delimiter1 = (float)$value;
									$obj_range_obj->delimiter2 = (float)$range_sup[$key];
									$obj_range_obj->mp_shipping_id = $mpshipping_id;
									
									//check is range all ready created
									$is_available = $obj_range_obj->isRangeInTableByShippingId();

									if($is_available)
									{
										/*echo "hello 1";
										die;*/
										//set price according to zone and range
										//if range available in table all ready then only update price
										$id_range = $is_available[0]['id'];
										$obj_mpshipping_del = new Mpshippingdelivery();
										foreach($zone_detail as $zone)
										{
											$id_zone = $zone['id_zone'];
											$delivery_id = $obj_mpshipping_del->getDeliveryId($id_zone,$mpshipping_id,$id_range,$shipping_method);
											
											$zone_fees = Tools::getValue('fees');
											if($delivery_id)
											{
												
												$obj_mpshipping_del->id = $delivery_id;
												$obj_mpshipping_del->mp_shipping_id  = $mpshipping_id;
												$obj_mpshipping_del->id_zone  = $id_zone;
												if($shipping_method==2)
												{
													$obj_mpshipping_del->mp_id_range_price=$key;
													$obj_mpshipping_del->mp_id_range_weight=0;
												}
												else
												{
													$obj_mpshipping_del->mp_id_range_price=0;
													$obj_mpshipping_del->mp_id_range_weight=$key;
												}
												
												$obj_mpshipping_del->base_price = (float)$zone_fees[$id_zone][$key];
												if($obj_mpshipping_del->base_price == "" OR $obj_mpshipping_del->base_price == "on")
													$obj_mpshipping_del->base_price = (float)0;
												
												$obj_mpshipping_del->save();
											}
										}
										//continue;
										//then update zone price if its change
									} 
									else
									{
										$obj_range_obj->add();

										foreach($zone_detail as $zone) {
											//set price according to zone and range
											$obj_mpshipping_del = new Mpshippingdelivery();
											$zone_id = $zone['id_zone'];
											$post_name = 'zone_'.$zone_id;
											$is_fee_set = Tools::getValue($post_name);

											if($is_fee_set)
											{
												$obj_mpshipping_del->mp_shipping_id = $mpshipping_id;
												$obj_mpshipping_del->id_zone = $zone_id;

												if($shipping_method==2)
												{
													$obj_mpshipping_del->mp_id_range_price = $obj_range_obj->id;
													$obj_mpshipping_del->mp_id_range_weight = 0;
												}
												else if($shipping_method==1)
												{
													$obj_mpshipping_del->mp_id_range_weight = $obj_range_obj->id;
													$obj_mpshipping_del->mp_id_range_price =0;
												}
												
												$zone_fees = Tools::getValue('fees');
												$obj_mpshipping_del->base_price = (float)$zone_fees[$zone_id][$key];

												if($obj_mpshipping_del->base_price == "" OR $obj_mpshipping_del->base_price == "on")
												{
													$obj_mpshipping_del->base_price = (float)0;
												}
												/*echo "hello<br>";
												echo "shipping id = $mpshipping_id";
												echo "price = ".$obj_mpshipping_del->mp_id_range_price;
												echo "weight = ".$obj_mpshipping_del->mp_id_range_weight;
												echo "fee=$is_fee_set";
												echo "base price = ";echo $zone_fees[$zone_id][$key];
												echo "zone id=$zone_id";*/
												
												//echo "<pre>";
												//print_r($zone_fees);
												
												//die;
												
												$obj_mpshipping_del->save();
											}
										}//die;
									}
								}
							}
						}
						
						//if old shipping method active then make corresponding changes in main shipping method
						if($obj_old_mpshipping_method->active == 1) 
						{
							$obj_shiiping_map = new Mpshippingmap();
							$ps_carrier_id = $obj_shiiping_map->getCarrierId($old_mpshipping_id);
							
							$obj_carrier = new Carrier($ps_carrier_id);
							// update function is called for update entries in core carrier table and id_reference is used for update because id_refernce donat change when admin or seller update shipping method
							Db::getInstance()->update('carrier', array('deleted' => 1), 'id_reference = '.$obj_carrier->id_reference);
							/*$obj_carrier->deleted = 1;
							$obj_carrier->save();*/
							
							$obj_mpshipping_method->active = 1;
							$obj_mpshipping_method->is_done = 1;
							$obj_mpshipping_method->save();
							
							//create new shipping method
							$addedto_carrier = $obj_mpshipping_method->addToCarrier($mpshipping_id,$obj_carrier->id_reference);

							
							if($is_new_image==true)
								copy($img_dir.$mpshipping_id.'.jpg',_PS_IMG_DIR_.'s/'.$addedto_carrier.'.jpg');
							else
							{
								if(file_exists($img_dir.$old_mpshipping_id.'.jpg'))
									copy($img_dir.$old_mpshipping_id.'.jpg',_PS_IMG_DIR_.'s/'.$addedto_carrier.'.jpg');
							}
							
							$obj_shiiping_map->mp_shipping_id	 = $mpshipping_id;
							$obj_shiiping_map->ps_id_carriers	 = $addedto_carrier;
							$obj_shiiping_map->add();
						}
						$extra = array('id_shipping'=>$mpshipping_id,'is_succe'=>1);
						$impact_price_edit_link = $link->getModuleLink('mpshipping','impactpriceedit',$extra);
						
						Tools::redirect($impact_price_edit_link);
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
		else
			Tools::redirect($my_account_link);
	}
}