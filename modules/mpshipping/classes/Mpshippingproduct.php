<?php
	class Mpshippingproduct extends ObjectModel {
		public $id;
		public $mp_product_id;
		public $width;
		public $height;
		public $depth;
		public $weight;
		public $date_add;
		public $date_upd;
		
		public static $definition = array(
			'table' => 'mp_shipping_product',
			'primary' => 'id',
			'fields' => array(
				'mp_product_id' => array('type' => self::TYPE_INT ,'validate' => 'isUnsignedInt', 'required' => true),
				'width' => 	array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
				'height' => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
				'depth' => 	array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
				'weight' => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
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

		public function findWeightInfoByMpProID($mp_product_id) {
			$mp_product_wet_detail = Db::getInstance()->executeS('
			SELECT * FROM `'._DB_PREFIX_.'mp_shipping_product` 
			WHERE `mp_product_id` = '.(int)$mp_product_id.''
			);
			if(empty($mp_product_wet_detail)) {
				return false;
			} else {
				return $mp_product_wet_detail;
			}
		}
	}
?>