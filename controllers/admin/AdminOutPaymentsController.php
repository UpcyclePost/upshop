<?php
class AdminOutPaymentsController extends AdminController
{
	protected $statuses_array = array();
	
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'marketplace_order_commision';
        $this->className = 'MarketplaceOrderCommission'; 
        $this->context = Context::getContext();
        $this->_select = 'DATE_FORMAT(DATE_ADD(st.date_add,INTERVAL 7 DAY),"%m/%d/%Y %H:%i:%s") as capture_time,((o.total_products+a.shipping_amt)-(a.admin_commission-a.shipping_amt)) as due_seller,osl.`name` AS `osname`,os.`color`,o.total_paid_tax_incl-o.total_paid_tax_excl as total_tax,o.total_paid,o.total_products as ttl_items,o.date_add,sllr_c.id_customer as seller,mc.marketplace_seller_id as id_seller, o.reference as reference,a.id_order as id_order,a.id_customer as id_customer,sllr_c.email as seller_email,a.tax as tax,a.shipping as shipping,a.shipping_amt shipping_amt,(a.admin_commission-a.shipping_amt) as admin_commission,CONCAT(sllr_c.`firstname`," ",sllr_c.`lastname`) as seller_name,ms.shop_name as shop_name,ss.stripe_seller_id';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_shop` ms ON (ms.`id_customer` = (select `customer_id` from `'._DB_PREFIX_.'marketplace_commision_calc` mcc where mcc.`id_order` = a.`id_order` limit 1)) ';    
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (a.`id_customer` = c.`id_customer`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customer` sllr_c ON (ms.`id_customer` = sllr_c.`id_customer`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'stripepro_sellers` ss ON (ms.`id_customer` = ss.`id_customer`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'stripepro_transaction` st ON (st.`id_order` = a.`id_order` && st.`status`="uncaptured")';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_customer` mc ON (mc.`id_customer` = sllr_c.`id_customer`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = o.`current_state`)';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')';
		$this->_where .= '=1 && o.`current_state` IN(2,4)';
		$this->_orderBy = 'a.id';
		$this->_orderWay = 'DESC';
		
		$statuses = OrderState::getOrderStates((int)$this->context->language->id);
		foreach ($statuses as $status)
			$this->statuses_array[$status['id_order_state']] = $status['name'];
		

        $this->fields_list = array(
		     'id' => array(
                'title' => $this->l('Pay'),
                'align' => 'text-center',
				'callback' => 'printPayIcons',
				'orderby' => false,
				'search' => false,
				'remove_onclick' => true
            ),
            'id_order' => array(
                'title' => $this->l('Order ID'),
                'align' => 'text-center',
				'havingFilter' => true,
				'remove_onclick' => true
            ),
			'reference' => array(
                'title' => $this->l('Reference'),
                'align' => 'center',
				'callback' => 'printRefIcons',
				'havingFilter' => true,
				'remove_onclick' => true
            ),
			'shop_name' => array(
                'title' => $this->l('Shop'),
                'align' => 'center',
				'callback' => 'printShopIcons',
				'remove_onclick' => true,
				'filter_key' => 'ms!shop_name',
            ),
			'seller' => array(
                'title' => $this->l('View Seller'),
                'align' => 'center',
				'callback' => 'printSllrIcons',
				'filter_key' => 'sllr_c!id_customer',
				'havingFilter' => true,
				'remove_onclick' => true,
            ), 
			'seller_name' => array(
                'title' => $this->l('Seller'),
                'align' => 'center',
                'havingFilter' => true,
				'remove_onclick' => true
            ), 
			'stripe_seller_id' => array(
                'title' => $this->l('Seller Account'),
                'align' => 'center',
				'remove_onclick' => true,
            ),
			'seller_email' => array(
                'title' => $this->l('Seller Email'),
                'align' => 'center',
                'havingFilter' => true,
				'remove_onclick' => true
            ),
            'id_customer' => array(
                'title' => $this->l('View Buyer'),
                'align' => 'center',
				'callback' => 'printEditIcons',
				'remove_onclick' => true,
				'filter_key' => 'c!id_customer',
            ),
			'osname' => array(
				'title' => $this->l('Status'),
				'type' => 'select',
				'color' => 'color',
				'list' => $this->statuses_array,
				'filter_key' => 'os!id_order_state',
				'filter_type' => 'int',
				'order_key' => 'osname',
				'remove_onclick' => true
			),
			'capture_time' => array(
                'title' => $this->l('Capture Due On'),
                'align' => 'center',
				'remove_onclick' => true,
				'filter_key' => 'st!date_add',
            ),
            'total_tax' => array(
                'title' => $this->l('Tax'),
                'align' => 'center',
				'type' => 'price',
				'remove_onclick' => true
            ), 
			 'ttl_items' => array(
                'title' => $this->l('Total'),
                'align' => 'center',
                'type' => 'price',
				'remove_onclick' => true
            ),
            'shipping_amt' => array(
                'title' => $this->l('Ship'),
                'type' => 'price',
                'align' => 'center',
				'remove_onclick' => true
            ),
			 'total_paid' => array(
                'title' => $this->l('Order Tot'),
                'align' => 'center',
                'type' => 'price',
				'remove_onclick' => true
            ),
            'admin_commission' => array(
                'title' => $this->l('Comm'),
                'align' => 'center',
                'type' => 'price',
				'remove_onclick' => true,
            ),
            'due_seller' => array(
                'title' => $this->l('Due'),
                'align' => 'center',
                'type' => 'price',
				'remove_onclick' => true,
            ),
			'date_add' => array(
                'title' => $this->l('Order Date'),
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
		
        $this->identifier  = 'id';
        parent::__construct();
    }
	
	public function printPayIcons($id, $tr)
	{
		$link = new Link();
		$link = $link->getAdminLink('AdminSellerTransfer').'&amp;id_seller='.$tr['seller'].'&amp;id_order='.$tr['id_order'];
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-search-plus"></i> &nbsp;Pay
		</a>
	</span>
</span>';
        return $html;

	}
	public function printEditIcons($id_customer, $tr)
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
	
	public function printRefIcons($reference, $tr)
	{
		
		$link = new Link();
		$link = $link->getAdminLink('AdminOrders').'&amp;vieworder&amp;id_order='.$tr['id_order'];
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-search-plus"></i> &nbsp;'.$reference.'
		</a>
	</span>
</span>';
        return $html;

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
	
	public function printSllrIcons($seller, $tr)
	{
		
		$link = new Link();
		
		$link = $link->getAdminLink('AdminCustomers').'&amp;viewcustomer&amp;id_customer='.$seller;
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-search-plus"></i> &nbsp;'.$seller.'
		</a>
	</span>
</span>';
        return $html;

	}
}
?>