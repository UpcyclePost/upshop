<?php
class AdminSellerOrdersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'marketplace_seller_info';
        $this->className = 'SellerInfoDetail'; 
        $this->context = Context::getContext();
        $id_lang = $this->context->language->id;
        $this->addRowAction('view');          
        /*$this->_select = 'o.reference as reference,a.id_order as id_order,a.id_customer as id_customer,a.tax as tax,a.shipping as shipping,a.shipping_amt shipping_amt,a.admin_commission as admin_commission,
            CONCAT(c.`firstname`," ",c.`lastname`) as customer,ms.shop_name as shop_name';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_shop` ms ON (ms.`id_customer` = a.`id_customer`) ';    
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (a.`id_customer` = c.`id_customer`) ';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`) ';


        $this->fields_list = array(
            'id_order' => array(
                'title' => $this->l('Id Order'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
			'reference' => array(
                'title' => $this->l('Reference'),
                'align' => 'center',
				'callback' => 'printRefIcons',
				'orderby' => false,
				'search' => false,
				'remove_onclick' => true
            ),
            'id_customer' => array(
                'title' => $this->l('View Customer'),
                'align' => 'center',
				'callback' => 'printEditIcons',
				'orderby' => false,
				'search' => false,
				'remove_onclick' => true
            ),
			'customer' => array(
                'title' => $this->l('Customer'),
                'align' => 'center',
                'havingFilter' => true
            ),
            'shop_name' => array(
                'title' => $this->l('Shop'),
                'align' => 'center'
            ), 

            'tax' => array(
                'title' => $this->l('Tax'),
                'align' => 'center'
            ), 
            'shipping' => array(
                'title' => $this->l('Shipping'),
                'align' => 'center'
            ), 
            'shipping_amt' => array(
                'title' => $this->l('Shipping Amt'),
                'type' => 'price',
                'align' => 'center'
            ),
            'admin_commission' => array(
                'title' => $this->l('Admin Commission'),
                'align' => 'center',
                'type' => 'price',
            ),
        );*/
        
        // $this->list_no_link = true;
        

        $this->_select = 'SUM(mcc.commision) as total_commision';
        $this->_join .= '
        LEFT JOIN `'._DB_PREFIX_.'marketplace_customer` mc ON (mc.`marketplace_seller_id` = a.`id`)
        INNER JOIN `'._DB_PREFIX_.'marketplace_commision_calc` mcc ON (mcc.`customer_id` = mc.`id_customer`)';

        $this->_group = 'GROUP BY a.`id`';
        $this->fields_list = array(
            'id' => array(
                'title' => $this->l('Id Seller'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'seller_name' => array(
                'title' => $this->l('Seller Name'),
                'align' => 'center',
                'havingFilter' => true
            ),
            'shop_name' => array(
                'title' => $this->l('Shop Name'),
                'align' => 'center'
            ),
            'total_commision' => array(
                'title' => $this->l('Total Commission'),
                'align' => 'center',
                'type'  => 'price'
            ),
        );
      
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

    public function renderView()
    {
        /*$obj_mporder  = new MarketplaceOrderCommission();
        $id = Tools::getValue('id');
        $id_order = $obj_mporder->getIdOrderById($id);
        if ($id_order)
        {
            $mp_order_details = $obj_mporder->getDetailsList($id_order);
            if ($mp_order_details)
                $this->context->smarty->assign('mp_order_details', $mp_order_details);
        }*/
        if ($id_seller = Tools::getValue('id'))
        {
            $obj_marketplace_customer  = new MarketplaceCustomer();
            $customer_id = $obj_marketplace_customer->getCustomerId($id_seller);
            $id_lang = $this->context->language->id;

            $dashboard = $this->getCustomerOrderDeatils($id_lang, $customer_id);
            //d($dashboard);
            $a=0;
            $order_by_cus = array();
            $order_currency = array();
            foreach($dashboard as $dashboard1)
            {
                $order_by_cus[]= Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * FROM `"._DB_PREFIX_."customer` WHERE id_customer=".$dashboard1['order_by_cus']);
                $currency_detail = Currency::getCurrency($dashboard1['id_currency']);
                $order_currency[] = $currency_detail['sign'];
                $a++;                
            }
            $admin_commission = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("SELECT SUM(commision) AS commisionval FROM `"._DB_PREFIX_."marketplace_commision_calc` WHERE `customer_id`=".$customer_id." GROUP BY id_order DESC");

            if(isset($order_by_cus))
            {
                if(is_array($order_by_cus))
                {
                    $this->context->smarty->assign("order_by_cus", $order_by_cus);
                    $this->context->smarty->assign("order_currency", $order_currency);
                }
            }

            if ($admin_commission)
                $this->context->smarty->assign("admin_commission", $admin_commission);

            $count = count($dashboard);
            $this->context->smarty->assign('count', $count);
            $this->context->smarty->assign('dashboard', $dashboard);
            $this->context->smarty->assign("id_seller", $id_seller);
        }
        return parent::renderView();
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    public function getCustomerOrderDeatils($id_lang, $customer_id)
    {
        $dashboard = Db::getInstance()->executeS("SELECT ordd.`id_order_detail` as `id_order_detail`,ordd.`product_name` as `ordered_product_name`,ordd.`product_price` as total_price,ordd.`product_quantity` as qty, ordd.`id_order` as id_order,ord.`id_customer` as order_by_cus,ord.`payment` as payment_mode,cus.`firstname` as name,ord.`date_add`,ords.`name`as order_status,ord.`id_currency` as `id_currency` from `" . _DB_PREFIX_ . "marketplace_shop_product` msp join `" . _DB_PREFIX_ . "order_detail` ordd on (ordd.`product_id`=msp.`id_product`) join `"._DB_PREFIX_."orders` ord on (ordd.`id_order`=ord.`id_order`) join `"._DB_PREFIX_."marketplace_seller_product` msep on (msep.`id` = msp.`marketplace_seller_id_product`) join `"._DB_PREFIX_."marketplace_customer` mkc on (mkc.`marketplace_seller_id` = msep.`id_seller`) join `" . _DB_PREFIX_ . "customer` cus on (mkc.`id_customer`=cus.`id_customer`) join `" . _DB_PREFIX_ . "order_state_lang` ords on (ord.`current_state`=ords.`id_order_state`) where ords.id_lang=".$id_lang." and cus.`id_customer`=" . $customer_id . "  GROUP BY ordd.`id_order` order by ordd.`id_order` desc");
        return $dashboard;


        
    }
}
?>