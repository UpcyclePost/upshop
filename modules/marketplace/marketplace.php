<?php
if (!defined('_PS_VERSION_'))
    exit;

include_once 'classes/MarketplaceClassInclude.php';

class MarketPlace extends Module
{
    const INSTALL_SQL_FILE = 'install.sql';
    private $_postErrors = array();
    public function __construct()
    {
        $this->name = 'marketplace';
        $this->tab = 'front_office_features';
        $this->version = '1.6.1';
        $this->author = $this->l('Webkul');
        $this->need_instance = 0;
		$this->module_key    = '92e753c36c07c56867a9169292c239e5';
        parent::__construct();
        $this->displayName = $this->l('Marketplace');
        $this->description = $this->l('Add customers as a seller');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module? All marketplace data will be lost.');
    }


    public function getContent()
    {
        $smarty_vars = array('this_path' => $this->_path,
                             'action_url' => Tools::safeOutput($_SERVER['REQUEST_URI']),
                             'product_approve' => Configuration::get('PRODUCT_APPROVE'),
                             'seller_approve' => Configuration::get('SELLER_APPROVE'),
                             'mp_title_color' => Configuration::get('MP_TITLE_COLOR'),
                             'mp_title_text_color' => Configuration::get('MP_TITLE_TEXT_COLOR'),
                             'mp_menu_border' => Configuration::get('MP_MENU_BORDER_COLOR'),
                             'mp_phone_digit' => Configuration::get('MP_PHONE_DIGIT'),
                             'defaultapprove' => true,
                             'adminemail' => true,
                             'mpthemesetting' => true,
                             'submitphonesetting' => true);

        //Updating approval setting
        if (Tools::isSubmit('submitapproval'))
        {
            $approve_prod_val = Tools::getValue('admin_product_approve');
            Configuration::updateValue('PRODUCT_APPROVE', $approve_prod_val);

            $approve_seller_val = Tools::getValue('admin_seller_approve');
            Configuration::updateValue('SELLER_APPROVE', $approve_seller_val);
            
            $product_approval = Configuration::get('PRODUCT_APPROVE');
            if ($product_approval)
                $smarty_vars = array_merge($smarty_vars, array('product_approve' => $product_approval));

            $seller_approval = Configuration::get('SELLER_APPROVE');
            if ($seller_approval)
                $smarty_vars = array_merge($smarty_vars, array('seller_approve' => $seller_approval));

            $success = $this->displayConfirmation($this->l('Approval Settings updated.'));
            $smarty_vars = array_merge($smarty_vars, array('success' => $success));
        }
        
        //Updating superadmin email
        if (Tools::isSubmit('submitsuperadminemail'))
        {
            $superadmin = Tools::getValue('superadmin_email');
            if (!Validate::isEmail($superadmin))
            {
                $error = $this->displayError($this->l('Invalid SuperAdmin email address.'));
                $smarty_vars = array_merge($smarty_vars, array('error' => $error));
            }
            else
            {
                Configuration::updateValue('MP_SUPERADMIN_EMAIL', $superadmin);
                $success = $this->displayConfirmation($this->l('SuperAdmin email address updated.'));
                $smarty_vars = array_merge($smarty_vars, array('success' => $success));
            }
            $smarty_vars = array_merge($smarty_vars, array('adminemail' => true));

        }

        //Updating theme setting
        if (Tools::isSubmit('submitthemesetting'))
        {
            $title_bg_color = Tools::getValue('mp_title_color');
            if (trim($title_bg_color) == '')
                $title_bg_color = "#333333";

            $title_text_color = Tools::getValue('mp_title_text_color');
            if (trim($title_text_color) == '')
                $title_text_color = "#FFFFFF";

            Configuration::updateValue('MP_TITLE_COLOR', $title_bg_color);
            Configuration::updateValue('MP_TITLE_TEXT_COLOR', $title_text_color);

            $success = $this->displayConfirmation($this->l('Theme Settings updated.'));
            $smarty_vars = array_merge($smarty_vars, array('success' => $success));

            $smarty_vars = array_merge($smarty_vars, array('mp_title_color' => $title_bg_color,
                                                           'mp_title_text_color' => $title_text_color));
        }

        //update phone number digit
        if (Tools::isSubmit('submitphonesetting'))
        {
            $phone_digit = Tools::getValue('mp_phone_digit');
            if (!Validate::isInt($phone_digit))
            {
                $error = $this->displayError($this->l('Invalid input. Number required.'));
                $smarty_vars = array_merge($smarty_vars, array('error' => $error));
            }
            else
            {
                Configuration::updateValue('MP_PHONE_DIGIT', $phone_digit);
                $success = $this->displayConfirmation($this->l('Phone number digit updated.'));
                $smarty_vars = array_merge($smarty_vars, array('success' => $success,
                                                                'mp_phone_digit' => $phone_digit));
            }
        }
         
        $superadminemail = Configuration::get('MP_SUPERADMIN_EMAIL');
        if (!$superadminemail)
        {
            $obj_emp = new Employee(1);
            $smarty_vars = array_merge($smarty_vars, array('superadminemail' => false,
                                                           'employee_mailid' => $obj_emp->email));
        }
        else
            $smarty_vars = array_merge($smarty_vars, array('superadminemail' => $superadminemail));


        $this->context->smarty->assign($smarty_vars);
        return $this->display(__FILE__, './views/templates/admin/admin.tpl');
    }
    
    public function hookDisplayMpmenuhook() {
        return $this->display(__FILE__, 'mpmenu.tpl');
    }
    
    public function hookDisplayMpmyaccountmenuhook()
    {
        $link            = new link();
        $customer_id     = $this->context->customer->id;
        
        $obj_marketplace_seller = new SellerInfoDetail();
        $already_request = $obj_marketplace_seller->getMarketPlaceSellerIdByCustomerId($customer_id);
        
        if ($already_request) {
            $is_seller = $already_request['is_seller'];
            $this->context->smarty->assign("is_seller", $is_seller);
            if ($is_seller == 1) 
            {
                $obj_marketplace_shop = new MarketplaceShop();
                $market_place_shop = $obj_marketplace_shop->getMarketPlaceShopInfoByCustomerId($customer_id);
                $id_shop   = $market_place_shop['id'];
                $obj_ps_shop = new MarketplaceShop($id_shop);
                $name_shop = $obj_ps_shop->link_rewrite;
                $param = array('shop' => $id_shop);           
                $payment_detail    = $link->getModuleLink('marketplace', 'customerPaymentDetail',$param);
                $link_store        = $link->getModuleLink('marketplace', 'shopstore',array('shop'=>$id_shop,'shop_name'=>$name_shop));
                $link_collection   = $link->getModuleLink('marketplace', 'shopcollection',array('shop'=>$id_shop,'shop_name'=>$name_shop));
                $link_profile      = $link->getModuleLink('marketplace', 'shopprofile',$param);
                $add_product       = $link->getModuleLink('marketplace', 'addproduct',$param);
                $account_dashboard = $link->getModuleLink('marketplace', 'marketplaceaccount',$param);
                $seller_profile    = $link->getModuleLink('marketplace', 'sellerprofile',$param);
                $edit_profile    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>2,'edit-profile'=>1));
                $product_list    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>3));
                $my_order    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>4));
                $payment_details    = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'id_cus'=>$customer_id,'l'=>5));
                        
                $this->context->smarty->assign("id_shop", $id_shop);
                $this->context->smarty->assign("id_customer", $customer_id);
                $this->context->smarty->assign("payment_detail", $payment_detail);
                $this->context->smarty->assign("link_store", $link_store);
                $this->context->smarty->assign("link_collection", $link_collection);
                $this->context->smarty->assign("link_profile", $link_profile);
                $this->context->smarty->assign("add_product", $add_product);
                $this->context->smarty->assign("account_dashboard", $account_dashboard);
                $this->context->smarty->assign("seller_profile", $seller_profile);
                $this->context->smarty->assign("edit_profile", $edit_profile);
                $this->context->smarty->assign("product_list", $product_list);
                $this->context->smarty->assign("my_order", $my_order);
                $this->context->smarty->assign("payment_details", $payment_details);
            }
        } else {
            $this->context->smarty->assign("is_seller", -1);
            $new_link1 = $link->getModuleLink('marketplace', 'sellerrequest');
            $this->context->smarty->assign("new_link1", $new_link1);
        }
        return $this->display(__FILE__, 'mpmyaccountmenu.tpl');
    }
    
    public function hookDisplayMpOrderheaderlefthook() {
        return $this->display(__FILE__, 'orderheaderleft.tpl');
    }
    
   /* public function hookDisplayMpOrderheaderrighthook($params) {
        //return true;
        return $this->display(__FILE__, 'orderheaderright.tpl');
    }*/
    
    public function hookDisplayMpbottomordercustomerhook() {
        return $this->display(__FILE__, 'bottomordercustomer.tpl');
    }
    public function hookDisplayMpbottomorderstatushook() {
        return $this->display(__FILE__, 'bottomorderstatus.tpl');
    }
    public function hookDisplayMpbottomorderproductdetailhook() {
        return $this->display(__FILE__, 'bottomorderproductdetail.tpl');
    }
    
    public function hookDisplayMpordershippinghook() {
        return $this->display(__FILE__, 'ordershipping.tpl');
    }
    public function hookDisplayMpordershippinglefthook() {
        return $this->display(__FILE__, 'ordershippingleft.tpl');
    }
    public function hookDisplayMpordershippingrighthook() {
        return $this->display(__FILE__, 'ordershippingright.tpl');
    }
    
    public function hookDisplayMpdashboardtophook() {
        return $this->display(__FILE__, 'dashboardtop.tpl');
    }
    
   /* public function hookDisplayMpdashboardbottomhook($params) {
        return $this->display(__FILE__, 'dashboardbottom.tpl');
    }*/
    
    public function hookDisplayMpsplefthook() {
        return $this->display(__FILE__, 'sellerprofilelefthook.tpl');
    }
    
    public function hookDisplayMpspcontentbottomhook() {
        return $this->display(__FILE__, 'sellerprofilecontentbottom.tpl');
    }
    public function hookDisplayMpsprighthook() {
        return $this->display(__FILE__, 'sellerprofilerighthook.tpl');
    }
    
    public function hookDisplayMpshoplefthook() {
        return $this->display(__FILE__, 'shoplefthook.tpl');
    }
    
    public function hookDisplayMpshopcontentbottomhook() {
        return $this->display(__FILE__, 'shopcontentbottom.tpl');
    }
    public function hookDisplayMpshoprighthook() {
        return $this->display(__FILE__, 'shoprighthook.tpl');
    }
    
    public function hookDisplayMpcollectionlefthook() {
        return $this->display(__FILE__, 'collectionlefthook.tpl');
    }
    
    public function hookDisplayMpcollectionfooterhook() {
        return $this->display(__FILE__, 'collectionfooterhook.tpl');
    }
    
    public function hookDisplayMpaddproductfooterhook() {
        return $this->display(__FILE__, 'addcustomefieldtoproduct.tpl');
    }
    
    public function hookDisplayMpupdateproductfooterhook() {
        return $this->display(__FILE__, 'updatecustomefieldtoproduct.tpl');
    }
    public function hookDisplayMpshoprequestfooterhook() {
        return $this->display(__FILE__, 'customefieldtoshoprequest.tpl');
    }
    
    public function hookDisplayMpshopaddfooterhook() {
        return $this->display(__FILE__, 'customefieldtoshopedit.tpl');
    }
    
    //product description hook
    public function hookDisplayMpproductdescriptionheaderhook() {
        return $this->display(__FILE__, 'productdetailheaderhook.tpl');
    }
    public function hookDisplayMpproductdescriptionfooterhook() {
        return $this->display(__FILE__, 'productdetailfooterhook.tpl');
    }
    
    public function hookDisplayMpproductdescriptioncontenthook() {
        //return true;
    }
    //product detail tab
    /*public function hookDisplayMpproductdetailheaderhook($params) {
        return $this->display(__FILE__, 'productdetailheader.tpl');
    }*/
    
    public function hookDisplayMpproductdetailfooterhook() {
        return $this->display(__FILE__, 'productdetailfooterhook.tpl');
    }
    
    //payment detail tab
    public function hookDisplayMppaymentdetailfooterhook() {
        return $this->display(__FILE__, 'paymentdetailfooterhook.tpl');
    }
    
    //seller detail tab
    public function hookDisplayMpsellerinfobottomhook() {
        //return true;
    }
    
    public function hookDisplayMpsellerleftbottomhook() {
        //return true;
    }
    
    public function hookActionAddproductExtrafield() {
        //return true;
    }
    public function hookActionUpdateproductExtrafield() {
        //return true;
    }
    public function hookActionAddshopExtrafield() {
        //return true;
    }
    public function hookActionUpdateshopExtrafield() {
        //return true;
    }

    public function hookDisplayOrderConfirmation($params)
    {
        $obj_mpsellerorder = new MarketplaceSellerOrders();
        $obj_order_com = new MarketplaceOrderCommission();
        $obj_mpcommission = new MarketplaceCommision();

        $id_lang = Context::getContext()->cookie->id_lang;
        $reference = $params['objOrder']->reference;
        $order_detail = Db::getInstance()->executeS("SELECT * from `" . _DB_PREFIX_ . "orders` where `reference`='".$reference."'");
        foreach($order_detail as $data)
        {
            $id_buyer = $data['id_customer'];
            $id_order = $data['id_order'];
            $is_allready_calc = Db::getInstance()->executeS("SELECT *  from `" . _DB_PREFIX_ . "marketplace_commision_calc` where `id_order`=" . $id_order);
            
            $currency = Db::getInstance()->getRow("SELECT `id_currency`  from `" . _DB_PREFIX_ . "orders` where `id_order`=" . $id_order);
            
            if (!$is_allready_calc)
            {
                $customer = Db::getInstance()->executeS("SELECT * from `" . _DB_PREFIX_ . "marketplace_shop_product` msp join `" . _DB_PREFIX_ . "order_detail` ordd on (ordd.`product_id`=msp.`id_product`) join `" . _DB_PREFIX_ . "marketplace_seller_product` mssp on(mssp.`id` = msp.`marketplace_seller_id_product`) join `" . _DB_PREFIX_ . "marketplace_customer` mc on(mc.`marketplace_seller_id` = mssp.`id_seller`) join `" . _DB_PREFIX_ . "customer` c on (c.`id_customer` = mc.`id_customer`) and ordd.`id_order`=" . $id_order);
                
                //for commision
                $d        = 0;
                $commision_array = array();
                foreach ($customer as $customer2)
                {
                    $cust_com = Db::getInstance()->getRow('SELECT `commision` from ' . _DB_PREFIX_ . 'marketplace_commision where customer_id=' . $customer2['id_customer']);
                    if (!$cust_com) 
                    {
                        $cust_com1 = Db::getInstance()->getRow('SELECT `commision` from ' . _DB_PREFIX_ . 'marketplace_commision where customer_id=0');
                        if (!$cust_com1) 
                        {
                            $cust_com11    = Db::getInstance()->getRow('SELECT `value` from ' . _DB_PREFIX_ . 'configuration where name="PS_CP_GLOBAL_COMMISION"');
                            $global_com    = $cust_com11['value'];
                            $customer_comm = $global_com;
                        } 
                        else 
                        {
                            $customer_comm = $cust_com1['commision'];
                        }
                    } 
                    else 
                    {
                        $customer_comm = $cust_com['commision'];
                    }
                    $commision_array[] = $customer_comm;
                    $d++;
                }
                $count = count($customer);
                $admin_total_commision = 0.00;
                for ($i = 0; $i < $count; $i++)
                {
                    //seller commision
                    $seller_comm = 100 - $commision_array[$i];
                    
                    $commision = (($customer[$i]['product_price'] * $customer[$i]['product_quantity']) * $commision_array[$i]) / 100;
                    $seller_commission = ((($customer[$i]['product_price'] * $customer[$i]['product_quantity']) * $seller_comm) / 100);

                    //Total tax by id_order_detail from order_detail_payment table
                    $total_tax = $obj_mpcommission->getTaxByIdOrderDetail($customer[$i]['id_order_detail']);

                    if (Configuration::get('MP_TAX_COMMISSION') == 'admin')
                        $commision = $commision + $total_tax;
                    else if (Configuration::get('MP_TAX_COMMISSION') == 'commission')
                    {
                        $tax_to_admin = ($total_tax * $commision_array[$i]) / 100;
                        $tax_to_seller = $total_tax - $tax_to_admin;
                        
                        $commision = $commision + $tax_to_admin;
                        $seller_commission = $seller_commission + $tax_to_seller;
                    }
                    else if (Configuration::get('MP_TAX_COMMISSION') == 'seller')
                        $seller_commission = $seller_commission + $total_tax;

                    $admin_total_commision = $admin_total_commision +  $commision;

                    Db::getInstance()->insert('marketplace_commision_calc', array(
                        'product_id' => $customer[$i]['id_product'],
                        'customer_id' => $customer[$i]['id_customer'],
                        'product_name' => $customer[$i]['product_name'],
                        'customer_name' => $customer[$i]['firstname'],
                        'price' => $customer[$i]['total_price_tax_incl'],
                        'quantity' => $customer[$i]['product_quantity'],
                        'commision' => $commision,
                        'id_order' => $id_order
                    ));

                    $commision_calc_latest_id = Db::getInstance()->Insert_ID();
                    
                    $mp_product_id = $customer[$i]['marketplace_seller_id_product'];
                    $obj_seller_product = new SellerProductDetail($mp_product_id);
                    $obj_seller_product->quantity = $obj_seller_product->quantity-$customer[$i]['product_quantity'];
                    $obj_seller_product->save();

                    Hook::exec('actionSellerPaymentTransaction',
                        array('commision' => $seller_commission,
                            'id_seller'=>$customer[$i]['id_customer'],
                            'id_currency'=>$currency['id_currency'],
                            'commision_calc_latest_id'=>$commision_calc_latest_id,
                            'product_price'=>$customer[$i]['product_price'],
                            'product_quantity'=>$customer[$i]['product_quantity']
                        ));
                }
                if ($count > 0)
                {
                    $shipping_amt = $obj_mpsellerorder->getOrderShipping($id_order);
                    $obj_order_com->id_order = $id_order;
                    $obj_order_com->shipping_amt = $shipping_amt;
                    $obj_order_com->tax = Configuration::get('MP_TAX_COMMISSION');
                    $obj_order_com->shipping = 'admin';
                    $obj_order_com->id_customer = $id_buyer;
                    $obj_order_com->admin_commission = $admin_total_commision + $shipping_amt;

                    $obj_order_com->add();
                }
           }
            
            //for seller email

            $obj_order_detail = new OrderDetail();
            //$product_details = $obj_order_detail->getList($params['objOrder']->id);
			$product_details = $obj_order_detail->getList($data['id_order']);
            $obj_mp_prod = new SellerProductDetail();
            $obj_mp_seller = new SellerInfoDetail();

			$sql = "SELECT sum(ord.`total_products`) as `total_products`, sum(ord.`total_shipping`) as `total_shipping`, sum(ord.`total_paid`) as `total_paid` 
			from `" . _DB_PREFIX_ . "orders` ord where ord.`reference`='".$reference."'";
			
                    echo "<pre>$seller_shop_name";
                    print_r($sql);
                    echo "</pre>";

			$m_orderheader =  Db::getInstance()->executeS($sql);
			
                    echo "<pre>m_orderheader";
                    print_r($m_orderheader);
                    echo "</pre>";


            $seller_list = array();

                    echo "<pre>product_details : ";
                    print_r($product_details);
                    echo "</pre>";


            foreach($product_details as $product)
            {
                $mp_product_id = $obj_mp_prod->checkProduct($product['product_id']);

                    echo "<pre>mp_product_id : ";
                    print_r($mp_product_id);
                    echo "</pre>";


                if($mp_product_id)
                {


                   $mp_seller_id = $obj_mp_prod->getSellerIdByProduct($mp_product_id);

                    echo "<pre>mp_seller_id : ";
                    print_r($mp_seller_id);
                    echo "</pre>";
                   
                   if(!array_key_exists($mp_seller_id, $seller_list))
                   {
                        $seller_list[$mp_seller_id]['products'][] = $product['product_id'];
                        $seller_list[$mp_seller_id]['quantity'][] = $product['product_quantity'];
                        $seller_list[$mp_seller_id]['unit_price'][] = Tools::displayPrice($product['unit_price_tax_excl'], $this->context->currency, false);
                        $seller_list[$mp_seller_id]['total_price'][] = Tools::displayPrice($product['unit_price_tax_excl'] * $product['product_quantity'], $this->context->currency, false);
                   }
                   else
                   {
                        $count = count($seller_list[$mp_seller_id]['products']);
                        $seller_list[$mp_seller_id]['products'][$count] = $product['product_id'];
                        $seller_list[$mp_seller_id]['quantity'][$count] = $product['product_quantity'];
                        $seller_list[$mp_seller_id]['unit_price'][] = Tools::displayPrice($product['unit_price_tax_excl'], $this->context->currency, false);
                        $seller_list[$mp_seller_id]['total_price'][] = Tools::displayPrice($product['unit_price_tax_excl'] * $product['product_quantity'], $this->context->currency, false);
                   }
                }
            }

                    echo "<pre>count";
                    print_r(count($seller_list));
                    echo "</pre>";

            if(count($seller_list))
            {
                foreach($seller_list  as $key => $value)
                {
                    $customer_info = $obj_mp_prod->getCustomerInfo($this->context->customer->id);
                    $id_address_delivery = $obj_mp_prod->getDeliverAddress($id_order); 
                    $shipping_details = $obj_mp_prod->getShippingInfo($id_address_delivery);
                    $state = $obj_mp_prod->getState($shipping_details['id_state']);
                    $country = $obj_mp_prod->getCountry($shipping_details['id_country']);
                    $customer_id = $obj_mp_prod->getCustomerIdBySellerId($key);
                    $seller_shop = $obj_mp_prod->getShopBySellerId($key);
                    $seller_shop_id = $seller_shop['id']; 
                    $seller_shop_name = $seller_shop['shop_name'];
                    $seller_info = $obj_mp_prod->getSellerInfo($customer_id);

                    echo "<pre>$seller_shop_name";
                    print_r($seller_shop_name);
                    echo "</pre>";


                    $produst_details = array();
                    $i = 0;
                    foreach ($value['products'] as $id_product)
                    {
                        $obj_prod = new Product($id_product, false, $id_lang);
                        $produst_details[$i]['name'] = $obj_prod->name;
                        $produst_details[$i]['qty'] = $value['quantity'][$i];
                        $produst_details[$i]['unit_price'] = $value['unit_price'][$i];
                        $produst_details[$i]['total_price'] = $value['total_price'][$i];
                        $i++;
                    }

                    $customer_name = $customer_info['firstname'].' '.$customer_info['lastname'];
                    $ship_address_name = $shipping_details['firstname'].' '.$shipping_details['lastname'];
                    $ship_address = $shipping_details['address1'].', '.$shipping_details['address2'];
                    $product_html = $obj_mp_seller->getMpEmailTemplateContent('mp_order_product_list.tpl', Mail::TYPE_HTML, $produst_details);

                    $templateVars = array('{seller_firstname}' => $seller_info['firstname'],
                                          '{seller_lastname}' => $seller_info['lastname'],
                                          '{website}' => $seller_info['website'],                                          
                                          '{seller_shop_name}' => $seller_shop_name,
                                          '{seller_shop_id}' => $seller_shop_id,
                                          '{customer_name}' => $customer_name,
                                          '{customer_email}' => $customer_info['email'],
                                          '{ship_address_name}' => $ship_address_name,
                                          '{ship_address}' => $ship_address,
                                          '{city}' => $shipping_details['city'],
                                          '{state}' => $state,
                                          '{country}' => $country,
                                          '{zipcode}' =>$shipping_details['postcode'],
                                          '{phone}' => $shipping_details['phone_mobile'],
                                          '{total_products}' => $m_orderheader['total_products'],
                                          '{total_shipping}' => $m_orderheader['total_shipping'],                                          
                                          '{total_paid}' => $m_orderheader['total-paid'],
                                          '{product_html}' => $product_html
                                          );

                    echo "<pre>templateVars";
                    print_r($templateVars);
                    echo "</pre>";


                    $template = 'mp_order';
                    $subject = 'Order Received : Reference # '. $reference;
                    $to = $seller_info['email'];
                    $temp_path = _PS_MODULE_DIR_.'marketplace/mails/';
                    Mail::Send($id_lang, $template, $subject, $templateVars, $to, null, null, 'Marketplace',
                                null, null, $temp_path, false, null, null);
                }
            }
        }
            
    }
    public function hookDisplayCustomerAccount()
    {
        return $this->display(__FILE__, 'customeraccount.tpl');
    }
    
    public function hookDisplayProductTab()
    {
        $id_product         = Tools::getValue('id_product');
        $obj_marketplace_product = new SellerProductDetail();
        $isproductassociatetomarketplace = $obj_marketplace_product->getMarketPlaceShopProductDetail($id_product);
        if ($isproductassociatetomarketplace)
            return $this->display(__FILE__, 'seller_details_tab.tpl');
    }
    
    public function hookDisplayProductTabContent()
    {
        $link = new link();
        $id_product = Tools::getValue('id_product');
        $obj_marketplace_product = new SellerProductDetail();
        $seller_shop_detail = $obj_marketplace_product->getMarketPlaceShopProductDetail($id_product);
        
        if ($seller_shop_detail)
        {
            $this->context->controller->addCSS($this->_path.'views/css/productsellerdetails.css');
            $this->context->controller->addJS($this->_path.'views/js/productsellerdetails.js');
            $this->context->controller->addJS(_PS_JS_DIR_.'validate.js');

            $id_shop         = $seller_shop_detail['id_shop'];
            $mkt_seller_pro  = $obj_marketplace_product->getMarketPlaceProductInfo($seller_shop_detail['marketplace_seller_id_product']);
           
            $product_name    = $mkt_seller_pro['product_name'];
          
            $mkt_shop = $obj_marketplace_product->getMarketPlaceShopDetail($id_shop);
            $id_customer     = $mkt_shop['id_customer'];
            
            $obj_marketplace_seller = new SellerInfoDetail();
            $mkt_customer = $obj_marketplace_seller->getMarketPlaceSellerIdByCustomerId($id_customer);
           
            $seller_id       = $mkt_customer['marketplace_seller_id'];
            
            $mkt_seller_info = $obj_marketplace_seller->getmarketPlaceSellerInfo($seller_id);     
                    
            $email           = $mkt_seller_info['business_email'];
            $facebook_id     = $mkt_seller_info['facebook_id'];
            $twitter_id      = $mkt_seller_info['twitter_id'];
            
            $param = array('shop'=>$id_shop);
            $obj_ps_shop = new MarketplaceShop($id_shop);
            $name_shop = $obj_ps_shop->link_rewrite;
            $link_store        = $link->getModuleLink('marketplace', 'shopstore',array('shop'=>$id_shop,'shop_name'=>$name_shop));
            $link_collection   = $link->getModuleLink('marketplace', 'shopcollection',array('shop'=>$id_shop,'shop_name'=>$name_shop));
            $link_profile       = $link->getModuleLink('marketplace', 'sellerprofile',$param);
            $link_ask_que       = $link->getModuleLink('marketplace', 'shopaskque',$param);

            if (isset($this->context->cookie->id_customer))
            {
                $id_customer = $this->context->cookie->id_customer;
                $this->context->smarty->assign("id_customer", $id_customer);
            }
            
            
            $this->context->smarty->assign("mkt_seller_info", $mkt_seller_info);
            $this->context->smarty->assign("product_name", $product_name);
            $this->context->smarty->assign("id_shop", $id_shop);
            $this->context->smarty->assign("id_product", $id_product);
            $this->context->smarty->assign("link_store", $link_store);
            $this->context->smarty->assign("link_collection", $link_collection);
            $this->context->smarty->assign("seller_id", $seller_id);
            $this->context->smarty->assign("seller_email", $email);
            $this->context->smarty->assign("facebook_id", $facebook_id);
            $this->context->smarty->assign("twitter_id", $twitter_id);
            $this->context->smarty->assign("link_profile", $link_profile);
            $this->context->smarty->assign("link_ask_que", $link_ask_que);
            return $this->display(__FILE__, 'seller_details_content.tpl');
        }    
    }
    
    public function callInstallTab()
    {
        $this->installTab('AdminMarketplaceManagement', 'Marketplace Management');
        $this->installTab('AdminSellerInfoDetail', 'Manage Seller Profile', 'AdminMarketplaceManagement');
        $this->installTab('AdminSellerProductDetail', 'Manage Seller Product', 'AdminMarketplaceManagement');
        $this->installTab('AdminCommisionSetting', 'Manage Commission Setting', 'AdminMarketplaceManagement');
        $this->installTab('AdminCustomerCommision', 'Manage Seller Commission', 'AdminMarketplaceManagement');
        $this->installTab('AdminSellerOrders', 'Manage Seller Orders', 'AdminMarketplaceManagement');
        $this->installTab('AdminPaymentMode', 'Manage Payment Mode', 'AdminMarketplaceManagement');
        $this->installTab('AdminReviews', 'Manage Seller Reviews', 'AdminMarketplaceManagement');
        return true;
    }
    
    public function installTab($class_name,$tab_name,$tab_parent_name=false) 
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $class_name;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang)
            $tab->name[$lang['id_lang']] = $tab_name;

        if($tab_parent_name)
            $tab->id_parent = (int)Tab::getIdFromClassName($tab_parent_name);
        else
            $tab->id_parent = 0;
        
        $tab->module = $this->name;
        return $tab->add();
    }
    
    public function insertConfg()
    {
        Configuration::updateValue('PS_CP_GLOBAL_COMMISION', 10);
        return true;
    }
    
    public function install()
    {
        if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
            return (false);
        else if (!$sql = Tools::file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
            return (false);

        $sql = str_replace(array(
            'PREFIX_',
            'ENGINE_TYPE'
        ), array(
            _DB_PREFIX_,
            _MYSQL_ENGINE_
        ), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", $sql);

        foreach ($sql as $query)
            if ($query)
                if (!Db::getInstance()->execute(trim($query)))
                    return false;

         if (!parent::install()
            || !$this->registerHook('displayleftColumn')
            || !$this->registerHook('displaycustomerAccount')
            || !$this->registerHook('orderConfirmation')
            || !$this->registerHook('displayProductTab')
            || !$this->registerHook('displayProductTabContent')
            || !$this->callInstallTab()
            || !$this->insertConfg()
            || !$this->registerHook('displayMenuhook')
            || !$this->registerHook('displayMpmenuhook')
            || !$this->registerHook('displayMpmyaccountmenuhook')
            || !$this->registerHook('displayMpOrderheaderlefthook')
            || !$this->registerHook('displayMpOrderheaderrighthook')
            || !$this->registerHook('displayMpbottomordercustomerhook')
            || !$this->registerHook('displayMpbottomorderstatushook')
            || !$this->registerHook('displayMpbottomorderproductdetailhook')
            || !$this->registerHook('displayMpordershippinghook')
            || !$this->registerHook('displayMpordershippinglefthook')
            || !$this->registerHook('displayMpordershippingrighthook')
            || !$this->registerHook('displayMpdashboardtophook')
            || !$this->registerHook('displayMpdashboardbottomhook')
            || !$this->registerHook('displayMpsplefthook')
            || !$this->registerHook('displayMpspcontentbottomhook')
            || !$this->registerHook('displayMpsprighthook')
            || !$this->registerHook('displayMpshoplefthook')
            || !$this->registerHook('displayMpshopcontentbottomhook')
            || !$this->registerHook('displayMpshoprighthook')
            || !$this->registerHook('displayMpcollectionlefthook')
            || !$this->registerHook('displayMpcollectionfooterhook')
            || !$this->registerHook('displayMpaddproductfooterhook')
            || !$this->registerHook('displayMpupdateproductfooterhook')
            || !$this->registerHook('displayMpshoprequestfooterhook')
            || !$this->registerHook('displayMpshopaddfooterhook')
            || !$this->registerHook('displayMpproductdetailheaderhook')
            || !$this->registerHook('displayMpproductdetailfooterhook')
            || !$this->registerHook('displayMppaymentdetailfooterhook')
            || !$this->registerHook('displayMpsellerinfobottomhook')
            || !$this->registerHook('displayMpsellerleftbottomhook')
            || !$this->registerHook('actionAddproductExtrafield')
            || !$this->registerHook('actionUpdateproductExtrafield')
            || !$this->registerHook('actionAddshopExtrafield')
            || !$this->registerHook('actionUpdateshopExtrafield')
         )
            return false;

        Configuration::updateValue('MP_TAX_COMMISSION', 'admin');
        Configuration::updateValue('PRODUCT_APPROVE', 'admin');
        Configuration::updateValue('SELLER_APPROVE', 'admin');

        Configuration::updateValue('MP_TITLE_COLOR', '#333333');
        Configuration::updateValue('MP_TITLE_TEXT_COLOR', '#FFFFFF');
        Configuration::updateValue('MP_PHONE_DIGIT', 10);

        return true;
    }
    
    public function deleteTables()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'marketplace_seller_product`,
            `'._DB_PREFIX_.'marketplace_seller_product_category`,
            `'._DB_PREFIX_.'marketplace_seller_info`,
            `'._DB_PREFIX_.'marketplace_shop`,
            `'._DB_PREFIX_.'marketplace_shop_product`,
            `'._DB_PREFIX_.'marketplace_customer`,
            `'._DB_PREFIX_.'marketplace_product_image`,
            `'._DB_PREFIX_.'marketplace_commision_calc`,
            `'._DB_PREFIX_.'marketplace_commision`,
            `'._DB_PREFIX_.'marketplace_payment_mode`,
            `'._DB_PREFIX_.'marketplace_order_commision`,
            `'._DB_PREFIX_.'marketplace_customer_payment_detail`,
            `'._DB_PREFIX_.'seller_reviews`');
    }
    
    public function deleteConfig()
    {
        $key_name = array('PS_CP_GLOBAL_COMMISION', 'market_place_seller_profile_id',
                          'PRODUCT_APPROVE', 'SELLER_APPROVE',
                          'MP_TITLE_COLOR', 'MP_TITLE_TEXT_COLOR',
                          'MP_PHONE_DIGIT');

        foreach ($key_name as $key)
            if (!Configuration::deleteByName($key))
                return false;

        return true;
    }
        
    public function callUninstallTab()
    {
        $this->uninstallTab('AdminReviews');
        $this->uninstallTab('AdminPaymentMode');
        $this->uninstallTab('AdminCustomerCommision');
        $this->uninstallTab('AdminCommisionCalc');
        $this->uninstallTab('AdminCommisionSetting');
        $this->uninstallTab('AdminSellerOrders');
        $this->uninstallTab('AdminSellerProductDetail');
        $this->uninstallTab('AdminSellerInfoDetail');
        $this->uninstallTab('AdminMarketplaceManagement');
        return true;
    }
        
    public function uninstallTab($class_name)
    {
        $id_tab = (int)Tab::getIdFromClassName($class_name);
        if ($id_tab)
        {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        else
            return false;
    }
    
    public function reset()
    {
        if (!$this->uninstall(false))
            return false;
        if (!$this->install(false))
            return false;
        return true;
    }
   
    public function uninstall($keep = true)
    {
        if(!parent::uninstall() || ($keep && !$this->deleteTables())
            || !$this->callUninstallTab()
            || !$this->deleteConfig()
            || !Configuration::deleteByName('MP_SUPERADMIN_EMAIL'))
            return false;
        return true;
    }
}
?>
