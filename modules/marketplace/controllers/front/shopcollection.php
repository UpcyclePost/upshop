<?php
class marketplaceshopcollectionModuleFrontController extends ModuleFrontController
{
    public function initContent() 
    {
		$link = new link();
        $id_lang = $this->context->cookie->id_lang;
		
		if (Tools::getIsset('orderby'))
			$orderby  = Tools::getValue('orderby');
		else
			$orderby  = 'product_name';

		if (Tools::getIsset('orderway'))
			$orderway  = Tools::getValue('orderway');
		else
			$orderway  = 'asc';

		if ($orderby == 'name')
            $orderby = 'product_name';
        elseif ($orderby == '')
            $orderby = 'product_name';


        if ($orderway == '')
            $orderway = 'asc';
		
        $id_product = Tools::getValue('id');
        
		$obj_marketplace_product = new SellerProductDetail();
		$obj_marketplace_seller = new SellerInfoDetail();
		$obj_marketplace_shop = new MarketplaceShop();
		if ($id_product != '')
        {
			$marketplace_shop_id = $obj_marketplace_product->getMarketPlaceShopProductDetail($id_product);
            $marketplace_id_shop = $marketplace_shop_id['id_shop'];
            $this->context->smarty->assign("id_product1", $id_product);
			$marketplace_shop_product = $obj_marketplace_product->findAllProductInMarketPlaceShop($marketplace_id_shop,$orderby,$orderway);
        }
        else
        {
            $id_shop = Tools::getValue('shop');
            if (!$id_shop)
                $id_shop = Tools::getValue('id_shop');

            if ($id_shop != '')
            {
				$id_product =0;
				$this->context->smarty->assign("id_product1", $id_product);
                $this->context->smarty->assign("id_shop1", $id_shop);
				$marketplace_shop_product = $obj_marketplace_product->findAllProductInMarketPlaceShop($id_shop,$orderby,$orderway);
            }
            else
            {
                Tools::redirect(__PS_BASE_URI__ . 'pagenotfound');
                return;
            }
        }
        if ($marketplace_shop_product) 
        {
            $a = 0;
            $marketplace_product_id = array();
            foreach ($marketplace_shop_product as $marketplace_shop_product1) 
            {
                $marketplace_product_id[] = $marketplace_shop_product1['id_product'];
                $marketplace_seller_id    = $marketplace_shop_product1['id_seller'];
                $a++;
            }
            $count = count($marketplace_product_id);
            $product = array();
            for ($i = 0; $i < $count; $i++)
            {
                $active_product = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * FROM `"._DB_PREFIX_."product` WHERE id_product = ".$marketplace_product_id[$i]." AND active = 1");
                if ($active_product)
				    $product[] = $active_product;
				
            }
            $a = 0;
			$category_id = array();
			$category_name = array();
			$category_qty = array();
            $product_id = array();
            foreach ($product as $product1)
            {
                $obj_product = new Product($product1['id_product']);
                $catgs = $obj_product->getCategories();
                foreach ($catgs as $catg)
                {
                    $obj_catg = new Category($catg, $id_lang);
                    if(!in_array($catg, $category_id))
                    {
                        $category_id[] = $catg;
                        $category_name[] = $obj_catg->name;
                        $category_qty[] = 1;
                    }
                    else
                    {
                        $key = array_search($catg, $category_id);
                        $category_qty[$key] = $category_qty[$key] + 1;
                    }
                }
                $product_id[] = $product1['id_product'];
                $a++;
            }

			//according to cat
			if(Tools::getIsset('cat_id'))
            {
                $new_cat_id = Tools::getValue('cat_id');
				$count = count($marketplace_product_id);
				unset($product_id);
                $product_id = array();
				for ($i = 0; $i < $count; $i++) 
                {
                    $id_product = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'category_product` WHERE `id_category` = '.$new_cat_id);
                    foreach ($id_product as $data)
                    {
                        if ($data['id_product'] == $marketplace_product_id[$i])
                        {
                            $product_id1 = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product` WHERE `id_product` ='.$marketplace_product_id[$i]);
                            if ($product_id1)
                                $product_id[] = $product_id1['id_product']; 
                        }
                    }
                }
				unset($product);
                $product = array();
				foreach($product_id as $product_id1)
                {
					$active_product = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * FROM `"._DB_PREFIX_."product`
                                                WHERE id_product =".$product_id1." AND active = 1");
                    if ($active_product)
                        $product[] = $active_product;
				}
				$this->context->smarty->assign("cat_id", $new_cat_id);
			}
            else
				$this->context->smarty->assign("cat_id", 0);

			$this->context->smarty->assign("count_category",count($category_id) );
		    $this->context->smarty->assign("category_id",$category_id );
			$this->context->smarty->assign("category_name",$category_name );
			$this->context->smarty->assign("category_qty",$category_qty );
			
            $count_product = count($product_id);
            $image = array();
            $product_lang = array();
            $image_link = array();
            for ($i = 0; $i < $count_product; $i++)
            {
                $image[]        = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "image` where id_product =" . $product_id[$i] . " and cover = 1");
				
				$image_id        = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "image` where id_product =" . $product_id[$i] . " and cover = 1");
				
				
                $product_lang[] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `" . _DB_PREFIX_ . "product_lang` where id_product =" . $product_id[$i] . " and id_lang=1");

				$product_obj = new Product($product_id[$i], false, $id_lang);
				$cover_image_id = Product::getCover($product_obj->id);
                $image_link[$i][0] = $product_obj->link_rewrite;
                $image_link[$i][3] = $this->context->language->id;
				if($cover_image_id)
                    $image_link[$i][1] = $product_obj->id.'-'.$cover_image_id['id_image'];
                else
                {
                    $image_link[$i][1] = "";
                    $image_link[$i][2] = $this->context->language->iso_code;
				}
            }
            $a = 0;
            $product_name = array();
            $product_desc = array();
            foreach ($product_lang as $product_lang1)
            {
                $product_name[] = $product_lang1['name'];
                $product_desc[] = $product_lang1['description'];
                $a++;
            }
            $a = 0;
		    $product_price = array();
            $product_id = array();
            $product_quantity = array();
            foreach ($product as $product1)
            {
                $product_price[] =  $product_obj->getPriceStatic($product1['id_product'],true,null,Configuration::get('PS_PRICE_DISPLAY_PRECISION'));
                $product_id[] = $product1['id_product'];
                $product_quantity[] = $product1['quantity'];
                $a++;
            }
            $a = 0;
            $i = 0;
            $main_address = array();
            foreach ($image as $image1)
            {
                $image_id[$a] = $image1['id_image'];
                $main_address[$i] = 'img/p/';
                $array = str_split($image_id[$a]);
                foreach ($array as $array1)
                {
                    $main_address[$i] = $main_address[$i] . $array1 . "/";
                }
                $a++;
                $i++;
            }
			$mkt_shop = $obj_marketplace_seller->getmarketPlaceSellerInfo($marketplace_seller_id);
			$fake = array('fake'=>1);
            $shop_name       = $mkt_shop['shop_name'];
           
            $link_store      = $link->getModuleLink('marketplace', 'shopstore',$fake);
            $link_collection = $link->getModuleLink('marketplace', 'shopcollection',$fake);
            $Seller_profile  = $link->getModuleLink('marketplace', 'sellerprofile',$fake);
            $link_contact    = $link->getModuleLink('marketplace', 'contact',$fake);
            $count_product   = count($product_quantity);
		
            $this->context->smarty->assign("ff", '15');
            $this->context->smarty->assign("image_id", $image_id);
            $this->context->smarty->assign("main_address", $main_address);
            $this->context->smarty->assign("Seller_profile", $Seller_profile);
            $this->context->smarty->assign("link_contact", $link_contact);
            $this->context->smarty->assign("link_collection", $link_collection);
            $this->context->smarty->assign("link_store", $link_store);
            $this->context->smarty->assign("shop_name", $shop_name);
            $this->context->smarty->assign("seller_id", $marketplace_seller_id);
            $this->context->smarty->assign("product_quantity", $product_quantity);
            $this->context->smarty->assign("product_price", $product_price);
            $this->context->smarty->assign("product_id", $product_id);
            $this->context->smarty->assign("product_desc", $product_desc);
            $this->context->smarty->assign("product_name", $product_name);
            $this->context->smarty->assign("count_product", $count_product);
            $this->context->smarty->assign("image_link", $image_link);
            $this->setTemplate('shop_collection.tpl');
        } 
		else 
        {    
			$marketplace_shop = $obj_marketplace_shop->getMarketPlaceShopDetail($id_shop);
		   
			$shop_name = $marketplace_shop['shop_name'];
            $id_customer                = $marketplace_shop['id_customer'];
			
			$marketplace_seller_id_info = $obj_marketplace_seller->getMarketPlaceSellerIdByCustomerId($id_customer);
            $marketplace_seller_id      = $marketplace_seller_id_info['marketplace_seller_id'];
			
			$fake = array('fake'=>1);
            $link            = new link();
            $link_store      = $link->getModuleLink('marketplace', 'shopstore', $fake);
            $link_collection = $link->getModuleLink('marketplace', 'shopcollection', $fake);
            $Seller_profile  = $link->getModuleLink('marketplace', 'sellerprofile', $fake);
            $link_contact    = $link->getModuleLink('marketplace', 'contact', $fake);
			$this->context->smarty->assign("count_category",0);
            $this->context->smarty->assign("ff", '15');
            $this->context->smarty->assign("Seller_profile", $Seller_profile);
            $this->context->smarty->assign("shop_name", $shop_name);
            $this->context->smarty->assign("seller_id", $marketplace_seller_id);
            $this->context->smarty->assign("link_contact", $link_contact);
            $this->context->smarty->assign("link_collection", $link_collection);
            $this->context->smarty->assign("link_store", $link_store);
            $this->context->smarty->assign("count_product", 0);
			$this->context->smarty->assign("cat_id",0);
            $this->setTemplate('shop_collection.tpl');
        }
        parent::initContent();
    }

    public function setMedia()
    {
        parent::setMedia();
        $this->addCSS(_MODULE_DIR_ . 'marketplace/css/shop_collection.css');
        $this->addCSS(array(
			_THEME_CSS_DIR_ . 'scenes.css' => 'all',
			_THEME_CSS_DIR_ . 'category.css' => 'all',
			_THEME_CSS_DIR_ . 'product_list.css' => 'all',
            _MODULE_DIR_ . 'marketplace/css/header.css'
        ));

        if (Configuration::get('PS_COMPARATOR_MAX_ITEM') > 0)
            $this->addJS(_THEME_JS_DIR_ . 'products-comparison.js');
    }
}
?>