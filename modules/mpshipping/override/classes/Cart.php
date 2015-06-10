<?php 
include_once dirname(__FILE__).'/../../modules/marketplace/classes/MarketplaceClassInclude.php';
include_once dirname(__FILE__).'/../../modules/mpshipping/classes/Mpshippinginclude.php';
class Cart extends CartCore{

	public function getDeliveryOptionList(Country $default_country = null, $flush = false)
	{
		static $cache = null;
		
		if ($cache !== null && !$flush)
			return $cache;

		$delivery_option_list = array();
		$carriers_price = array();
		$carrier_collection = array();
		$mp_seelr_info = array();
		$mp_seelr_info_product = array();
		$impact_carrier = array();
		$mp_seelr_info_product_weight = array();
		$package_list = $this->getPackageList();
		//var_dump($package_list);
		// Foreach addresses
		$obj_seller_product = new SellerProductDetail();
		//var_dump($package_list);
		foreach ($package_list as $id_address => $packages)
		{
			//echo $this->id;
			// Initialize vars
			// var_dump($id_address);
			$delivery_option_list[$id_address] = array();
			$carriers_price[$id_address] = array();
			$common_carriers = null;
			$best_price_carriers = array();
			$best_grade_carriers = array();
			$carriers_instance = array();
			
			// Get country
			if ($id_address)
			{
				$address = new Address($id_address);
				$country = new Country($address->id_country);
			}
			else
				$country = $default_country;
			
			
			// Foreach packages, get the carriers with best price, best position and best grade
			foreach ($packages as $id_package => $package)
			{
				
				// No carriers available
				if (count($package['carrier_list']) == 1 && current($package['carrier_list']) == 0)
				{	
					$cache = array();
					return $cache;
				}

				$carriers_price[$id_address][$id_package] = array();
				$main_product_id = $package['product_list'][0]['id_product'];
				
				$seller_product_detail = $obj_seller_product->getMarketPlaceShopProductDetail($main_product_id);
				if($seller_product_detail) {
					$mp_product_id = $seller_product_detail['marketplace_seller_id_product'];
					$obj_new_seller_product = new SellerProductDetail($mp_product_id);
					if(!in_array($obj_new_seller_product->id_seller,$mp_seelr_info)) {
						$mp_seelr_info[] = $obj_new_seller_product->id_seller;
						$mp_seelr_info_product[$obj_new_seller_product->id_seller] = $obj_new_seller_product->price;
						$mp_seelr_info_product_weight[$obj_new_seller_product->id_seller] = $package['product_list'][0]['weight'];
					} else {
						$mp_seelr_info_product[$obj_new_seller_product->id_seller] = $mp_seelr_info_product[$obj_new_seller_product->id_seller]+$obj_new_seller_product->price;
						$mp_seelr_info_product_weight[$obj_new_seller_product->id_seller] += $package['product_list'][0]['weight'];
					}
				}
				
				// Get all common carriers for each packages to the same address
				if (is_null($common_carriers))
					$common_carriers = $package['carrier_list'];
				else
					$common_carriers = array_intersect($common_carriers, $package['carrier_list']);

				$best_price = null;
				$best_price_carrier = null;
				$best_grade = null;
				$best_grade_carrier = null;

				// Foreach carriers of the package, calculate his price, check if it the best price, position and grade
				foreach ($package['carrier_list'] as $id_carrier)
				{
					if (!isset($carriers_instance[$id_carrier]))
						$carriers_instance[$id_carrier] = new Carrier($id_carrier);

					$price_with_tax = $this->getPackageShippingCost($id_carrier, true, $country, $package['product_list']);
					$price_without_tax = $this->getPackageShippingCost($id_carrier, false, $country, $package['product_list']);
					if (is_null($best_price) || $price_with_tax < $best_price)
					{
						$best_price = $price_with_tax;
						$best_price_carrier = $id_carrier;
					}
					$carriers_price[$id_address][$id_package][$id_carrier] = array(
						'without_tax' => $price_without_tax,
						'with_tax' => $price_with_tax);

					$grade = $carriers_instance[$id_carrier]->grade;
					if (is_null($best_grade) || $grade > $best_grade)
					{
						$best_grade = $grade;
						$best_grade_carrier = $id_carrier;
					}
				}

				$best_price_carriers[$id_package] = $best_price_carrier;
				$best_grade_carriers[$id_package] = $best_grade_carrier;
			}

			// Reset $best_price_carrier, it's now an array
			$best_price_carrier = array();
			$key = '';

			// Get the delivery option with the lower price
			foreach ($best_price_carriers as $id_package => $id_carrier)
			{
				$key .= $id_carrier.',';
				if (!isset($best_price_carrier[$id_carrier]))
					$best_price_carrier[$id_carrier] = array(
						'price_with_tax' => 0,
						'price_without_tax' => 0,
						'package_list' => array(),
						'product_list' => array(),
					);
				$best_price_carrier[$id_carrier]['price_with_tax'] += $carriers_price[$id_address][$id_package][$id_carrier]['with_tax'];
				$best_price_carrier[$id_carrier]['price_without_tax'] += $carriers_price[$id_address][$id_package][$id_carrier]['without_tax'];
				$best_price_carrier[$id_carrier]['package_list'][] = $id_package;
				$best_price_carrier[$id_carrier]['product_list'] = array_merge($best_price_carrier[$id_carrier]['product_list'], $packages[$id_package]['product_list']);
				$best_price_carrier[$id_carrier]['instance'] = $carriers_instance[$id_carrier];
			}

			// Add the delivery option with best price as best price
			$delivery_option_list[$id_address][$key] = array(
				'carrier_list' => $best_price_carrier,
				'is_best_price' => true,
				'is_best_grade' => false,
				'unique_carrier' => (count($best_price_carrier) <= 1)
			);

			// Reset $best_grade_carrier, it's now an array
			$best_grade_carrier = array();
			$key = '';

			// Get the delivery option with the best grade
			foreach ($best_grade_carriers as $id_package => $id_carrier)
			{
				$key .= $id_carrier.',';
				if (!isset($best_grade_carrier[$id_carrier]))
					$best_grade_carrier[$id_carrier] = array(
						'price_with_tax' => 0,
						'price_without_tax' => 0,
						'package_list' => array(),
						'product_list' => array(),
					);
				$best_grade_carrier[$id_carrier]['price_with_tax'] += $carriers_price[$id_address][$id_package][$id_carrier]['with_tax'];
				$best_grade_carrier[$id_carrier]['price_without_tax'] += $carriers_price[$id_address][$id_package][$id_carrier]['without_tax'];
				$best_grade_carrier[$id_carrier]['package_list'][] = $id_package;
				$best_grade_carrier[$id_carrier]['product_list'] = array_merge($best_grade_carrier[$id_carrier]['product_list'], $packages[$id_package]['product_list']);
				$best_grade_carrier[$id_carrier]['instance'] = $carriers_instance[$id_carrier];
			}
			
			// Add the delivery option with best grade as best grade
			if (!isset($delivery_option_list[$id_address][$key]))
				$delivery_option_list[$id_address][$key] = array(
					'carrier_list' => $best_grade_carrier,
					'is_best_price' => false,
					'unique_carrier' => (count($best_grade_carrier) <= 1)
				);
			$delivery_option_list[$id_address][$key]['is_best_grade'] = true;

			// Get all delivery options with a unique carrier
			foreach ($common_carriers as $id_carrier)
			{
				$price = 0;
				$key = '';
				$package_list = array();
				$product_list = array();
				$total_price_with_tax = 0;
				$total_price_without_tax = 0;
				$price_with_tax = 0;
				$price_without_tax = 0;

				foreach ($packages as $id_package => $package)
				{
					$key .= $id_carrier.',';
					$price_with_tax += $carriers_price[$id_address][$id_package][$id_carrier]['with_tax'];
					$price_without_tax += $carriers_price[$id_address][$id_package][$id_carrier]['without_tax'];
					$package_list[] = $id_package;
					$product_list = array_merge($product_list, $package['product_list']);
				}

				if (!isset($delivery_option_list[$id_address][$key]))
					$delivery_option_list[$id_address][$key] = array(
						'is_best_price' => false,
						'is_best_grade' => false,
						'unique_carrier' => true,
						'carrier_list' => array(
							$id_carrier => array(
								'price_with_tax' => $price_with_tax,
								'price_without_tax' => $price_without_tax,
								'instance' => $carriers_instance[$id_carrier],
								'package_list' => $package_list,
								'product_list' => $product_list,
							)
						)
					);
				else
					$delivery_option_list[$id_address][$key]['unique_carrier'] = (count($delivery_option_list[$id_address][$key]['carrier_list']) <= 1);
			}
		}

		$cart_rules = CartRule::getCustomerCartRules(Context::getContext()->cookie->id_lang, Context::getContext()->cookie->id_customer, true, true, false, $this);
		$result = Db::getInstance('SELECT * FROM '._DB_PREFIX_.'cart_cart_rule WHERE id_cart='.$this->id);
		$cart_rules_in_cart = array();

		if (is_array($result) && count($result))
			foreach ($result as $row)
				$cart_rules_in_cart[] = $row['id_cart_rule'];

		$total_products_wt = $this->getOrderTotal(true, Cart::ONLY_PRODUCTS);
		$total_products = $this->getOrderTotal(false, Cart::ONLY_PRODUCTS);

		$free_carriers_rules = array();

		foreach ($cart_rules as $cart_rule)
		{
			$total_price = $cart_rule['minimum_amount_tax'] ? $total_products_wt : $total_products;
			$total_price += $cart_rule['minimum_amount_tax'] && $cart_rule['minimum_amount_shipping'] ? $real_best_price : 0;
			$total_price += !$cart_rule['minimum_amount_tax'] && $cart_rule['minimum_amount_shipping'] ? $real_best_price_wt : 0;
			if ($cart_rule['free_shipping'] && $cart_rule['carrier_restriction'] && $cart_rule['minimum_amount'] <= $total_price)
			{
				$cr = new CartRule((int)$cart_rule['id_cart_rule']);
				if (Validate::isLoadedObject($cr) &&
					$cr->checkValidity(Context::getContext(), in_array((int)$cart_rule['id_cart_rule'], $cart_rules_in_cart), false, false))
				{
					$carriers = $cr->getAssociatedRestrictions('carrier', true, false);
					if (is_array($carriers) && count($carriers) && isset($carriers['selected']))
						foreach ($carriers['selected'] as $carrier)
							if (isset($carrier['id_carrier']) && $carrier['id_carrier'])
								$free_carriers_rules[] = (int)$carrier['id_carrier'];
				}
			}
		}

		// For each delivery options :
		//    - Set the carrier list
		//    - Calculate the price
		//    - Calculate the average position
		//var_dump($delivery_option_list);
		foreach ($delivery_option_list as $id_address => $delivery_option)
			foreach ($delivery_option as $key => $value)
			{
				if($id_address!=0) {
					$address_obj = new Address($id_address);
					$id_country = $address_obj->id_country;
					$id_state = $address_obj->id_state;
					$obj_country = new Country($id_country);
					$id_zone = $obj_country->id_zone;
				} 
					$total_price_with_tax = 0;
					$total_price_without_tax = 0;
					$position = 0;
					foreach ($value['carrier_list'] as $id_carrier => $data)
					{
						
						$ps_id_carrier = $id_carrier;

						$obj_mp_shipping_map = new Mpshippingmap();
						$mpshipping_id = $obj_mp_shipping_map->getMpshippingId($ps_id_carrier);
						if($mpshipping_id) {
							$obj_mp_shiiping = new Mpshippingmethod($mpshipping_id);
							$shipping_method = $obj_mp_shiiping->shipping_method;
							$mp_id_seller = $obj_mp_shiiping->mp_id_seller;
							$ps_id_shop =  $obj_mp_shiiping->ps_id_shop;
							if($shipping_method==2) 
							{
								if(isset($mp_seelr_info_product[$mp_id_seller])) 
								{
									$total_product_price = $mp_seelr_info_product[$mp_id_seller];
									$obj_mp_range = new Mprangeprice();
									$range_id = $obj_mp_range->findRangeIdBetweenDelimetr($mpshipping_id,$total_product_price);
								}
							} 
							else 
							{
								if(isset($mp_seelr_info_product_weight[$mp_id_seller])) 
								{
									$total_weight = $mp_seelr_info_product_weight[$mp_id_seller];
									$obj_mp_range = new Mprangeweight();
									$range_id = $obj_mp_range->findRangeIdBetweenDelimetr($mpshipping_id,$total_weight);
								}
								
							}
							
							if(isset($range_id) && $range_id) 
							{
								$obj_mp_ship_del = new Mpshippingdelivery();
								if(isset($id_zone)) 
								{
									$delivery_id = $obj_mp_ship_del->getDeliveryId($id_zone,$mpshipping_id,$range_id,$shipping_method);
									if($delivery_id) 
									{
										$obj_mp_impact = new Mpshippingimpact();
										$is_in_impact = $obj_mp_impact->isAllReadyInImpact($mpshipping_id,$delivery_id,$id_zone,$id_country,$id_state);
										
										if(!$is_in_impact) {
											$is_in_impact = $obj_mp_impact->isAllReadyInImpact($mpshipping_id,$delivery_id,$id_zone,$id_country,0);

											if($is_in_impact) 
											{
												$impact_carrier[$ps_id_carrier] = $is_in_impact['impact_price'];
												$total_price_with_tax += $data['price_with_tax']+$is_in_impact['impact_price'];
												$total_price_without_tax += $data['price_without_tax']+$is_in_impact['impact_price'];
											} 
											else 
											{
												$total_price_with_tax += $data['price_with_tax'];
												$total_price_without_tax += $data['price_without_tax'];
											}
										} 
										else 
										{
											$total_price_with_tax += $data['price_with_tax']+$is_in_impact['impact_price'];
											$total_price_without_tax += $data['price_without_tax']+$is_in_impact['impact_price'];
										}
									} 
									else 
									{
										$total_price_with_tax += $data['price_with_tax'];
										$total_price_without_tax += $data['price_without_tax'];
									}
								} 
								else 
								{
									$total_price_with_tax += $data['price_with_tax'];
									$total_price_without_tax += $data['price_without_tax'];
								}
								unset($range_id);
							} 
							else 
							{
								$total_price_with_tax += $data['price_with_tax'];
								$total_price_without_tax += $data['price_without_tax'];
							}					
						} 
						else 
						{
							$total_price_with_tax += $data['price_with_tax'];
							$total_price_without_tax += $data['price_without_tax'];
						}
						// $total_price_with_tax += $data['price_with_tax'];
						// $total_price_without_tax += $data['price_without_tax'];

						if (!isset($carrier_collection[$id_carrier]))
							$carrier_collection[$id_carrier] = new Carrier($id_carrier);
						$delivery_option_list[$id_address][$key]['carrier_list'][$id_carrier]['instance'] = $carrier_collection[$id_carrier];

						if (file_exists(_PS_SHIP_IMG_DIR_.$id_carrier.'.jpg'))
							$delivery_option_list[$id_address][$key]['carrier_list'][$id_carrier]['logo'] = _THEME_SHIP_DIR_.$id_carrier.'.jpg';
						else
							$delivery_option_list[$id_address][$key]['carrier_list'][$id_carrier]['logo'] = false;
						
						$position += $carrier_collection[$id_carrier]->position;
					}		
				$total_price_without_tax_with_rules = (in_array($id_carrier, $free_carriers_rules)) ? 0 : $total_price_without_tax ;

				$delivery_option_list[$id_address][$key]['total_price_with_tax'] = $total_price_with_tax;
				$delivery_option_list[$id_address][$key]['total_price_without_tax'] = $total_price_without_tax;
				$delivery_option_list[$id_address][$key]['is_free'] = !$total_price_without_tax_with_rules ? true : false;
				$delivery_option_list[$id_address][$key]['position'] = $position / count($value['carrier_list']);
			}

		if(!empty($impact_carrier)) 
		{
			foreach($impact_carrier as $key=>$imp_car) 
			{
				$ps_id_car = $key;
				$extra_cost = $imp_car;
				$ps_id_cart = $this->id;
				$obj_mp_cart = new Mpshippingcart();
				$is_available = $obj_mp_cart->isAvailable($ps_id_car,$ps_id_cart);
				if(!$is_available) 
				{
					$obj_mp_cart->ps_id_carrier = $ps_id_car;
					$obj_mp_cart->ps_id_cart = $ps_id_cart;
					$obj_mp_cart->extra_cost = $extra_cost;
					$obj_mp_cart->save();
				} 
				else 
				{
					if($extra_cost!=$is_available['extra_cost']) 
					{
						$obj_mp_cart->id = $is_available['id'];
						$obj_mp_cart->ps_id_carrier = $ps_id_car;
						$obj_mp_cart->ps_id_cart = $ps_id_cart;
						$obj_mp_cart->extra_cost = $extra_cost;
						$obj_mp_cart->save();
					}
				}
			}
		}
		// Sort delivery option list
		foreach ($delivery_option_list as &$array)
			uasort ($array, array('Cart', 'sortDeliveryOptionList'));
		
		//var_dump($delivery_option_list);
		$cache = $delivery_option_list;
		return $delivery_option_list;
	}
}
?>