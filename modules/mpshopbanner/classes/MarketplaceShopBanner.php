<?php
	class MarketplaceShopBanner extends ObjectModel {
		public $id;	
		public $name;		
		public $mp_id_shop;
		public $date_add;
		public $date_upd;
		public $is_active;
		
		/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'marketplace_shop_banner',
		'primary' => 'id',
		'fields' => array(
			'name' => array('type' => self::TYPE_STRING, 'required' => true),			
			'mp_id_shop' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt' ,'required' => true),
			'is_active' => array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
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
		Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_ .'marketplace_shop_banner` WHERE `id` = '.(int)$this->id);
		return parent::delete();
	}
	
	public function getAllBannerIdshop($mp_id_shop){		
		$list = Db::getInstance()->executeS("SELECT * from `"._DB_PREFIX_ . "marketplace_shop_banner` WHERE mp_id_shop = '".$mp_id_shop."'");
		if(empty($list)){
			return false;
		}else{
			return $list;
		}
	}
	
	public function getActiveBannerByIdshop($mp_id_shop){		
		$list = Db::getInstance()->executeS("SELECT * from `"._DB_PREFIX_ . "marketplace_shop_banner` WHERE mp_id_shop = '".$mp_id_shop."' AND is_active=1");
		if(empty($list)){
			return false;
		}else{
			return $list;
		}
	}
	
	public function setAllToInactive($mp_id_shop){
		return Db::getInstance()->execute("UPDATE `" . _DB_PREFIX_ . "marketplace_shop_banner` set `is_active` = 0 where `mp_id_shop` = " . $mp_id_shop . "");		
	}
	
	public function setBannerActiveById($id){
		return Db::getInstance()->execute("UPDATE `" . _DB_PREFIX_ . "marketplace_shop_banner` set `is_active` = 1 where `id` = " . $id . "");		
	}
}