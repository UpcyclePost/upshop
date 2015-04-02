<?php
	class MarketplaceCustomerPaymentDetail extends ObjectModel{
		public $id;
		public $id_customer;
		public $payment_mode_id;
		public $payment_detail;
		/**
	 * @see ObjectModel::$definition
	 */
		public static $definition = array(
			'table' => 'marketplace_customer_payment_detail',
			'primary' => 'id',
			'fields' => array(
				'id_customer' =>		array('type' => self::TYPE_INT, 'validate' => 'isInt'),
				'payment_mode_id' =>		array('type' => self::TYPE_INT, 'validate' => 'isInt'),
				'payment_detail' => array('type' => self::TYPE_STRING)
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

		public function getPaymentDetailByCustomerId($id_customer)
		{
			$paymentdetail = Db::getInstance()->getRow('SELECT mcpd.*,mpm.`payment_mode`
										FROM `'._DB_PREFIX_.'marketplace_customer_payment_detail` mcpd
										LEFT JOIN `'._DB_PREFIX_.'marketplace_payment_mode` mpm
										ON (mcpd.`payment_mode_id` = mpm.`id`)
										WHERE mcpd.id_customer = '.(int)$id_customer);
			if (empty($paymentdetail))
				return false;
			else
				return $paymentdetail;
		}
	}
?>