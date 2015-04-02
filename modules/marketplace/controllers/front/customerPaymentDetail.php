<?php
	if (!defined('_PS_VERSION_'))
		exit();
		
	class marketplaceCustomerPaymentDetailModuleFrontController extends ModuleFrontController	{
		public function initContent() {
			parent::initContent();
			$customer_id = $this->context->cookie->id_customer;
			$link = new link();
			$param = array('pay'=>1);
			$payPro_link = $link->getModuleLink('marketplace','paymentprocess',$param);
			$pay_mode = Db::getInstance()->ExecuteS("SELECT * from `"._DB_PREFIX_."marketplace_payment_mode`");
			$this->context->smarty->assign("customer_id",$customer_id);
			$this->context->smarty->assign("pay_mode",$pay_mode);
			$this->context->smarty->assign("payPro_link",$payPro_link);
			$this->setTemplate('customerPaymentDetail.tpl');
		}
	}
?>