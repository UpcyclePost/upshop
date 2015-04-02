<?php
include_once dirname(__FILE__).'/../../classes/MarketplaceCommision.php';
class AdminCommisionCalcController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'marketplace_commision_calc';
        $this->className = 'MarketplaceCommision';
		$this->_select = 'a.id_order as id_ord_details,pm.`payment_mode` as `payment_mode`,ord.reference as reference,ord.payment,ms.shop_name as shop_name,CONCAT(c.`firstname`," ",c.`lastname`) as customer';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'orders` ord ON (a.`id_order` = ord.`id_order`) ';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_shop` ms ON (ms.`id_customer` = a.`customer_id`) ';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_customer_payment_detail` cpd ON (cpd.`id_customer` = a.`customer_id`) ';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_payment_mode` pm ON (cpd.`payment_mode_id` = pm.`id`) ';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (ord.`id_customer` = c.`id_customer`) ';
     
    
        $this->fields_list = array(
            'id_order' => array(
                'title' => $this->l('Id Order'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'reference' => array(
                'title' => $this->l('Order Reference'),
                'align' => 'center'
            ),
            'customer' => array(
                'title' => $this->l('Customer'),
                'align' => 'center'
            ),
            'shop_name' => array(
                'title' => $this->l('Shop'),
                'align' => 'center'
            ),            
            'product_name' => array(
                'title' => $this->l('Product Name'),
                'align' => 'center'
            ),          
            
            'price' => array(
                'title' => $this->l('Product Price'),
                'align' => 'center',
                'type' => 'price',
            ),
            
            'commision' => array(
                'title' => $this->l('Commission'),
                'align' => 'center',
                'type' => 'price',
            ),
            'payment' => array(
                'title' => $this->l('Payment method'),
                'align' => 'center'
            ),
            'payment_mode' => array(
                'title' => $this->l('Reseller Payment Mode'),
                'align' => 'center'
            ),
             'id_ord_details' => array(
                'title' => $this->l('Details'),
                'align' => 'center',
                'callback' => 'getOrderDetails',
                'orderby' => false,
                'search' => false,
                'remove_onclick' => true
            )   
            
        );
        
        $this->list_no_link = true;
        $this->identifier  = 'id';
        parent::__construct();
    }

    public function getOrderDetails($id_order)
    {
        $order = new Order($id_order);
        $this->context->smarty->assign(array(
            'order' => $order
        ));

        return $this->createTemplate('get_order_details.tpl')->fetch();
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }
}
?>