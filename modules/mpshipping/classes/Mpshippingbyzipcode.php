<?php
	class Mpshippingbyzipcode extends ObjectModel {
		public $id;	
		public $mp_shipping_id;
		public $id_zone;
		public $delimiter1;
		public $delimiter2;			
		public $date_add;		
		public $date_upd;		
		/**
	 * @see ObjectModel::$definition
	 */
		public static $definition = array(
			'table' => 'mp_shipping_byzipcode',
			'primary' => 'id',
			'fields' => array(
				'mp_shipping_id' => array('type' => self::TYPE_INT, 'required' => true),
				'id_zone' => array('type' => self::TYPE_INT, 'required' => true),
				'id_country' => array('type' => self::TYPE_INT, 'required' => true),
				'id_state' => array('type' => self::TYPE_INT, 'required' => true),
				'zipcode_to' => array('type' => self::TYPE_INT, 'required' => true),
				'zipcode_from' => array('type' => self::TYPE_INT, 'required' => true),
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
	
	}