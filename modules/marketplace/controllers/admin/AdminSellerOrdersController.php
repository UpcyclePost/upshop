<?php
class AdminSellerOrdersController extends ModuleAdminController
{
	protected $statuses_array = array();
	
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'marketplace_order_commision';
        $this->className = 'MarketplaceOrderCommission'; 
        $this->context = Context::getContext();
        $this->addRowAction('view');          
        $this->_select = 'osl.`name` AS `osname`,os.`color`,o.total_paid_tax_incl-o.total_paid_tax_excl as total_tax,o.total_paid,o.total_products as ttl_items,o.date_add,sllr_c.id_customer as seller,mc.marketplace_seller_id as id_seller, o.reference as reference,a.id_order as id_order,a.id_customer as id_customer,c.email as email,sllr_c.email as seller_email,a.tax as tax,a.shipping as shipping,a.shipping_amt shipping_amt,(a.admin_commission-a.shipping_amt) as admin_commission,
            CONCAT(c.`firstname`," ",c.`lastname`) as customer,CONCAT(sllr_c.`firstname`," ",sllr_c.`lastname`) as seller_name,ms.shop_name as shop_name';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_shop` ms ON (ms.`id_customer` = (select `customer_id` from `'._DB_PREFIX_.'marketplace_commision_calc` mcc where mcc.`id_order` = a.`id_order` limit 1)) ';    
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (a.`id_customer` = c.`id_customer`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customer` sllr_c ON (ms.`id_customer` = sllr_c.`id_customer`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_customer` mc ON (mc.`id_customer` = sllr_c.`id_customer`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = o.`current_state`)';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')';
		
		$statuses = OrderState::getOrderStates((int)$this->context->language->id);
		foreach ($statuses as $status)
			$this->statuses_array[$status['id_order_state']] = $status['name'];
		

        $this->fields_list = array(
            'id_order' => array(
                'title' => $this->l('Id Order'),
                'align' => 'text-center',
				'remove_onclick' => true
            ),
			'date_add' => array(
                'title' => $this->l('Order Date'),
                'align' => 'center',
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
			'seller' => array(
                'title' => $this->l('View Seller'),
                'align' => 'center',
				'callback' => 'printSllrIcons',
				'filter_key' => 'sllr_c!id_customer',
				'havingFilter' => true,
				'remove_onclick' => true,
            ), 
			'shop_name' => array(
                'title' => $this->l('Shop'),
                'align' => 'center',
				'callback' => 'printShopIcons',
				'remove_onclick' => true,
				'filter_key' => 'ms!shop_name',
            ),
			'seller_name' => array(
                'title' => $this->l('Seller'),
                'align' => 'center',
                'havingFilter' => true,
				'remove_onclick' => true
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
			'customer' => array(
                'title' => $this->l('Buyer'),
                'align' => 'center',
                'havingFilter' => true,
				'remove_onclick' => true
            ),
			'email' => array(
                'title' => $this->l('Buyer Email'),
                'align' => 'center',
                'havingFilter' => true,
				'remove_onclick' => true
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
            'total_tax' => array(
                'title' => $this->l('Tax'),
                'align' => 'center',
				'type' => 'price',
				'remove_onclick' => true
            ), 
			 'ttl_items' => array(
                'title' => $this->l('Items Total'),
                'align' => 'center',
                'type' => 'price',
				'remove_onclick' => true
            ),
            'shipping_amt' => array(
                'title' => $this->l('Shipping Amt'),
                'type' => 'price',
                'align' => 'center',
				'remove_onclick' => true
            ),
			 'total_paid' => array(
                'title' => $this->l('Order Total'),
                'align' => 'center',
                'type' => 'price',
				'remove_onclick' => true
            ),
            'admin_commission' => array(
                'title' => $this->l('Admin Commission'),
                'align' => 'center',
                'type' => 'price',
				'remove_onclick' => true,
            ),
        );
        
        // $this->list_no_link = true;
      if ($_GET['submitFiltermarketplace_order_commision']!='')
		{
			$_POST['submitFilter'] = '';
			$_POST['submitFiltermarketplace_order_commision'] = 1;
			$_POST['marketplace_order_commisionFilter_sllr_c!id_customer'] = Tools::getValue('marketplace_order_commisionFilter_sllr_c!id_customer');
		}
		
        $this->identifier  = 'id';
        parent::__construct();
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
	

    public function renderView()
    {
        $obj_mporder  = new MarketplaceOrderCommission();
        $id = Tools::getValue('id');
        $id_order = $obj_mporder->getIdOrderById($id);
		
        if ($id_order!='')
        {
            $mp_order_details = $obj_mporder->getDetailsList($id_order);
			$order_details = OrderDetail::getList($id_order);
			$total_commission = 0;
			foreach($order_details as $key=>$ord){
				foreach($mp_order_details as $mp)
			      if($ord['product_id']==$mp['product_id']){
					 $order_details[$key]['line_commission'] = $mp['commision'];
					 $order_details[$key]['line_total'] = $mp['price'];
					 $total_commission += $mp['commision'];
					}
					
			}
			$order = new Order($id_order);
			$order_state = $order->getCurrentStateFull((int)$this->context->language->id);
			$id_seller = Db::getInstance()->getValue('SELECT `customer_id` FROM `'._DB_PREFIX_.'marketplace_commision_calc` WHERE `id_order` = '.$id_order,false);
			$seller = new Customer($id_seller);
			$buyer = new Customer($order->id_customer);
			$this->context->smarty->assign('seller', $seller);
			$this->context->smarty->assign('buyer', $buyer);
            $this->context->smarty->assign('order_details', $order_details);
			$this->context->smarty->assign('order_date', $order->date_add);
			$this->context->smarty->assign('order_ref', $order->reference);
			$this->context->smarty->assign('order_state', $order_state['name']);
			$this->context->smarty->assign('subtotal', $order->total_products_wt);
			$this->context->smarty->assign('total', $order->total_paid);
			$this->context->smarty->assign('tax', $order->total_paid_tax_incl-$order->total_paid_tax_excl);
			$this->context->smarty->assign('shipping', $order->total_shipping);
			$this->context->smarty->assign('total_commission', $total_commission);
        }
        return parent::renderView();
    } 
	
    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }
}
?>