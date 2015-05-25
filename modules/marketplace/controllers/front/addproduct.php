<?php
include_once dirname(__FILE__).'/../../classes/MarketplaceClassInclude.php';
class marketplaceAddproductModuleFrontController extends ModuleFrontController
{
	public $ssl = true; 
	public function initContent() 
	{
		parent::initContent();

		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) 
			$this->context->smarty->assign("browser", "ie");
		else
			$this->context->smarty->assign("browser", "notie");

		$context = Context::getContext();
		$this->context->smarty->assign("title_bg_color", Configuration::get('MP_TITLE_COLOR'));		 
		$this->context->smarty->assign("title_text_color", Configuration::get('MP_TITLE_TEXT_COLOR'));
		
		if($context->cookie->__isset('c_mp_product_name') 
			|| $context->cookie->__isset('c_mp_short_description') 
			|| $context->cookie->__isset('c_mp_product_description')
			|| $context->cookie->__isset('c_mp_product_price')
			|| $context->cookie->__isset('c_mp_product_quantity')
			) {			 
			$c_mp_product_name = $context->cookie->c_mp_product_name;
			$c_mp_short_description = $context->cookie->c_mp_short_description;
			$c_mp_product_description = $context->cookie->c_mp_product_description;
			$c_mp_product_price = $context->cookie->c_mp_product_price;
			$c_mp_product_quantity = $context->cookie->c_mp_product_quantity;
		}
		else {
			$c_mp_product_name = '';
			$c_mp_short_description = '';
			$c_mp_product_description = '';
			$c_mp_product_price = '';
			$c_mp_product_quantity = '';
		}

		$this->context->smarty->assign('c_mp_product_name',$c_mp_product_name);
		$this->context->smarty->assign('c_mp_short_description',$c_mp_short_description);
		$this->context->smarty->assign('c_mp_product_description',$c_mp_product_description);
		$this->context->smarty->assign('c_mp_product_price',$c_mp_product_price);
		$this->context->smarty->assign('c_mp_product_quantity',$c_mp_product_quantity);
						 
		$id_lang = $this->context->cookie->id_lang;
		$link = new link();

		if(isset($this->context->cookie->id_customer)) 
		{
			if(isset($this->context->cookie->id_customer)) 
			{
				$login = 1;
			} 
			else
				$login = 0;

			$is_main_er = Tools::getValue('is_main_er');
			if($is_main_er)
				$this->context->smarty->assign("is_main_er",$is_main_er);
			else
				$this->context->smarty->assign("is_main_er",'0');
			
			if(Tools::getIsset('su') && Tools::getValue('su')!='')
				$this->context->smarty->assign("product_upload",Tools::getValue('su'));
			else
				$this->context->smarty->assign("product_upload",'0');
			
			$this->context->smarty->assign("login",$login);
			$this->context->smarty->assign("is_seller",1);	
			
			
			$customer_id     = $this->context->cookie->id_customer;
			$market_place_shop       = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `id`,`is_active` ,`about_us` from `" . _DB_PREFIX_ . "marketplace_shop` where id_customer =" . $customer_id . " ");
			$id_shop   = $market_place_shop['id'];
			  
			$this->context->smarty->assign("id_shop", $id_shop);
			$obj_ps_shop = new MarketplaceShop($id_shop);
			$name_shop = $obj_ps_shop->link_rewrite;
			$param = array('shop'=>$id_shop);
			
			$new_link4 = $link->getModuleLink('marketplace','addproductprocess',$param);
			$this->context->smarty->assign("new_link4",$new_link4);
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
			$this->context->smarty->assign("logic",'add_product');
			
			//Prepair Category Tree Array
			$root = Category::getRootCategory();
			$obj_seller_product_category = new SellerProductCategory();
			$category =  Db::getInstance()->ExecuteS("SELECT a.`id_category`,l.`name` from `"._DB_PREFIX_."category` a LEFT  JOIN `"._DB_PREFIX_."category_lang` l  ON (a.`id_category`=l.`id_category`) where a.id_parent=".$root->id." and l.id_lang=".$id_lang." and l.`id_shop`=1 order by a.`id_category`");
			
			//Recursive Category Tree Closed		
			$tree = "<ul id='tree1'>";
			$tree .= "<!--"; // hide the root category
			$tree .= "<li><input type='checkbox' checked='checked' class='product_category' name='product_category[]' value='".$root->id."'><label>".$root->name."</label>";
			$tree .= " -->"; //Hide the root category
			//$depth = 1;
			$exclude = array();
			array_push($exclude, 0);
			
			foreach($category as $cat) 
			{
				$goOn = 1;             
				$tree .= "<ul>" ;
				 for($x = 0; $x < count($exclude); $x++ )   
				 {
					  if ( $exclude[$x] == $cat['id_category'] )
					  {
						   $goOn = 0;
						   break;                   
					  }
				 }
				 if ( $goOn == 1 )
				 {
					  $tree .= "<li><input id='check' type='checkbox' class='product_category' name='product_category[]' value='".$cat['id_category']."'><label>".$cat['name']."</label>";                  
					  array_push($exclude, $cat['id_category']);          
					  $tree .= $obj_seller_product_category->buildChildCategoryRecursive($cat['id_category'],$id_lang);        
				 }
				 $tree .= "</ul>";
			}

			$this->context->smarty->assign("categoryTree",$tree);
			//Recursive Category Tree Closed
			$obj_currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
			$currency_sign = $obj_currency->sign;
			$this->context->smarty->assign("currency_sign",$currency_sign);
			$this->setTemplate('addproduct.tpl');
		} 
		else 
		{
			$my_account_link = $link->getPageLink('my-account');
			Tools::redirect($my_account_link);
		}
			
	}
	
	public function setMedia() 
	{
		parent::setMedia();
		$this->addCSS(array(
				_MODULE_DIR_.'marketplace/css/add_product.css',
				_MODULE_DIR_.'marketplace/css/marketplace_account.css'
			));
		
		//tinyMCE
		if(Configuration::get('PS_JS_THEME_CACHE')==0)
			$this->addJS(array(
	                _MODULE_DIR_ .'marketplace/js/tinymce/tinymce.min.js',
	                _MODULE_DIR_ .'marketplace/js/tinymce/tinymce_wk_setup.js',
	                _MODULE_DIR_ .'marketplace/js/mp_form_validation.js'
	            ));
		else
			$this->addJS(array(
	                _MODULE_DIR_ .'marketplace/js/mp_form_validation.js'
	            ));

		Media::addJsDef(array('iso' => $this->context->language->iso_code));
		
		//Category tree
		$this->addJS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js');
		$this->addCSS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css');
		$this->addJS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery.checkboxtree.js');
		$this->addCSS(_MODULE_DIR_.'marketplace/views/js/categorytree/wk.checkboxtree.css');
	}
}

?>