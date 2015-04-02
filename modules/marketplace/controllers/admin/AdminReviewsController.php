<?php
include_once dirname(__FILE__).'/../../classes/Reviews.php';
class AdminReviewsController extends ModuleAdminController 
{

	public function __construct()
    {
		$this->bootstrap = true;
		$this->table = 'seller_reviews';
		$this->className   = 'Reviews';
		//$this->list_no_link = true;
		$this->_defaultOrderBy = 'id_review';
		$this->addRowAction('view');
		$this->addRowAction('delete');
		$this->fields_list = array(
		    'id_review' => array(
				'title' => $this->l('Id'),
				'align' => 'center',
				'class' => 'fixed-width-xs'
			),
			'id_customer' => array(
				'title' => $this->l('Customer'),
				'align' => 'center'
			),
			'customer_email' => array(
				'title' => $this->l('Customer Email'),
				'align' => 'center'
			),
			
			'rating' => array(
				'title' => $this->l('Rating'),
				'align' => 'center'
			),
			'review' => array(
				'title' => $this->l('Review'),
				'align' => 'center'
			),

			'active' => array(
				'title' => $this->l('Status'),
				'align' => 'center',
				'active' => 'status',
				'type' => 'bool',
				'orderby' => false
			)
		);
		$this->identifier = 'id_review';
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'),
													'icon' => 'icon-trash',
													'confirm' => $this->l('Delete selected items?')));
        parent::__construct();
	}
	
	public function postProcess() {
		if($this->display == 'view')
		{
			$review_detail =  Db::getInstance()->getRow("SELECT * from `" . _DB_PREFIX_ . "seller_reviews` where id_review=" . Tools::getValue("id_review") . "");
			$customer_detail =  Db::getInstance()->getRow("SELECT * from `" . _DB_PREFIX_ . "customer` where id_customer=" . $review_detail["id_customer"] . "");
			$customer_name = $customer_detail['firstname'].' '.$customer_detail['lastname'];
			$this->context->smarty->assign('review_detail',$review_detail);
			$this->context->smarty->assign('customer_name',$customer_name);
		}
		return parent::postProcess();
	}
	public function initToolbar() {
		
	}
}
?>