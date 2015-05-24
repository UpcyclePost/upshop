<?php
class MarketplaceProductTag extends ObjectModel {
	public $id;	
	public $mp_product_id;
	public $tag_id;		
	public $date_add;
	public $date_upd;	

	public static $definition = array(
			'table' => 'mp_product_tags',
			'primary' => 'id',
			'fields' => array(
				'mp_product_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt' ,'required' => true),
				'tag_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
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
		
		public function getProductTags($mp_product_id){
			$product_tag = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("select tag_id as id from "._DB_PREFIX_."mp_product_tags where mp_product_id=".$mp_product_id);
			if(empty($product_tag)) {
				return false;
			} else {
				return $product_tag;
			}
		}

		public function getTagnameById($tag_id){
			$tag_name = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select name from "._DB_PREFIX_."tag where id_tag=".$tag_id);
			if(empty($tag_name)) {
				return false;
			} else {
				return $tag_name;
			}
		}

		public function checkIfTagExist($tag_name){
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from "._DB_PREFIX_."tag where name="."'".$tag_name."'");
			if(empty($result)) {
				return false;
			} else {
				return $result;
			}
		}

		public static function isValidTagName($tag_name)
		{
			return preg_match('/^[^!<>;?=+#"°{}_$%]*$/u', $tag_name);
		}

		public function getTagIdByName($tag_name){
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select id_tag from "._DB_PREFIX_."tag where name="."'".$tag_name."'");
			if(empty($result)) {
				return false;
			} else {
				return $result;
			}
		}

		public function getTagId($mp_product_id){
			$tag_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("select id from "._DB_PREFIX_."mp_product_tags where mp_product_id=".$mp_product_id);
			if(empty($tag_id)) {
				return false;
			} else {
				return $tag_id;
			}
		}

	public function deleteMpProductTags($mp_product_id)
	{
		return Db::getInstance()->delete('mp_product_tags', 'mp_product_id = '.$mp_product_id);
	}	


	}
?>