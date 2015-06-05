<?php
class marketplaceSellerrequestModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $link = new Link();
        $smarty_vars = array();
        if (isset($this->context->cookie->id_customer)) 
        {

            $customer_id = $this->context->cookie->id_customer;
            $obj_mp_cust = new MarketplaceCustomer();
            $mp_customer = $obj_mp_cust->findMarketPlaceCustomer($customer_id);
            $smarty_vars = array('login' => 1);

            $img_error = Tools::getValue('img_size_error');
            $mp_error = Tools::getValue('mp_error');
            if ($img_error == 1)
                $smarty_vars = array_merge($smarty_vars, array('img_size_error' => 1));          
            
            if ($mp_error)
                 $smarty_vars = array_merge($smarty_vars, array('mp_error' => $mp_error));

            if ($mp_customer) 
            {
                $is_seller = $mp_customer['is_seller'];
                $smarty_vars = array_merge($smarty_vars, array('is_seller' => $is_seller));
            }

            $smarty_vars = array_merge($smarty_vars, array('phone_digit' => Configuration::get('MP_PHONE_DIGIT'),
                                                            'title_bg_color' => Configuration::get('MP_TITLE_COLOR'),
                                                            'title_text_color' => Configuration::get('MP_TITLE_TEXT_COLOR')));
            $this->context->smarty->assign($smarty_vars);
            
            $seller_email = $this->context->customer->email;
            $seller_name = $this->context->customer->firstname .' '.$this->context->customer->lastname;

            $this->context->smarty->assign("seller_name", $seller_name);           
            $this->context->smarty->assign("seller_email", $seller_email);
            $this->setTemplate('registration.tpl');
        } 
        else 
            Tools::redirect('index.php?controller=authentication&back='.urlencode($link->getModuleLink('marketplace', 'sellerrequest')));

        parent::initContent();
    }

    public function setMedia()
    {
        parent::setMedia();
        $this->addCSS(_MODULE_DIR_.'marketplace/css/registration.css');
        $this->addJS(_MODULE_DIR_.'marketplace/js/mp_form_validation.js');

        if(Configuration::get('PS_JS_THEME_CACHE') == 0)
            $this->addJS(array(
                        _MODULE_DIR_ .'marketplace/js/tinymce/tinymce.min.js',
                        _MODULE_DIR_ .'marketplace/js/tinymce/tinymce_wk_setup.js'
                ));
        Media::addJsDef(array('iso' => $this->context->language->iso_code));
    }
}
?>