<?php
	class Mpshippingmethod extends ObjectModel 
	{
		public $id;	
		public $mp_shipping_name;
		public $transit_delay;
		public $shipping_method;
		public $is_free;
		public $deleted;			
		public $id_reference;		
		public $tracking_url;		
		public $max_width;		
		public $max_height;		
		public $max_depth;		
		public $max_weight;		
		public $grade;
		public $mp_id_seller;		
		public $mp_id_shop;		
		public $ps_id_shop;		
		public $date_add;		
		public $date_upd;		
		public $active;		
		public $is_done=0;
		public $shipping_handling;
		public $shipping_policy;
		
		public static $definition = array(
			'table' => 'mp_shipping_method',
			'primary' => 'id',
			'fields' => array(
				'id_reference' => array('type' => self::TYPE_INT),
				'mp_shipping_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCarrierName', 'required' => true, 'size' => 64),
				'transit_delay' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
				'shipping_method' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
				'is_free' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
				'deleted' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
				'tracking_url' => array('type' => self::TYPE_STRING, 'validate' => 'isAbsoluteUrl'),
				'max_width' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
				'max_height' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
				'max_depth' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
				'max_weight' =>array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
				'grade' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'size' => 1),
				'mp_id_seller' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
				'mp_id_shop' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
				'ps_id_shop' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
				'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
				'date_upd' =>array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
				'active' => array('type' => self::TYPE_BOOL),
				'is_done' => array('type' => self::TYPE_BOOL),
				'shipping_handling' => array('type' => self::TYPE_BOOL),
				'shipping_policy' => array('type' => self::TYPE_STRING),
			),
		);
	
	
		public function add($autodate = true, $null_values = false)
		{
			if (!parent::add($autodate, $null_values))
				return false;
			return Db::getInstance()->Insert_ID();
		}
	
		public function update($null_values = false)
		{
			Cache::clean('getContextualValue_'.$this->id.'_*');
			$success = parent::update($null_values);
			return $success;
		}

		public function delete()
		{
			return parent::delete();
		}

		public function deleteMpShipping($id_shipping)
		{
			$obj_mp_shipping = new Mpshippingmethod($id_shipping);
			$obj_mp_shipping_map = new Mpshippingmap();
			$obj_mp_ship_product_map = new Mpshippingproductmap();
			$id_carrier = $obj_mp_shipping_map->getCarrierId($id_shipping);
			if ($id_carrier)
			{
				$obj_carrier = new Carrier($id_carrier);
				$del = Db::getInstance()->update('carrier', array('deleted' => 1), 'id_reference = '.$obj_carrier->id_reference);
				//$del = $obj_carrier->delete();
				if ($del)
				{
					$obj_mp_shipping->delete();
					Db::getInstance()->delete('mp_shipping_map', 'mp_shipping_id = '.$id_shipping);
				}
			}
			else
			{
				$obj_mp_shipping->delete();
			}

			if ($obj_mp_ship_product_map->getMpShippingForProducts($id_shipping))
			{
				Db::getInstance()->delete('mp_shipping_product_map', 'mp_shipping_id = '.$id_shipping);
			}
		}
		
		public function getAllShippingMethod($mp_id_shop) {
			$mp_shipping_detail = Db::getInstance()->executeS('
			SELECT * FROM `'._DB_PREFIX_.'mp_shipping_method` 
			WHERE `mp_id_shop` = '.(int)$mp_id_shop.''
			);
			if(empty($mp_shipping_detail)) {
				return false;
			} else {
				return $mp_shipping_detail;
			}
		}
		
		public function getAllShippingMethodNotDelete($mp_id_shop,$delete,$is_done=1) {
			$mp_shipping_detail = Db::getInstance()->executeS('
			SELECT msm.*, msd.base_price FROM `'._DB_PREFIX_.'mp_shipping_method` as msm
			JOIN `'._DB_PREFIX_.'mp_shipping_delivery` msd on (msd.mp_shipping_id = msm.id)
			WHERE `mp_id_shop` = '.(int)$mp_id_shop.' and msd.id_zone = 2 and deleted='.$delete.' and is_done='.$is_done		
			);
			if(empty($mp_shipping_detail)) {
				return false;
			} else {
				return $mp_shipping_detail;
			}
		}
		
		public function addToCarrier($mp_shipping_id,$id_reference=false) 
		{
			$obj_mp_shipping = new Mpshippingmethod($mp_shipping_id);
			$obj_carrier = new Carrier();
			$obj_carrier->name = $obj_mp_shipping->mp_shipping_name;
			$obj_carrier->active = $obj_mp_shipping->active;
			$obj_carrier->url = $obj_mp_shipping->tracking_url;
			$obj_carrier->position = Carrier::getHigherPosition() + 1;
			$obj_carrier->shipping_method = $obj_mp_shipping->shipping_method;
			$obj_carrier->max_width = $obj_mp_shipping->max_width;
			$obj_carrier->max_height = $obj_mp_shipping->max_height;
			$obj_carrier->max_depth = $obj_mp_shipping->max_depth;
			$obj_carrier->max_weight = $obj_mp_shipping->max_weight;
			$obj_carrier->grade = $obj_mp_shipping->grade;
			$obj_carrier->shipping_handling = $obj_mp_shipping->shipping_handling;

			if ($obj_mp_shipping->is_free)
					$obj_carrier->is_free = 1;
			// delay
			foreach (Language::getLanguages(true) as $lang){
				$obj_carrier->delay[$lang['id_lang']] = $obj_mp_shipping->transit_delay;
			}
			$obj_carrier->save();
			$id_carrier = $obj_carrier->id;
			
			if($obj_mp_shipping->shipping_method==2) 
			{
				//range price
				$this->addRangePrice($mp_shipping_id,$id_carrier,$obj_mp_shipping->ps_id_shop);
			}
			else
			{
				//range weight
				$this->addRangeWeight($mp_shipping_id,$id_carrier,$obj_mp_shipping->ps_id_shop);
			}
			
			$this->addZone($mp_shipping_id,$id_carrier);
			$this->updateZoneShop($obj_mp_shipping->ps_id_shop,$id_carrier);
			$this->addCarrierTaxRule($obj_mp_shipping->ps_id_shop,$id_carrier);
			
			$this->addCarrierGroup($obj_mp_shipping->ps_id_shop,$id_carrier);
			
			if($id_reference)
			{
				$new_obj_carrier = new Carrier($id_carrier);
				$new_obj_carrier->id_reference = $id_reference;
				$new_obj_carrier->save();
			}
			return $id_carrier;
		}
		
		public function addRangePrice($mp_shipping_id,$id_carrier,$ps_id_shop) {
			$obj_mp_range = new Mprangeprice();
			$obj_mp_range->mp_shipping_id = $mp_shipping_id;
			$range_detail_info = $obj_mp_range->getAllRangeAccordingToShippingId();
			if($range_detail_info) 
			{
				foreach($range_detail_info as $range_detail) 
				{
					Db::getInstance()->insert('range_price', array(
											'id_carrier' => (int)$id_carrier,
											'delimiter1' => $range_detail['delimiter1'],
											'delimiter2' => $range_detail['delimiter2']
										));
					$range_price_insert_id = Db::getInstance()->Insert_ID();
					$this->addDelivery($id_carrier,$ps_id_shop,$mp_shipping_id,$range_price_insert_id,$range_detail['id_range'],true);
				}
			}
			return true;
		}
		
		public function addRangeWeight($mp_shipping_id,$id_carrier,$ps_id_shop) {
			$obj_mp_range = new Mprangeweight();
			$obj_mp_range->mp_shipping_id = $mp_shipping_id;
			$range_detail_info = $obj_mp_range->getAllRangeAccordingToShippingId();
			if($range_detail_info) 
			{
				foreach($range_detail_info as $range_detail) 
				{
					Db::getInstance()->insert('range_weight', array(
											'id_carrier' => (int)$id_carrier,
											'delimiter1' => $range_detail['delimiter1'],
											'delimiter2' => $range_detail['delimiter2']
										));
					$range_weight_insert_id = Db::getInstance()->Insert_ID();
					$this->addDelivery($id_carrier,$ps_id_shop,$mp_shipping_id,$range_weight_insert_id,$range_detail['id_range'],false);
				}
			}
			return true;
		}
		
		public function addZone($mp_shipping_id,$id_carrier) {
			$obj_mp_del = new Mpshippingdelivery(); 
			$id_zone_detail = $obj_mp_del->getIdZoneByShiipingId($mp_shipping_id);
			
			if($id_zone_detail) {
				foreach($id_zone_detail as $id_zo_det) {
					 Db::getInstance()->insert('carrier_zone', array(
										'id_carrier' => (int)$id_carrier,
										'id_zone' => (int)$id_zo_det['id_zone']
									));
				}
			}
			
			return true;
		}
		
		public function updateZoneShop($ps_id_shop,$id_carrier) {
			return Db::getInstance()->update('carrier_shop', array('id_shop' =>$ps_id_shop),'id_carrier ="'.$id_carrier.'" ');
		}
		
		public function addCarrierTaxRule($ps_id_shop,$id_carrier) 
		{
			 Db::getInstance()->insert('carrier_tax_rules_group_shop', array(
										'id_carrier' => (int)$id_carrier,
										'id_tax_rules_group' => 0,
										'id_shop' => (int)$ps_id_shop
									));
		}
		
		public function addCarrierGroup($ps_id_shop,$id_carrier) {
			$group_detail = Group::getGroups(1);
			foreach($group_detail as $group_det) {
				Db::getInstance()->insert('carrier_group', array(
										'id_carrier' => (int)$id_carrier,
										'id_group' => $group_det['id_group'],
									));
			}
			return true;
		}
		
		public function addDelivery($id_carrier,$ps_id_shop,$mp_shipping_id,$id_range,$mp_id_range,$is_price_range=false) 
		{
			$obj_mpshipping_del = new Mpshippingdelivery();
			
			if($is_price_range) 
			{
				$delivery_detail_info = $obj_mpshipping_del->getDeliveryBySIdAndRpId($mp_shipping_id,$mp_id_range);
				
				if($delivery_detail_info) {
					foreach($delivery_detail_info as $delivery_detail) {
						Db::getInstance()->insert('delivery', array(
											'id_carrier' => $id_carrier,
											'id_range_price' => $id_range,
											'id_zone' => $delivery_detail['id_zone'],
											'price' => $delivery_detail['base_price'],
										));
					}
				}
			} 
			else
			 {
				$delivery_detail_info = $obj_mpshipping_del->getDeliveryBySIdAndRwId($mp_shipping_id,$mp_id_range);
				if($delivery_detail_info) {
					foreach($delivery_detail_info as $delivery_detail) {
						Db::getInstance()->insert('delivery', array(
											'id_carrier' => $id_carrier,
											'id_range_weight' => $id_range,
											'id_zone' => $delivery_detail['id_zone'],
											'price' => $delivery_detail['base_price'],
										));
					}
				}
			}
			return true;
		}
		public function getMpShippingMethods($mp_id_shop){
			$mp_shipping_data = Db::getInstance()->executeS('
			SELECT * FROM `'._DB_PREFIX_.'mp_shipping_method` 
			WHERE `mp_id_shop` = '.(int)$mp_id_shop.' and `deleted` = 0 and `active` = 1
			');
			if(empty($mp_shipping_data)) {
				return false;
			} else {
				return $mp_shipping_data;
			}
		
		}
		public function getMpShippingPsShopId($mp_shipping_id){
			$ps_shop_id = Db::getInstance()->getValue('Select `ps_id_shop` from `'._DB_PREFIX_.'mp_shipping_method` where `id` = '.$mp_shipping_id.'');
			if(empty($ps_shop_id)) 
			{
				return false;
			} 
			else 
			{
				return $ps_shop_id;
			}
		}
		public function insertProductCarrierDetails($id_product,$id_carrier_reference,$id_shop){
		$ps_product_carrier_details = Db::getInstance()->insert('product_carrier', array(
											'id_product' => $id_product,
											'id_carrier_reference' => $id_carrier_reference,
											'id_shop' => $id_shop
											
										));
		return $ps_product_carrier_details;
		
		
		}

		public function getAdminShippingMethods(){
			$ps_carriers = Db::getInstance()->executeS('
			SELECT * FROM `'._DB_PREFIX_.'carrier` 
			WHERE `deleted` = 0 and `active` = 1
			');
			if(empty($ps_carriers)) {
				return false;
			} else {
				return $ps_carriers;
			}
		}
		public function getAllProducts($mp_id_seller){
			$mp_products = Db::getInstance()->executeS('
			SELECT * FROM `'._DB_PREFIX_.'marketplace_seller_product` 
			WHERE `id_seller` = '.$mp_id_seller
			);
			if(empty($mp_products)) {
				return false;
			} else {
				return $mp_products;
			}
		}
	}