<?php
if (!defined('_PS_VERSION_'))
    exit;
include_once 'modules/marketplace/classes/MarketplaceClassInclude.php';
class marketplaceEditProfileModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $customer_id  = $this->context->cookie->id_customer;
        $link = new Link();
        $id_shop = Tools::getValue('update_id_shop');
        $market_seller_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `marketplace_seller_id` from `" . _DB_PREFIX_ . "marketplace_customer` where id_customer =" . $customer_id . "");

        if (Tools::getValue('update_seller_name'))
            $seller_name = Tools::getValue('update_seller_name');
        
        if (Tools::getValue('update_shop_name'))
            $shop_name = Tools::getValue('update_shop_name');
        
        if (Tools::getValue('update_business_email'))
            $business_email = Tools::getValue('update_business_email');
        
        if (Tools::getValue('update_phone')) 
            $phone = Tools::getValue('update_phone');
        
        if (Tools::getValue('update_fax'))
            $fax = Tools::getValue('update_fax');
        
        if (Tools::getValue('update_address'))
            $address = Tools::getValue('update_address');
        
        if (Tools::getValue('update_about_shop'))
            $about_us = trim(Tools::getValue('update_about_shop'));
        
        if (Tools::getValue('update_twitter_id'))
            $twitter_id = trim(Tools::getValue('update_twitter_id'));
        
        if (Tools::getValue('update_facbook_id'))
            $facebook_id = trim(Tools::getValue('update_facbook_id'));
        
        
        $market_place_seller_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "marketplace_seller_info` where id =" . $market_seller_id['marketplace_seller_id'] . "");
        if ($_FILES['update_shop_logo']["size"] != 0)
        {
            list($shop_width, $shop_height) = getimagesize($_FILES['update_shop_logo']["tmp_name"]);
            if($shop_width < 200 || $shop_height < 200 )
            {
                $param = array('shop' => $id_shop, 'l' => 2, 'edit-profile' => 0, 'img_shop' => 1);
                $redirect_link = $link->getModuleLink('marketplace', 'marketplaceaccount',$param);
                Tools::redirect($redirect_link);
            }
        }

        if ($_FILES['update_seller_logo']["size"] != 0)
        {
            list($seller_width, $seller_height) = getimagesize($_FILES['update_seller_logo']['tmp_name']);
            if($seller_width < 200 || $seller_height < 200)
            {
              $param = array('shop' => $id_shop, 'l' => 2, 'edit-profile' => 0, 'img_seller' => 1);
              $redirect_link = $link->getModuleLink('marketplace', 'marketplaceaccount',$param);
              Tools::redirect($redirect_link);
            }
            else 
            { 
                if ($_FILES['update_seller_logo']['error'] == 0) 
                {
                    $validExtensions1 = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
                    $fileExtension1   = strrchr($_FILES['update_seller_logo']['name'], ".");
                    if (in_array($fileExtension1, $validExtensions1)) 
                    {
                        $newpath = _PS_MODULE_DIR_.'marketplace/img/seller_img/';
                        $width = '200';
                        $height = '200';
                        ImageManager::resize($_FILES['update_seller_logo']['tmp_name'],$newpath.$market_seller_id['marketplace_seller_id'].'.jpg',$width,$height);
                    }
                }
            }           
        }

        $market_place_shop_name   = $market_place_seller_info['shop_name'];
        if ($_FILES['update_shop_logo']["size"] == 0) 
        {
            if ($market_place_shop_name!=$shop_name) 
            {
                $shop_prev_logo_name=$market_seller_id['marketplace_seller_id']."-".$market_place_shop_name;
                $shop_prev_logo_name1=glob('modules/marketplace/img/shop_img/'.$shop_prev_logo_name.'.*');
                $shop_image_path='modules/marketplace/img/shop_img/';
                $is_shop_image_exist=$shop_prev_logo_name1[0];
                if (file_exists($is_shop_image_exist)) 
                {
                    $shop_new_logo_name = $market_seller_id['marketplace_seller_id']."-".$shop_name.".jpg";
                    rename($shop_image_path.$shop_prev_logo_name.'.jpg',$shop_image_path.$shop_new_logo_name);
                }
            }
        } 
        else 
        {
            $shop_image_path      = 'modules/marketplace/img/shop_img/';
            $shop_prev_logo_name  = $market_seller_id['marketplace_seller_id']."-".$market_place_shop_name;
            $shop_prev_logo_name1 = glob($shop_image_path . $shop_prev_logo_name.'.*');
            $is_shop_image_exist  = $shop_prev_logo_name1[0];
            if (file_exists($is_shop_image_exist))
                unlink($shop_prev_logo_name1[0]);

            if ($_FILES['update_shop_logo']['error'] == 0)
            {
                $validExtensions = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
                $fileExtension   = strrchr($_FILES['update_shop_logo']['name'], ".");
                if (in_array($fileExtension, $validExtensions)) 
                {
                    $newpath = _PS_MODULE_DIR_.'marketplace/img/shop_img/';
                    $width = '200';
                    $height = '200';
                    ImageManager::resize($_FILES['update_shop_logo']['tmp_name'],$newpath.$market_seller_id['marketplace_seller_id'].'-'.$shop_name.'.jpg',$width,$height);
                }
            }
        }

        $shop_rewrite = Tools::link_rewrite($shop_name);
        $obj_seller = new SellerInfoDetail($market_seller_id['marketplace_seller_id']);
        $obj_seller->business_email = $business_email;
        $obj_seller->seller_name = $seller_name;
        $obj_seller->shop_name = $shop_name;
        $obj_seller->phone = $phone;
        $obj_seller->fax = $fax;
        $obj_seller->address = $address;
        $obj_seller->facebook_id = $facebook_id;
        $obj_seller->twitter_id = $twitter_id;
        $obj_seller->save();

        $obj_shop = new MarketplaceShop($id_shop);
        $obj_shop->shop_name = $shop_name;
        $obj_shop->link_rewrite = $shop_rewrite;
        $obj_shop->about_us = $about_us;
        $obj_shop->save();
    
        $param = array('shop'=>$id_shop);
        Hook::exec('actionUpdateshopExtrafield', array('marketplace_seller_id' => $market_seller_id['marketplace_seller_id']));
        $redirect_link1 = $link->getModuleLink('marketplace', 'marketplaceaccount',array('shop'=>$id_shop,'l'=>2,'update'=>1));
        Tools::redirect($redirect_link1);
    }

    public function setMedia()
    {
        parent::setMedia();
        $this->addJS(array(
                    _MODULE_DIR_ .'marketplace/js/tinymce/tinymce.min.js',
                    _MODULE_DIR_ .'marketplace/js/tinymce/tinymce_wk_setup.js'
            ));
    }
}
?>