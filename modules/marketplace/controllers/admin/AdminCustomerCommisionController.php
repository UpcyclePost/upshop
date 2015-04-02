<?php
include_once dirname(__FILE__).'/../../classes/MarketplaceCommision.php';
class AdminCustomerCommisionController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table     = 'marketplace_commision';
        $this->className = 'MarketplaceCommision';
        $this->bootstrap = true;
        $this->addRowAction('edit');
        $this->addRowAction('add');
        $this->addRowAction('delete');
        $this->fields_list = array(
            'id' => array(
                'title' => $this->l('Id'),
                'align' => 'center',
               'class' => 'fixed-width-xs'
            ),
            'customer_name' => array(
                'title' => $this->l('Customer Name'),
                'align' => 'center'
            ),
            'commision' => array(
                'title' => $this->l('Customer Commission'),
                'align' => 'center'
            )
        );
        $this->identifier  = 'id';
        parent::__construct();
    }

    public function initToolbar()
    {
        $obj_mp_cutomer = new MarketplaceCustomer();
        $all_customer_is_seller = $obj_mp_cutomer->findIsallCustomerSeller();

        parent::initToolbar();
        $this->page_header_toolbar_btn['new'] = array(
            'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
            'desc' => $this->l('Add new commission')
        );

        if(!$all_customer_is_seller) 
        {
            unset($this->toolbar_btn['new']);
            unset($this->page_header_toolbar_btn['new']);
        }
        else 
        {
            $objmp_comm = new MarketplaceCommision();
            $is_seller_remain = $objmp_comm->getSellerNotHaveCommissionSet();
            //var_dump($is_seller_remain);
            if(!$is_seller_remain)
            {
                unset($this->toolbar_btn['new']);
                unset($this->page_header_toolbar_btn['new']);
            }
        }
    }   


    public function postProcess()
    {
        $token = $this->token;
        if (!$this->loadObject(true))
            return;
        
        elseif (Tools::isSubmit('submitupdatecommision')) {
            $this->submitupdatecommision($token);
        } elseif ($this->tabAccess['delete'] === '1' && Tools::isSubmit('deletemarketplace_commision')) {
            $this->deletecustomer();
        }
        return parent::postProcess();
    }

    public function deletecustomer()
    {
        $id  = Tools::getValue('id');
        $get = Db::getInstance()->Execute("DELETE from`" . _DB_PREFIX_ . "marketplace_commision` where id=" . $id);
        if ($get)
            Tools::redirectAdmin(self::$currentIndex . '&conf=1&token=' . $this->token);
    }

    public function renderForm()
    {
        if ($this->display == 'add') {

            $obj_mp_comm = new MarketplaceCommision();
            $remain_seller = $obj_mp_comm->getSellerNotHaveCommissionSet();
           
            $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Enter Customer Commsion')
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Select Customer'),
                        'name' => 'customer_name',
                        'type' => 'select',
                        'identifier' => 'id',
                        'options' => array(
                            'query' => $remain_seller,
                            'id' => 'id_customer',
                            'name' => 'email'
                        )
                    ),
                    array(
                        'label' => $this->l('Commision'),
                        'name' => 'add',
                        'type' => 'hidden',
                        'value' => '1'
                    ),
                    array(
                        'label' => $this->l('Enter Customer Commission'),
                        'name' => 'new_Commision',
                        'type' => 'text',
                        'default' => '10',
                        'suffix' => $this->l('%')
                    )
                ),
                
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            );
            
            $this->fields_value = array(
                    'new_Commision' => '10',
                    'add' => '1'
                );
        } else if ($this->display == 'edit') {
            $id                = Tools::getValue('id');
            $com_data          = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * from `" . _DB_PREFIX_ . "marketplace_commision` where id=" . $id);
            $cust_com          = $com_data['commision'];
            $cust_id           = $com_data['customer_id'];
            $cust_name         = Db::getInstance()->getRow('SELECT `firstname` from ' . _DB_PREFIX_ . 'customer where id_customer=' . $cust_id);
            $cust_name1        = $cust_name['firstname'];
            $id                = Tools::getValue('id');
            $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Edit Customer Commision')
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Customer Name'),
                        'name' => 'customer_name',
                        'type' => 'text'
                    ),
                    array(
                        'label' => $this->l(' Commision'),
                        'name' => 'edit',
                        'type' => 'hidden'
                    ),
                    array(
                        'label' => $this->l(' Commision'),
                        'name' => 'id_new',
                        'type' => 'hidden'
                    ),
                    array(
                        'label' => $this->l('Enter Customer Commision'),
                        'name' => 'customer_Commision',
                        'type' => 'text',
                        'suffix' => $this->l('%')
                    )
                ),
                
                'submit' => array(
                    'title' => $this->l('   Save   '),
                )
            );
            $this->fields_value = array(
                    'customer_name' => $cust_name1,
                    'customer_Commision' => $cust_com,
                    'edit' => '1',
                    'id_new' => $id
                );
        }
        return parent::renderForm();
    }
    public function processSave()
    {   $add_var = Tools::getValue('add');
        if (isset($add_var))
        {
            if ($add_var == "1")
            {
                $new_commision = Tools::getValue('new_Commision');
                if ($new_commision == "")
                {
                    $this->_errors[] = Tools::displayError('Fields Should not be Empty');
                } 
                elseif(!is_numeric($new_commision))
                {
                    $this->errors[] = Tools::displayError('Commision should be in Integer');
                    $this->display = 'add';
                }
                else
                {
                    $customer_name = Tools::getValue('customer_name');
                    $cust_name1 = Db::getInstance()->getRow('SELECT `firstname` from ' . _DB_PREFIX_ . 'customer where id_customer=' . $customer_name);
                    $cust1_name = $cust_name1['firstname'];
                    $insert1    = Db::getInstance()->insert('marketplace_commision', array(
                        'id' => 'null',
                        'customer_id' => (int) $customer_name,
                        'commision' => (int) $new_commision,
                        'customer_name' => pSQL($cust1_name)
                    ));
                    if ($insert1)
                        Tools::redirectAdmin(self::$currentIndex . '&conf=4&token=' . $this->token);
                }
            }
        }   
        elseif (Tools::getIsset('edit'))
        {
            if (Tools::getValue('edit') == "1")
            {
                $customer_commision = Tools::getValue('customer_Commision');
                if ($customer_commision == "")
                {
                    $this->_errors[] = Tools::displayError('Fields Should not be Empty');
                }
                elseif(!is_numeric($customer_commision))
                {
                    $this->errors[] = Tools::displayError('Commision should be in Integer');
                }
                else
                {
                    $id = Tools::getValue('id_new');
                    $result = Db::getInstance()->update('marketplace_commision', array(
                        'commision' => $customer_commision
                        ), 'id =' . $id);
                    if ($result)
                        Tools::redirectAdmin(self::$currentIndex . '&conf=4&token=' . $this->token);
                }
            }
        }
    }
}
?>