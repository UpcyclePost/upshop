<?php
if (!defined('_PS_VERSION_'))
    exit;
include_once dirname(__FILE__).'/../../classes/MarketplaceClassInclude.php';
class marketplaceAddproductprocessModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
		$link = new link();
		$customer_id          = $this->context->cookie->id_customer;
        $obj_seller_product = new SellerProductDetail();
        $obj_mpshop = new MarketplaceShop();
		$obj_mp_customer = new MarketplaceCustomer();
		
		$marketplace_shop = $obj_mpshop->getMarketPlaceShopInfoByCustomerId($customer_id);
      	$mp_id_shop              = $marketplace_shop['id'];
		
		$marketplace_customer = $obj_mp_customer->findMarketPlaceCustomer($customer_id);
       
        $id_seller = $marketplace_customer['marketplace_seller_id'];
        
		$product_name = Tools::getValue('product_name');
		$short_description = Tools::getValue('short_description');
		$product_description = Tools::getValue('product_description');
		$product_price = Tools::getValue('product_price');
		$product_quantity = Tools::getValue('product_quantity');
		$product_category = Tools::getValue('product_category');

		$context = Context::getContext();
		$context->cookie->__set('c_mp_product_name',$product_name); 
		$context->cookie->__set('c_mp_short_description',$short_description);
		$context->cookie->__set('c_mp_product_description',$product_description);
		$context->cookie->__set('c_mp_product_price',$product_price);
		$context->cookie->__set('c_mp_product_quantity',$product_quantity);
		

		

		if($product_name) {
			
			if($product_name=='') {
				$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>1));
					Tools::redirect($add_product_link);
			} else {
				$is_generic_name = Validate::isGenericName($product_name);
				if(!$is_generic_name) {
					$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>2));
					Tools::redirect($add_product_link);
				}
			}
			
			if($short_description) {
				/*$is_celan_short_desc = Validate::isCleanHtml($short_description);
				if(!$is_celan_short_desc) {
					$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>3));
					Tools::redirect($add_product_link);
				}*/
			} 
			
			if($product_description) {
				$is_celan_pro_desc = Validate::isCleanHtml($product_description, (int)Configuration::get('PS_ALLOW_HTML_IFRAME'));
				if(!$is_celan_pro_desc) {
					$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>4));
					Tools::redirect($add_product_link);
				}
			}
			
			if($product_price!='') {
				$is_product_price = Validate::isPrice($product_price);
				if(!$is_product_price) {
					$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>5));
					Tools::redirect($add_product_link);
				}
			} else {
				$product_price =0;
			}
			
			if($product_quantity!='') {
				$is_product_quantity = Validate::isUnsignedInt($product_quantity);
				if(!$is_product_quantity) {
					$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>6));
					Tools::redirect($add_product_link);
				}
			} else {
				$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>6));
					Tools::redirect($add_product_link);
			}
			
			if($product_category == false){
				$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>7));
				Tools::redirect($add_product_link);
			}
			$validExtensions = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
			if(isset($_FILES["product_image"])) {
				if($_FILES["product_image"]['size']>0) {
					$fileExtension   = strrchr($_FILES['product_image']['name'], ".");
					if(!in_array($fileExtension, $validExtensions)) 
					{
						$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>8));
						Tools::redirect($add_product_link);
					}
				}
			}
			Hook::exec('actionBeforeAddproduct', array('mp_seller_id' => $id_seller));
			$approve_type = Configuration::getGlobalValue('PRODUCT_APPROVE');

			
			$obj_seller_product->id_seller = $id_seller;
			$obj_seller_product->price = $product_price;
			$obj_seller_product->quantity = $product_quantity;
			$obj_seller_product->product_name = $product_name;
			$obj_seller_product->description = $product_description;
			$obj_seller_product->short_description = $short_description;
			$obj_seller_product->id_category = $product_category[0];
			$obj_seller_product->ps_id_shop = $this->context->shop->id;
			$obj_seller_product->id_shop = $mp_id_shop;
			if($approve_type == 'admin'){
				$active = false;
					$obj_seller_product->active = 0;
			}else{
				$active = true;
					$obj_seller_product->active = 1;
			}
		
			$obj_seller_product->save();
			//Db::getInstance()->execute("INSERT INTO `"._DB_PREFIX_."marketplace_seller_product` (short_description)VALUES('".$short_description."')");
			
			$seller_product_id = $obj_seller_product->id;
			
			//Add into category table
			$obj_seller_product_category = new SellerProductCategory();
			$obj_seller_product_category->id_seller_product = $seller_product_id;
			$obj_seller_product_category->is_default = 1;
			$i=0;
			foreach($product_category as $p_category){
				$obj_seller_product_category->id_category = $p_category;
				if($i != 0)
					$obj_seller_product_category->is_default = 0;
				$obj_seller_product_category->add();
				$i++;
			}
			//Close
			
			$address    = "modules/marketplace/img/product_img/";
			
			if(isset($_FILES["product_image"])) {
				if($_FILES["product_image"]['size']>0) {
					$length     = 6;
					$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
					$u_id       = "";
					for ($p = 0; $p < $length; $p++) {
						$u_id .= $characters[mt_rand(0, Tools::strlen($characters))];
					}
					
					Db::getInstance()->insert('marketplace_product_image', 
											array(
													'seller_product_id' => (int) $seller_product_id,
													'seller_product_image_id' => pSQL($u_id)
											));
					$image_name = $u_id . ".jpg";
					//move_uploaded_file($_FILES["product_image"]["tmp_name"], $address . $image_name);
					ImageManager::resize($_FILES["product_image"]["tmp_name"],$address.$image_name);
					
				}
			}
						
			if (isset($_FILES['images'])) {
				$other_images = $_FILES["images"]['tmp_name'];
				$other_images_name = $_FILES["images"]['name'];
				$count = count($other_images);
			} else {
				$count = 0;
			}
			/*echo "<pre>";
			print_r($other_images);
			print_r($other_images_name);die();
			list($image_width, $image_height) = getimagesize($other_images[0]);*/
/*			$image_info = getimagesize($_FILES["images"]["tmp_name"]);*/
			//$image_width = $image_info[0];
			//$image_height = $image_info[1];
			/*print_r($image_width);echo "<br />";
			print_r($image_height);*/
			/*print_r($type);
			print_r($attr);*/
			//die;
			
			for ($i = 0; $i < $count; $i++)
			{
				if($other_images[$i]!='')
				{
					$fileExtension   = strrchr($other_images_name[$i], ".");
					if(in_array($fileExtension, $validExtensions)) 
					{
						list($image_width, $image_height) = getimagesize($other_images[$i]);
						if($image_width >= 200 && $image_width  <= 2000 && $image_height >= 200 &&  $image_height <= 2000)
						{
						$length     = 6;
						$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
						$u_other_id = "";
						for ($p = 0; $p < $length; $p++)
							$u_other_id .= $characters[mt_rand(0, Tools::strlen($characters))];

						Db::getInstance()->insert('marketplace_product_image', array(
							'seller_product_id' => (int) $seller_product_id,
							'seller_product_image_id' => pSQL($u_other_id)
						));
						$image_name = $u_other_id . ".jpg";
						$address    = "modules/marketplace/img/product_img/";
						ImageManager::resize($other_images[$i],$address . $image_name);
						}
					}					
				}
			}
			if($seller_product_id){
						// if active, then entry of a product in ps_product table...
				if($active)
				{
					$obj_seller_product = new SellerProductDetail();
					$image_dir = "modules/marketplace/img/product_img";
					// creating ps_product when admin setting is default
					$ps_product_id = $obj_seller_product->createPsProductByMarketplaceProduct($seller_product_id,$image_dir, $active);
					if($ps_product_id){
						// mapping of ps_product and mp_product id
						$mps_product_obj = new MarketplaceShopProduct();
						$mps_product_obj->id_shop = $mp_id_shop;
						$mps_product_obj->marketplace_seller_id_product = $seller_product_id;
						$mps_product_obj->id_product = $ps_product_id;
						$mps_product_obj->add();
					}
				}
				else
				{
					$obj_emp = new Employee(1);    //1 for superadmin
					if(Configuration::get('MP_SUPERADMIN_EMAIL'))
						$admin_email = Configuration::get('MP_SUPERADMIN_EMAIL');
					else
						$admin_email = $obj_emp->email;

					$id_lang = Context::getContext()->cookie->id_lang;
					$mail_reason = 'Marketplace product request';
					$sub = 'Marketplace product request';
					$obj_seller_product = new SellerProductDetail($seller_product_id);
					$product_name = $obj_seller_product->product_name;
					$id_category = $obj_seller_product->id_category;
					//$short_description = $obj_seller_product->short_description;
					$ps_id_shop	 = $obj_seller_product->ps_id_shop;
					$mp_id_shop	 = $obj_seller_product->id_shop;
					$mp_id_seller	 = $obj_seller_product->id_seller;
					$quantity	 = $obj_seller_product->quantity;
					
					$obj_category = new Category($id_category);
					$category_name = $obj_category->name['1'];
					$obj_seller = new SellerInfoDetail($mp_id_seller);
					$obj_mp_customer = new MarketplaceCustomer();
					$mp_seller_name = $obj_seller->seller_name;
					//$shop_name = $obj_seller->shop_name;
					$business_email = $obj_seller->business_email;
					if($business_email=='') 
					{
						$id_customer = $obj_mp_customer->getCustomerId($mp_id_seller);
						$obj_cus = new Customer($id_customer);
						$business_email = $obj_cus->email;
					}
					$obj_mp_shop = new MarketplaceShop($mp_id_shop);
					$mp_shop_name = $obj_mp_shop->shop_name;
					
					$obj_shop = new Shop($ps_id_shop);
					$ps_shop_name = $obj_shop->name;
					if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
						$secure_connection = "https://";
					} else {
						$secure_connection = "http://";
					}
					$shop_url = $secure_connection.$obj_shop->domain.$obj_shop->physical_uri.$obj_shop->virtual_uri;
					$template='product_request';
					$templateVars = array(
										'{seller_name}' => $mp_seller_name,
										'{product_name}' => $product_name,
										'{mp_shop_name}' => $mp_shop_name,
										'{mail_reason}' => $mail_reason,
										'{category_name}' => $category_name,
										'{quantity}' => $quantity,
										'{ps_shop_name}' => $ps_shop_name,
										'{shop_url}' => $shop_url
									);
					$temp_path = _PS_MODULE_DIR_.'marketplace/mails/';
					Mail::Send($id_lang,$template,$sub,$templateVars,$admin_email,'Admin',$business_email,$mp_seller_name,null,null,$temp_path,false,null,null);

				}
						
			}

			$context->cookie->__unset('c_mp_product_name');
			$context->cookie->__unset('c_mp_short_description');
			$context->cookie->__unset('c_mp_product_description');
			$context->cookie->__unset('c_mp_product_price');
			$context->cookie->__unset('c_mp_product_quantity');

			Hook::exec('actionAddproductExtrafield', array('marketplace_product_id' => $seller_product_id));
			
			$param  = array('su' => 1,'shop' => $mp_id_shop);
			$redirect_link = $link->getModuleLink('marketplace', 'addproduct', $param);
			$ismpinstall = Module::isInstalled('mpcombination');
				if($ismpinstall){
					$param = array('added'=>1,'id'=>$seller_product_id,'editproduct'=>1);
					$prod_update_link = $link->getModuleLink('marketplace', 'productupdate', $param);
					Tools::redirect($prod_update_link);
				}else{
					Tools::redirect($redirect_link);
				}		
			} else {
				
				$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>1));
						Tools::redirect($add_product_link);
			}
    }
}
?>