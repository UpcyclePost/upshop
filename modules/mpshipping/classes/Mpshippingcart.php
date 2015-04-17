<?php
	class Mpshippingcart extends ObjectModel {
		public $id;	
		public $ps_id_cart;
		public $ps_id_carrier;
		public $extra_cost;			
		public $date_add;		
		public $date_upd;		
		/**
	 * @see ObjectModel::$definition
	 */
		public static $definition = array(
			'table' => 'mp_shipping_cart',
			'primary' => 'id',
			'fields' => array(
				'ps_id_cart' => array('type' => self::TYPE_INT, 'required' => true),
				'ps_id_carrier' => array('type' => self::TYPE_INT, 'required' => true),
				'extra_cost' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
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
		
		public function isAvailable($ps_id_carrier,$ps_id_cart) {
			$is_available = Db::getInstance()->getRow('Select * from `'._DB_PREFIX_.'mp_shipping_cart` where `ps_id_carrier` = '.$ps_id_carrier.' and ps_id_cart='.$ps_id_cart);
			if(empty($is_available)) {
				return false;
			} else {
				return $is_available;
			}
		}
	}