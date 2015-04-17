<?php
	class Mprangeprice extends ObjectModel 
	{
		public $id;	
		public $mp_shipping_id;
		public $delimiter1;
		public $delimiter2;			
		public $date_add;		
		public $date_upd;		
		
		public static $definition = array(
			'table' => 'mp_range_price',
			'primary' => 'id',
			'fields' => array(
				'mp_shipping_id' => array('type' => self::TYPE_INT, 'required' => true),
				'delimiter1' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
				'delimiter2' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
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
		
		public function getAllRangeAccordingToShippingId() {
			$is_range = Db::getInstance()->executeS("select `id` as id_range,delimiter1,delimiter2 from "._DB_PREFIX_."mp_range_price where mp_shipping_id=".$this->mp_shipping_id);
			if(empty($is_range)) {
				return false;
			} else {
				return $is_range;
			}
		}
		
		public function isRangeInTableByShippingId() 
		{
			$is_range = Db::getInstance()->executeS("select * from "._DB_PREFIX_."mp_range_price where mp_shipping_id=".$this->mp_shipping_id." and delimiter1=".$this->delimiter1." and delimiter2=".$this->delimiter2);
			if(empty($is_range)) {
				return false;
			} else {
				return $is_range;
			}
		}
		
		public function findRangeIdBetweenDelimetr($mp_shipping_id,$price) {
			$mp_range_id = Db::getInstance()->getRow("select `id` from `"._DB_PREFIX_."mp_range_price` where mp_shipping_id=".$mp_shipping_id." and ".$price." BETWEEN  `delimiter1` AND  `delimiter2` ");
			if(empty($mp_range_id)) {
				return false;
			} else {
				return $mp_range_id['id'];
			}
		}
		
		public function deleteRangeByMpshippingId($mp_shipping_id) {
			return Db::getInstance()->delete('mp_range_price','mp_shipping_id='.$mp_shipping_id);
		}
	}