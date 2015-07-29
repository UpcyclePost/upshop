<?php
class AdminPaymentHistoryController extends AdminController
{
	protected $statuses_array = array();
	
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_transfer';
        $this->context = Context::getContext();
		//$this->addRowAction('view');
        $this->_select = 'DATE_FORMAT(a.date_add,"%m/%d/%Y %H:%i:%s") as date_add,msi.seller_name,msi.shop_name,msi.business_email,';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_seller_info` msi ON (msi.`id` = a.id_seller) ';    
        
		$this->_orderBy = 'a.id_transfer';
		$this->_orderWay = 'DESC';
		

        $this->fields_list = array(
		     'id_transfer' => array(
                'title' => $this->l('ID'),
                'align' => 'text-center',
				'remove_onclick' => true
            ),
			'shop_name' => array(
				'title' => $this->l('Shop'),
				'callback' => 'printShopIcons',
				'filter_key' => 'msi!shop_name',
				'remove_onclick' => true
			),
            'id_customer' => array(
                'title' => $this->l('View Seller'),
                'align' => 'center',
				'callback' => 'printSllrIcons',
				'remove_onclick' => true,
            ),
			'seller_name' => array(
				'title' => $this->l('Seller'),
				'remove_onclick' => true
			),
			'destination' => array(
				'title' => $this->l('Seller Account'),
				'remove_onclick' => true
			),
			'business_email' => array(
				'title' => $this->l('Seller Email'),
				'remove_onclick' => true
			),
			'transaction_id' => array(
                'title' => $this->l('Transaction ID'),
                'align' => 'center',
				'remove_onclick' => true,
            ),
            'amount' => array(
                'title' => $this->l('Amount'),
                'align' => 'center',
				'type' => 'price',
				'remove_onclick' => true
            ), 
			 'status' => array(
                'title' => $this->l('Status'),
                'align' => 'center',
                'type' => 'price',
				'remove_onclick' => true
            ),
			'date_add' => array(
                'title' => $this->l('Transfer Date'),
                'align' => 'center',
                'havingFilter' => true,
				'remove_onclick' => true
            ),
        );
        
        // $this->list_no_link = true;
      if ($_GET['submitFiltermarketplace_order_commision']!='')
		{
			$_POST['submitFilter'] = '';
			$_POST['submitFiltermarketplace_order_commision'] = 1;
			if(Tools::getValue('marketplace_order_commisionFilter_seller_email')!='')
			$_POST['marketplace_order_commisionFilter_seller_email'] = Tools::getValue('marketplace_order_commisionFilter_seller_email');
			elseif(Tools::getValue('marketplace_order_commisionFilter_id_order')!='')
			$_POST['marketplace_order_commisionFilter_id_order'] = Tools::getValue('marketplace_order_commisionFilter_id_order');
		}
		
        $this->identifier  = 'id_transfer';
        parent::__construct();
    }
	
	public function printShopIcons($shop_name, $tr)
	{
		
		$link = new Link();
		//$link = $link->getAdminLink('AdminSellerInfoDetail').'&amp;updatemarketplace_seller_info&amp;id='.$tr['id_seller'];
		$link = $link->getAdminLink('AdminSellerInfoDetail').'&amp;submitFiltermarketplace_seller_info=1&amp;marketplace_seller_infoFilter_shop_name='.$shop_name;
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-search-plus"></i> &nbsp;'.$shop_name.'
		</a>
	</span>
</span>';
        return $html;

	}
	
	public function printSllrIcons($id_customer, $tr)
	{
		
		$link = new Link();
		
		$link = $link->getAdminLink('AdminCustomers').'&amp;viewcustomer&amp;id_customer='.$id_customer;
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-search-plus"></i> &nbsp;'.$id_customer.'
		</a>
	</span>
</span>';
        return $html;

	}
}
?>