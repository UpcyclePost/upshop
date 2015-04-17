<?php
class MarketplaceProductSeo extends ObjectModel {
	public $id;	
	public $mp_product_id;
	public $meta_title;
	public $meta_description;
	public $friendly_url;			
	public $date_add;
	public $date_upd;	

	public static $definition = array(
			'table' => 'mp_product_seo',
			'primary' => 'id',
			'fields' => array(
				'mp_product_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt' ,'required' => true),
				'meta_title' => array('type' => self::TYPE_STRING),
				'meta_description' =>array('type' => self::TYPE_STRING),
				'friendly_url' => array('type' => self::TYPE_STRING),
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
		public function save($null_values = true, $autodate = true){
		if(!parent::save($null_values, $autodate))
			return false;
		return true;	
		}
		public function delete()
		{
			return parent::delete();
		}
		public function getMetaInfo($mp_product_id){
			$meta_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from "._DB_PREFIX_."mp_product_seo where mp_product_id=".$mp_product_id);
			if(empty($meta_info)) {
				return false;
			} else {
				return $meta_info;
			}
		}
		public function getMetaInfoId($mp_product_id){
			$meta_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select id from "._DB_PREFIX_."mp_product_seo where mp_product_id=".$mp_product_id);
			if(empty($meta_id)) {
				return false;
			} else {
				return $meta_id;
			}
		}



	}
?>