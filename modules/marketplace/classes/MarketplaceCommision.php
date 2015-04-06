<?php
class MarketplaceCommision extends ObjectModel
{
	public $id;
	public $commision;
	public $customer_id;
	public $customer_name;
	

	public static $definition = array(
		'table' => 'marketplace_commision',
		'primary' => 'id',
		'fields' => array(
			'commision' => array('type' => self::TYPE_FLOAT,'required' => true),
			'customer_id' => array('type' => self::TYPE_INT,'required' => true),
			'seller_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
			'customer_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
			
		),
	);
	
	public function findGlobalcomm() {
		$globlacomm = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('select * from `' . _DB_PREFIX_ . 'marketplace_commision` where customer_id='.$this->customer_id);
		if(empty($globlacomm)) {
			return false;
		} else {
			return $globlacomm['commision'];
		}
	}
	
	public function findAllCustomerInfo() {
		$customer_info  = Db::getInstance()->executeS('select * from `' . _DB_PREFIX_ . 'customer` where `id_customer`='.$this->customer_id);
		if(empty($customer_info)) {
			return false;
		} else {
			return $customer_info;
		}
	}

	public function getSellerNotHaveCommissionSet() {
		$mp_selle_info = Db::getInstance()->executeS('select c.`id_customer`,c.`email` from `' . _DB_PREFIX_ . 'marketplace_customer` mc join `' . _DB_PREFIX_ . 'customer` c on (mc.id_customer=c.id_customer) where mc.id_customer NOT IN (select customer_id from `' . _DB_PREFIX_ . 'marketplace_commision`)');
		if(empty($mp_selle_info)) {
			return false;
		} else {
			return $mp_selle_info;
		}
	}

	public function getTaxByIdOrderDetail($id_order_detail)
	{
		$tax_amt = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `total_amount` FROM `'._DB_PREFIX_.'order_detail_tax`
																	WHERE `id_order_detail` = '.(int)$id_order_detail);

		if ($tax_amt)
			return $tax_amt;
		else
			return 0;
	}
}