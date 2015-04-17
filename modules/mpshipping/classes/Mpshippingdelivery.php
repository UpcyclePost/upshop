<?php
	class Mpshippingdelivery extends ObjectModel {
		public $id;	
		public $mp_shipping_id;
		public $id_zone;
		public $mp_id_range_price;
		public $mp_id_range_weight;			
		public $base_price=0;		
		public $date_add;		
		public $date_upd;		
		

		public static $definition = array(
			'table' => 'mp_shipping_delivery',
			'primary' => 'id',
			'fields' => array(
				'mp_shipping_id' => array('type' => self::TYPE_INT, 'required' => true),
				'id_zone' => array('type' => self::TYPE_INT, 'required' => true),
				'mp_id_range_price' => array('type' => self::TYPE_INT, 'required' => true),
				'mp_id_range_weight' => array('type' => self::TYPE_INT, 'required' => true),
				'base_price' => array('type' => self::TYPE_FLOAT,'validate' => 'isPrice', 'required' => true),
				'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
				'date_upd' =>array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
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
		
		public function getDliveryMethodForPriceRange($id_zone,$mp_shipping_id) {
			$delivery_emthod = 	Db::getInstance()->executeS('
			SELECT msd.`id`,round(mrp.`delimiter1`,2) as delimiter1,round(mrp.`delimiter2`,2) as delimiter2,mrp.`id` as id_range
			FROM `'._DB_PREFIX_.'mp_shipping_delivery` msd left join
			`'._DB_PREFIX_.'mp_range_price` mrp on (mrp.`id`=msd.`mp_id_range_price`)
			 WHERE msd.`mp_shipping_id` = '.(int)$mp_shipping_id.' and msd.`id_zone`='.(int)$id_zone.' and msd.mp_id_range_weight=0'
			);
			
			if(empty($delivery_emthod)) {
				return false;
			} else {
				return $delivery_emthod;
			}
		}
		
		public function getDliveryMethodForWeightRange($id_zone,$mp_shipping_id) {
			$delivery_emthod = 	Db::getInstance()->executeS('
			SELECT msd.`id`,round(mrw.`delimiter1`,2) as delimiter1,round(mrw.`delimiter2`,2) as delimiter2,mrw.`id` as id_range 
			FROM `'._DB_PREFIX_.'mp_shipping_delivery` msd left join
			`'._DB_PREFIX_.'mp_range_weight` mrw on (mrw.`id`= msd.`mp_id_range_weight`)
			WHERE msd.`mp_shipping_id` = '.(int)$mp_shipping_id.' and msd.`id_zone`='.(int)$id_zone.' and msd.mp_id_range_price=0'
			);
			
			if(empty($delivery_emthod)) {
				return false;
			} else {
				return $delivery_emthod;
			}
		}
		
		public function getIdZoneByShiipingId($mp_shipping_id) {
			$id_zone_detail = 	Db::getInstance()->executeS('select distinct(`id_zone`)  from `'._DB_PREFIX_.'mp_shipping_delivery` where mp_shipping_id='.$mp_shipping_id);
			
			if(empty($id_zone_detail)) {
				return false;
			} else {
				return $id_zone_detail;
			}
		}
		
		public function getDeliveryDetailByShiipingId($mp_shipping_id) {
			$delivery_detail = 	Db::getInstance()->executeS('select *  from `'._DB_PREFIX_.'mp_shipping_delivery` where mp_shipping_id='.$mp_shipping_id);
			
			if(empty($delivery_detail)) {
				return false;
			} else {
				return $delivery_detail;
			}
		}
		
		public function getDeliveryBySIdAndRpId($mp_shipping_id,$mp_id_range_price) {
			$delivery_detail = 	Db::getInstance()->executeS('select *  from `'._DB_PREFIX_.'mp_shipping_delivery` where mp_shipping_id='.$mp_shipping_id." and mp_id_range_price=".$mp_id_range_price);
			
			
			if(empty($delivery_detail)) {
				return false;
			} else {
				return $delivery_detail;
			}
		}
		
		
		public function getDeliveryBySIdAndRwId($mp_shipping_id,$mp_id_range_weight) {
			$delivery_detail = 	Db::getInstance()->executeS('select *  from `'._DB_PREFIX_.'mp_shipping_delivery` where mp_shipping_id='.$mp_shipping_id." and mp_id_range_weight=".$mp_id_range_weight);
			
			
			if(empty($delivery_detail)) {
				return false;
			} else {
				return $delivery_detail;
			}
		}
		//by id_zone,mps_shipping_id,id_range
		public function getDeliveryId($id_zone,$mp_shipping_id,$id_range,$shipping_method) {
			if($shipping_method==1)
				$deliver_id_detail = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('select `id`  from `'._DB_PREFIX_.'mp_shipping_delivery` where mp_shipping_id='.$mp_shipping_id.' and mp_id_range_weight='.$id_range.' and id_zone='.$id_zone);
			else 
				$deliver_id_detail = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('select `id`  from `'._DB_PREFIX_.'mp_shipping_delivery` where mp_shipping_id='.$mp_shipping_id.' and mp_id_range_price='.$id_range.' and id_zone='.$id_zone);
			
			
			if(empty($deliver_id_detail))
				return false;
			else
				return $deliver_id_detail['id'];
		}
	}