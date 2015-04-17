<?php
	class Mpshippingimpact extends ObjectModel {
		public $id;	
		public $mp_shipping_id;
		public $shipping_delivery_id;
		public $id_zone;
		public $id_country;
		public $id_state;
		public $impact_price;			
		public $date_add;		
		public $date_upd;		
		/**
	 * @see ObjectModel::$definition
	 */
		public static $definition = array(
			'table' => 'mp_shipping_impact',
			'primary' => 'id',
			'fields' => array(
				'mp_shipping_id' => array('type' => self::TYPE_INT, 'required' => true),
				'shipping_delivery_id' => array('type' => self::TYPE_INT, 'required' => true),
				'id_zone' => array('type' => self::TYPE_INT, 'required' => true),
				'id_country' => array('type' => self::TYPE_INT, 'required' => true),
				'id_state' => array('type' => self::TYPE_INT),
				'impact_price' => array('type' => self::TYPE_FLOAT,'validate' => 'isPrice', 'required' => true),
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
		
		public function getCountriesByZoneId($id_zone, $id_lang){
			$sql = ' SELECT DISTINCT c.id_country, cl.name
				FROM `'._DB_PREFIX_.'country` c
				'.Shop::addSqlAssociation('country', 'c', false).'
				LEFT JOIN `'._DB_PREFIX_.'state` s ON (s.`id_country` = c.`id_country`)
				LEFT JOIN `'._DB_PREFIX_.'country_lang` cl ON (c.`id_country` = cl.`id_country`)
				WHERE (c.`id_zone` = '.(int)$id_zone.' OR s.`id_zone` = '.(int)$id_zone.')
				AND `id_lang` = '.(int)$id_lang;
			return Db::getInstance()->executeS($sql);
		}
		
		public function getStatesByIdCountry($id_country) {
			if (empty($id_country))
				die(Tools::displayError());

			return Db::getInstance()->executeS('
			SELECT s.`id_state`,s.`name`
			FROM `'._DB_PREFIX_.'state` s
			WHERE s.`id_country` = '.(int)$id_country.' and s.`active`=1'
			);
		} 
		
		public function isAllReadyInImpact($mp_shipping_id,$shipping_delivery_id,$id_zone,$id_country,$id_state) {
			$is_exist_impact = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from "._DB_PREFIX_."mp_shipping_impact where mp_shipping_id=".(int)$mp_shipping_id." and shipping_delivery_id=".(int)$shipping_delivery_id." and id_zone=".$id_zone." and id_country=".(int)$id_country." and id_state=".$id_state);
			
			if(empty($is_exist_impact)) {
				return false;
			} else {
				return $is_exist_impact;
			}
		}
	}