<?php
class AdminSellerOrdersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'marketplace_order_commision';
        $this->className = 'MarketplaceOrderCommission'; 
        $this->context = Context::getContext();
        $this->addRowAction('view');          
        $this->_select = 'a.id_order as id_order,a.id_customer as id_customer,a.tax as tax,a.shipping as shipping,a.shipping_amt shipping_amt,a.admin_commission as admin_commission,
            CONCAT(c.`firstname`," ",c.`lastname`) as customer,ms.shop_name as shop_name';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_shop` ms ON (ms.`id_customer` = a.`id_customer`) ';    
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (a.`id_customer` = c.`id_customer`) ';


        $this->fields_list = array(
            'id_order' => array(
                'title' => $this->l('Id Order'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
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
        );
        
        // $this->list_no_link = true;
      
        $this->identifier  = 'id';
        parent::__construct();
    }

    public function renderView()
    {
        $obj_mporder  = new MarketplaceOrderCommission();
        $id = Tools::getValue('id');
        $id_order = $obj_mporder->getIdOrderById($id);
        if ($id_order)
        {
            $mp_order_details = $obj_mporder->getDetailsList($id_order);
            if ($mp_order_details)
                $this->context->smarty->assign('mp_order_details', $mp_order_details);
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