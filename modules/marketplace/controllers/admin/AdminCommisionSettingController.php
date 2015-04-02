<?php
	include_once dirname(__FILE__).'/../../classes/MarketplaceCommision.php';
	class AdminCommisionSettingController extends ModuleAdminController 
    {
        public function __construct()
        {
            $this->bootstrap = true;
            $this->className = 'MarketplaceCommision';
            parent::__construct();
    		
    		$obj_comm = new MarketplaceCommision();
    		$obj_comm->customer_id = 0;
    		$glob_com = $obj_comm->findGlobalcomm();        
    		if(!$glob_com)
    			$glob_com  = 10 ;
    		
    		$obj_mp_comm = new MarketplaceCommision();
            $customer_info = $obj_mp_comm->getSellerNotHaveCommissionSet();
    		
            $a = 0;
            $cust11 = array();
            if ($customer_info)
            {
                foreach ($customer_info as $cust1) 
                {
                    $cust11[] = array(
                        'id' => $cust1['id_customer'],
                        'name' => $cust1['email']
                    );
                    $a++;
                }
            }

            $this->fields_options = array(
                'general' => array(
    			
                    'title' => $this->l('Global Commission'),
    				'image' => _MODULE_DIR_.'marketplace/img/commision_setting.gif',
                    'fields' => array(
                        'PS_CP_GLOBAL_COMMISION' => array(
                            'title' => $this->l('Enter Global Commission'),
                            'name' => 'PS_COMMISION_BOX',
                            'validation' => 'isInt',
                            'cast' => 'intval',
                            'type' => 'text',
                            'default' => '10',
                            'suffix' => $this->l('%')
                        )
                    )
                ),
                'general1' => array(
                    'title' => $this->l('Customer Commission '),
    				'image' => _MODULE_DIR_.'marketplace/img/commision_setting.gif',
                    'fields' => array(
                        'PS_COMMISION_CUSTOMER_BOX' => array(
                            'title' => $this->l('Select Customer'),
                            'validation' => 'isInt',
                            'cast' => 'intval',
                            'type' => 'select',
                            'list' => $cust11,
                            'identifier' => 'id'
                        ),
                        'PS_ENTER_CUSTOMER_BOX' => array(
                            'title' => $this->l('Enter Commission'),
                            'type' => 'text',
                            'name' => 'PS_COMMISION_CUST_BOX',
                            'suffix' => $this->l('%')
                        )
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                        'name' => 'submit_commision'
                    )
                )
            );

            if (!$customer_info)
                unset($this->fields_options['general1']);
        }

        public function postProcess()
        {
            if (!$this->loadObject(true))
                return;
            if (Tools::isSubmit('submit_commision'))
                $this->add_commision();
            return parent::postProcess();
        }

        public function add_commision()
        {
            $global_com = Tools::getValue('PS_CP_GLOBAL_COMMISION');
            $cust_id    = Tools::getValue('PS_COMMISION_CUSTOMER_BOX');
            $cust_com   = Tools::getValue('PS_ENTER_CUSTOMER_BOX');
    		$obj_comm = new MarketplaceCommision();
    		$obj_comm->customer_id = $cust_id;
    		//$cust_name = $obj_comm->findAllCustomerInfo();
    		$obj_customer =  new Customer($cust_id);

            $cust_name1 = $obj_customer->firstname;
            if (($cust_com != "") && ($global_com == "")) 
            {
                $messages_cust = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('select `customer_id` from `' . _DB_PREFIX_ . 'marketplace_commision` where customer_id=' . $cust_id);
                if ($messages_cust == "") {
                   Db::getInstance()->insert('marketplace_commision', array(
                        'id' => 'null',
                        'customer_id' => (int) $cust_id,
                        'commision' => $cust_com,
                        'customer_name' => pSQL($cust_name1)
                    ));
                } 
                else 
                {
                    $message_cust_id = $messages_cust['customer_id'];
                    Db::getInstance()->update('marketplace_commision', array(
                        'commision' => $cust_com
                    ), 'customer_id =' . $message_cust_id);
                }
            } 
            elseif (($cust_com == "") && ($global_com != "")) 
            {
                $messages = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('select * from `' . _DB_PREFIX_ . 'marketplace_commision` where customer_id=0');
                if ($messages == "") 
                {
                    Db::getInstance()->insert('marketplace_commision', array(
                        'id' => 'null',
                        'customer_id' => '0',
                        'commision' => $global_com,
                        'customer_name' => 'global'
                    ));
                    Db::getInstance()->update('configuration', array(
                        'value' => $global_com
                    ), 'name="PS_CP_GLOBAL_COMMISION"');
                } 
                else 
                {
                    Db::getInstance()->update('marketplace_commision', array(
                        'commision' => $global_com
                    ), 'customer_id =0');
                    Db::getInstance()->update('configuration', array(
                        'value' => $global_com
                    ), 'name="PS_CP_GLOBAL_COMMISION"');
                }
            } 
            elseif (($cust_com != "") && ($global_com != "")) 
            {
                $messages_cust = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('select * from `' . _DB_PREFIX_ . 'marketplace_commision` where customer_id=' . $cust_id);
                //echo $messages_cust;
                if ($messages_cust == "") 
                {
                    Db::getInstance()->insert('marketplace_commision', array(
                        'id' => 'null',
                        'customer_id' => (int) $cust_id,
                        'commision' => $cust_com,
                        'customer_name' => pSQL($cust_name1)
                    ));
                }
                else 
                {
                    $message_cust_id = $messages_cust['customer_id'];
                    Db::getInstance()->update('marketplace_commision', array(
                        'commision' => $cust_com
                    ), 'customer_id =' . $message_cust_id);
                }
                $messages = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('select * from `' . _DB_PREFIX_ . 'marketplace_commision` where customer_id=0');
                if ($messages == "") 
                {
                    Db::getInstance()->insert('marketplace_commision', array(
                        'id' => 'null',
                        'customer_id' => '0',
                        'commision' => $global_com,
                        'customer_name' => 'global'
                    ));
                    Db::getInstance()->update('configuration', array(
                        'value' => $global_com
                    ), 'name ="PS_CP_GLOBAL_COMMISION"');
                } 
                else 
                {
                    Db::getInstance()->update('marketplace_commision', array(
                        'commision' => $global_com
                    ), 'customer_id =0');
                    Db::getInstance()->update('configuration', array(
                        'value' => $global_com
                    ), 'name ="PS_CP_GLOBAL_COMMISION"');
                }
            }
            $link = $this->context->link->getAdminLink('AdminCommisionSetting');
            Tools::redirectAdmin("$link");
        }
}
?>