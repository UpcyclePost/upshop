<?php
	class Mpshippingmap extends ObjectModel {
		public $id;	
		public $mp_shipping_id;
		public $ps_id_carriers;
		public $date_add;		
		public $date_upd;
		
		public static $definition = array(
			'table' => 'mp_shipping_map',
			'primary' => 'id',
			'fields' => array(
				'mp_shipping_id' => array('type' => self::TYPE_INT ,'validate' => 'isUnsignedInt', 'required' => true),
				'ps_id_carriers' => array('type' => self::TYPE_INT ,'validate' => 'isUnsignedInt', 'required' => true),
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
		
		public function isAllreadyMapByShippingID($mp_shipping_id) {
			$is_mapped = Db::getInstance()->getRow('select * from `'._DB_PREFIX_.'mp_shipping_map` where mp_shipping_id='.$mp_shipping_id);
			if(empty($is_mapped)) {
				return false;
			} else {
				return $is_mapped;
			}
		}
		public function getCarrierId($mp_shipping_id){
		$carrier_id = Db::getInstance()->getValue('select `ps_id_carriers` from `'._DB_PREFIX_.'mp_shipping_map` where mp_shipping_id='.$mp_shipping_id);
		if(empty($carrier_id)) {
				return false;
			} else {
				return $carrier_id;
			}
		}
		
		public function getMpshippingId($ps_id_carriers) {
			$mp_shipping_id = Db::getInstance()->getValue('select `mp_shipping_id` from `'._DB_PREFIX_.'mp_shipping_map` where ps_id_carriers='.$ps_id_carriers);
			if(empty($mp_shipping_id)) {
					return false;
				} else {
					return $mp_shipping_id;
				}
		}
	}