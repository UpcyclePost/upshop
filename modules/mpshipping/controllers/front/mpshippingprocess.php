<?php
	if (!defined('_PS_VERSION_'))
		exit;
	class mpshippingmpshippingprocessModuleFrontController extends ModuleFrontController	
	{
		public function initContent() 
		{
			parent::initContent();
			$link = new Link();
			$my_account_link = $link->getPageLink('my-account');
			$submitaddcarrier = Tools::getValue('submitAddcarrier');
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
						
						//step 1 process
						if($submitaddcarrier==1) 
						{
							$shipping_name = Tools::getValue('shipping_name');
							$transit_time = Tools::getValue('transit_time');
							$shipping_method = Tools::getValue('shipping_method');
							$grade = Tools::getValue('grade');
							$tracking_url = Tools::getValue('tracking_url');


							
							$is_valid_shipping_name = Validate::isCarrierName($shipping_name);
							if(!$is_valid_shipping_name) 
							{
								$params = array('is_main_error'=>'1');
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
							
							$is_valid_transit_time = Validate::isGenericName($transit_time);
							if(!$is_valid_transit_time) 
							{
								$params = array('is_main_error'=>'2');
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
							
							$is_valid_grade = Validate::isUnsignedInt($grade);
							if(!$is_valid_grade) 
							{
								$params = array('is_main_error'=>'4');
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							} 
							else if($grade>9) 
							{
								$params = array('is_main_error'=>'4');
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
							
							$is_valid_tracking_url = Validate::isAbsoluteUrl($tracking_url);
							if(!$is_valid_tracking_url) 
							{
								$params = array('is_main_error'=>'5');
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
							
							//check is new logo uploaded for shipping
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
										$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
										Tools::redirect($addnewshipping_link);
									} 
									else 
									{
										list($width, $height) = getimagesize($_FILES['shipping_logo']['tmp_name']);
										if($width >125 ||  $height >125) 
										{
											$params = array('is_main_error'=>'3');
											$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
											Tools::redirect($addnewshipping_link);
										}
										$is_new_image = true;
									}
								}
							}
							
							if(Tools::getValue('mpshipping_id')) 
							{
								$obj_mpshipping_method = new Mpshippingmethod(Tools::getValue('mpshipping_id'));
								$old_shipping_method = $obj_mpshipping_method->shipping_method;

								//check which type of shipping method chossen when we edit shipping is shipping calculate on the basis of product price or is shipping calculate on the basis of product weight
								if($old_shipping_method!=$shipping_method) {
									if($old_shipping_method==2) 
									{
										//obj for price
										$obj_range_obj = new Mprangeprice();
										$obj_range_obj->deleteRangeByMpshippingId(Tools::getValue('mpshipping_id'));
									}
									else if($old_shipping_method==1) 
									{
										//obj for weight
										$obj_range_obj = new Mprangeweight();
										$obj_range_obj->deleteRangeByMpshippingId(Tools::getValue('mpshipping_id'));
									}
								}
							} 
							else 
							{
								$obj_mpshipping_method = new Mpshippingmethod();
							}
							$obj_mpshipping_method->mp_shipping_name = $shipping_name;
							$obj_mpshipping_method->transit_delay = $transit_time;
							$obj_mpshipping_method->grade = $grade;
							$obj_mpshipping_method->shipping_method = $shipping_method;
							$obj_mpshipping_method->deleted = 0;
							$obj_mpshipping_method->mp_id_seller = $mp_id_seller;
							$obj_mpshipping_method->mp_id_shop = $mp_id_shop;
							$obj_mpshipping_method->ps_id_shop = $ps_id_shop;
							$obj_mpshipping_method->save();
							$mpshipping_id = $obj_mpshipping_method->id;
							$obj_mpshipping_method->id_reference = $mpshipping_id;
							$obj_mpshipping_method->save();
							
							if($is_new_image == true) 
							{
								$img_dir = 'modules/mpshipping/img/logo/';
								move_uploaded_file($_FILES['shipping_logo']['tmp_name'],$img_dir.$mpshipping_id.'.jpg');
							}
							$button_click = Tools::getValue('button_click');
							if($button_click=='finish') 
							{
								$params = array('su'=>'1');
								$sellershippinglist_link = $link->getModuleLink('mpshipping','sellershippinglist',$params);
								Tools::redirect($sellershippinglist_link);
							} 
							else 
							{
								$params = array('mpsp_id'=>$mpshipping_id,'step'=>'2');
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
						} 
						else if($submitaddcarrier==2) 
						{
							$zone_detail = Zone::getZones();
							$mpshipping_id = Tools::getValue('mpshipping_id');
							
							$range_inf = Tools::getValue('range_inf');
							$range_sup = Tools::getValue('range_sup');
							$is_free = Tools::getValue('is_free');
							$shipping_handling = Tools::getValue('shipping_handling');
							
							$obj_mpshipping_method = new Mpshippingmethod($mpshipping_id);
							$obj_mpshipping_method->is_free = $is_free;
							$obj_mpshipping_method->shipping_handling = $shipping_handling;
							if ($is_free)
								$obj_mpshipping_method->shipping_method = 2;
							$obj_mpshipping_method->save();
								

							$shipping_method = $obj_mpshipping_method->shipping_method;
							
							if ($shipping_method == 2) //obj for price 
								$obj_range_obj = new Mprangeprice();
							else if ($shipping_method == 1) //obj for weight
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
										$obj_range_obj->delimiter1 = $value;
										if($range_sup[$key] == "")
											$obj_range_obj->delimiter2 = (float)0;
										else
											$obj_range_obj->delimiter2 = $range_sup[$key];

										$obj_range_obj->mp_shipping_id = $mpshipping_id;

										//search range all ready available in table or not
										$is_available = $obj_range_obj->isRangeInTableByShippingId();
										if($is_available) 
										{
											$id_range = $is_available[0]['id'];
											$obj_mpshipping_del = new Mpshippingdelivery();
											foreach ($zone_detail as $zone) 
											{
												$id_zone = $zone['id_zone'];
												$delivery_id = $obj_mpshipping_del->getDeliveryId($id_zone,$mpshipping_id,$id_range,$shipping_method);
												
												$zone_fees = Tools::getValue('fees');
												if($delivery_id)
												{
													$obj_mpshipping_del->id = $delivery_id;
													$obj_mpshipping_del->mp_shipping_id  = $mpshipping_id;
													$obj_mpshipping_del->id_zone  = $id_zone;
													if($shipping_method == 2)
													{
														$obj_mpshipping_del->mp_id_range_price=$key;
														$obj_mpshipping_del->mp_id_range_weight=0;
													}
													else
													{
														$obj_mpshipping_del->mp_id_range_price=0;
														$obj_mpshipping_del->mp_id_range_weight=$key;
													}
													/*echo "blog 1";
													var_dump($zone_fees[$id_zone][$key]);
													die;*/
													$obj_mpshipping_del->base_price = (float)$zone_fees[$id_zone][$key];
													if (empty($obj_mpshipping_del->base_price))
														$obj_mpshipping_del->base_price = 0;

													$obj_mpshipping_del->save();
												}
											}
											//continue;
											//then update zone price if its change
										} 
										else 
										{
											$obj_range_obj->add();
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
													if($shipping_method == 2)
													{
														$obj_mpshipping_del->mp_id_range_price = $obj_range_obj->id;
														$obj_mpshipping_del->mp_id_range_weight = 0;
													}
													else if($shipping_method == 1)
													{
														$obj_mpshipping_del->mp_id_range_weight = $obj_range_obj->id;
														$obj_mpshipping_del->mp_id_range_price =0;
													}	
													$zone_fees = Tools::getValue('fees');
													/*echo "blog 2";
													var_dump($zone_fees[$zone_id][$key]);
													die;*/
													$obj_mpshipping_del->base_price = (float)$zone_fees[$zone_id][$key];
													if($obj_mpshipping_del->base_price == 'on' || $obj_mpshipping_del->base_price == "")
														$obj_mpshipping_del->base_price = 0;
													
													$obj_mpshipping_del->save();
												}
											}
										}
									}
								}
							}

							$button_click = Tools::getValue('button_click');
							if ($button_click == 'prev') 
							{
								$step = 1;
								$params = array('mpsp_id'=>$mpshipping_id,'step' => $step);
								$addnewshipping_link = $link->getModuleLink('mpshipping', 'addnewshipping', $params);
								Tools::redirect($addnewshipping_link);
							} 
							else if ($button_click == 'next') 
							{
								$step = 3;
								$params = array('mpsp_id'=>$mpshipping_id,'step' => $step);
								$addnewshipping_link = $link->getModuleLink('mpshipping', 'addnewshipping', $params);
								Tools::redirect($addnewshipping_link);
							} 
							else 
							{
								$params = array('su' => '1');
								$sellershippinglist_link = $link->getModuleLink('mpshipping', 'sellershippinglist', $params);
								Tools::redirect($sellershippinglist_link);
							}
							
						}
						else if($submitaddcarrier==3) 
						{
							//step 3 
							$mpshipping_id = Tools::getValue('mpshipping_id');
							$max_height =  Tools::getValue('max_height');
							$max_width =  Tools::getValue('max_width');
							$max_depth =  Tools::getValue('max_depth');
							$max_weight =  Tools::getValue('max_weight');
							
							//$is_valid_max_height = Validate::isNullOrUnsignedId($max_height);
							if($max_height == "")
								$max_height = (int)0;

							if(!Validate::isUnsignedInt($max_height))
							{
								$params = array('is_main_error'=>'6','mpsp_id'=>$mpshipping_id,'step'=>3);
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
							
							//$is_valid_max_width = Validate::isNullOrUnsignedId($max_width);
							if($max_width == "")
								$max_width = (int)0;

							if(!Validate::isUnsignedInt($max_width))
							{
								$params = array('is_main_error'=>'7','mpsp_id'=>$mpshipping_id,'step'=>3);
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
							
							//$is_valid_max_depth = Validate::isNullOrUnsignedId($max_depth);
							if($max_depth == "")
								$max_depth = (int)0;

							if(!Validate::isUnsignedInt($max_depth))
							{
								$params = array('is_main_error'=>'8','mpsp_id'=>$mpshipping_id,'step'=>3);
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
							
							//$is_valid_max_weight = Validate::isNullOrUnsignedId($max_weight);
							if($max_weight == "")
								$max_weight = (float)0;

							if(!Validate::isFloat($max_weight)) 
							{
								$params = array('is_main_error'=>'9','mpsp_id'=>$mpshipping_id,'step'=>3);
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							}
							
							$obj_mpshipping_method = new Mpshippingmethod($mpshipping_id);
							$obj_mpshipping_method->max_height = $max_height;
							$obj_mpshipping_method->max_width = $max_width;
							$obj_mpshipping_method->max_depth = $max_depth;
							$obj_mpshipping_method->max_weight = $max_weight;
							$obj_mpshipping_method->save();
							
							$button_click = Tools::getValue('button_click');
							if($button_click=='prev') 
							{
								$step=2;
								$params = array('mpsp_id'=>$mpshipping_id,'step'=>$step);
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							} 
							else if($button_click=='next') 
							{
								$step=4;
								$params = array('mpsp_id'=>$mpshipping_id,'step'=>$step);
								$addnewshipping_link = $link->getModuleLink('mpshipping','addnewshipping',$params);
								Tools::redirect($addnewshipping_link);
							} 
							else {
								$params = array('su'=>'1');
								$sellershippinglist_link = $link->getModuleLink('mpshipping','sellershippinglist',$params);
								Tools::redirect($sellershippinglist_link);
							}
							
						}
						else if ($submitaddcarrier == 4) 
						{
							//step 4
							$mpshipping_id = Tools::getValue('mpshipping_id');
							$obj_mpshipping_method = new Mpshippingmethod($mpshipping_id);
							$obj_mpshipping_method->is_done = 1;
							$obj_mpshipping_method->save();
							$params = array('su'=>'1');
							$sellershippinglist_link = $link->getModuleLink('mpshipping','sellershippinglist',$params);
							Tools::redirect($sellershippinglist_link);
						}
					} 
					else 
					{
						Tools::redirect($my_account_link);
					}
				} 
				else 
				{
					Tools::redirect($my_account_link);
				}
			}
			else 
			{
				Tools::redirect($my_account_link);
			}
		}
	}
?>