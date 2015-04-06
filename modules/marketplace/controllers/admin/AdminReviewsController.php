<?php
include_once dirname(__FILE__).'/../../classes/Reviews.php';
class AdminReviewsController extends ModuleAdminController 
{

	public function __construct()
    {
		$this->bootstrap = true;
		$this->table = 'seller_reviews';
		$this->className   = 'Reviews';

		$this->_defaultOrderBy = 'id_review';
		$this->_select = 'msi.business_email AS seller_email';
		$this->_join = 'LEFT JOIN `'._DB_PREFIX_.'marketplace_seller_info` msi ON (a.`id_seller` = msi.`id`)';

		//$this->list_no_link = true;
		$this->addRowAction('view');
		$this->addRowAction('delete');

		$this->fields_list = array(
		    'id_review' => array(
				'title' => $this->l('Id'),
				'align' => 'center',
				'class' => 'fixed-width-xs'
			),
			'seller_email' => array(
				'title' => $this->l('Seller Email'),
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

	public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }
	
	public function postProcess()
	{
		if($this->display == 'view')
		{
			$obj_review = new Reviews();

			$id_review = Tools::getValue('id_review');
			$review_detail = $obj_review->getReviewById($id_review);

			// get seller information
			$obj_mp_seller = new SellerInfoDetail($review_detail['id_seller']);

			// get customer information
			if ($review_detail['id_customer']) // if not a guest
			{
				$obj_customer = new Customer($review_detail['id_customer']);
				$customer_name = $obj_customer->firstname.' '.$obj_customer->lastname;
				$this->context->smarty->assign('customer_name', $customer_name);
			}
			
			$this->context->smarty->assign('review_detail', $review_detail);
			$this->context->smarty->assign('obj_mp_seller', $obj_mp_seller);
		}

		return parent::postProcess();
	}
}
?>