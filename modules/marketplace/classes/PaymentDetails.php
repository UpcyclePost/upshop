<?php
class PaymentDetails extends ObjectModel
{
	public $id;
	public $id_customer;
	public $payment_mode_id;
	public $payment_detail;
	
 	 	 	 	 	 	 
	public static $definition = array(
		'table' => 'marketplace_customer_payment_detail',
		'primary' => 'id',
		'fields' => array(
			'id_customer' => array('type' => self::TYPE_INT),
			'payment_mode_id' =>	array('type' => self::TYPE_INT,'required' => true),
			'payment_detail' => array('type' => self::TYPE_STRING)			
		),
	);

	public function getSellerPaymentDetails($id_customer)
	{
		return Db::getInstance()->getRow("SELECT * FROM `"._DB_PREFIX_."marketplace_customer_payment_detail` WHERE `id_customer`=".(int)$id_customer);
	}
}
?>