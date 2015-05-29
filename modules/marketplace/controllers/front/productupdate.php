<?php
include_once dirname(__FILE__).'/../../classes/MarketplaceClassInclude.php';
class marketplaceProductupdateModuleFrontController extends ModuleFrontController	
{
	public function initContent() 
	{
		parent::initContent();		
		$id = Tools::getValue('id');		
		$link = new link();
		if ($this->context->cookie->id_customer)
		{
			$edit_pro_link = $link->getModuleLink('marketplace','productupdate',array('edited'=>1));
			$mkt_acc_link = $link->getModuleLink('marketplace','marketplaceaccount');
			$obj_marketplace_product = new SellerProductDetail();
			$pro_info = $obj_marketplace_product->getMarketPlaceProductInfo($id);
			$pro_info['price'] = Tools::ps_round($pro_info['price'],Configuration::get('PS_PRICE_DISPLAY_PRECISION'));
			$checked_product_cat = $obj_marketplace_product->getMarketPlaceProductCategories($id);
			$obj_seller_product_category = new SellerProductCategory();
			$defaultcatid = $obj_seller_product_category->getMpDefaultCategory($id);
			if(Tools::getIsset('deleteproduct') == 1) 
			{
				$obj_seller_product = new SellerProductDetail();
				$prod_detail = $obj_seller_product->getMarketPlaceProductInfo($id);
				$active = $prod_detail['active'];
				if($active == 1){
					$obj_mpshop_pro = new MarketplaceShopProduct();
					$product_deatil = $obj_mpshop_pro->findMainProductIdByMppId($id);
					$main_product_id = $product_deatil['id_product'];
					$obj_ps_prod = new Product($main_product_id);
					$obj_ps_prod->delete();
					}
				$is_delete = $obj_seller_product->deleteMarketPlaceSellerProduct($id);
				$mkt_acc_link = $link->getModuleLink('marketplace','marketplaceaccount',array('del'=>1));
				if($is_delete)
					Tools::redirect($mkt_acc_link.'&l=3');
				else {
					//error occurs
					Tools::redirect($mkt_acc_link.'&l=3');
				}
			}
			elseif(Tools::getIsset('editproduct') == 1) 
			{
				$id_lang = $this->context->cookie->id_lang;

				$id = Tools::getValue('id');
				$added = Tools::getValue('added');
				if($added)
					$this->context->smarty->assign('added',1);
				
				$is_main_er = Tools::getValue('is_main_er');
				if($is_main_er)
					$this->context->smarty->assign("is_main_er",$is_main_er);
				else
					$this->context->smarty->assign("is_main_er",'0');
				
				$this->context->smarty->assign("id",$id);
				$this->context->smarty->assign("edit_pro_link", $edit_pro_link);

				//Prepair Category Tree 
				$root = Category::getRootCategory();
				$category =  Db::getInstance()->ExecuteS("SELECT a.`id_category`,l.`name` from `"._DB_PREFIX_."category` a LEFT  JOIN `"._DB_PREFIX_."category_lang` l  ON (a.`id_category`=l.`id_category`) where a.id_parent=".$root->id." and l.id_lang=".$id_lang." and l.`id_shop`=".$this->context->shop->id." order by a.`id_category`");
					
				$tree = "<ul id='tree1'>";
				$tree .= "<!--"; // hide the root category
				$tree .= "<li><input type='checkbox'";
				if($checked_product_cat)
				{     					//For old products which have uploded
					foreach($checked_product_cat as $product_cat)
					{
						if($product_cat['id_category'] == $root->id)
							$tree .= "checked";
					}
				}
				else
				{
					if($defaultcatid == $root->id)
						$tree .= "checked";
				}
				$tree .= " name='product_category[]' class='product_category' style='display:none !important' value='".$root->id."'><label>".$root->name."</label>";
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
						$tree .= "<li><input type='checkbox'";
						if($checked_product_cat){       					//For old products which have uploded
							foreach($checked_product_cat as $product_cat){
								if($product_cat['id_category'] == $cat['id_category'])
									$tree .= "checked";
							}
						}
						else{
							if($defaultcatid == $cat['id_category'])
								$tree .= "checked";
						}
						$tree .= " name='product_category[]' class='product_category' value='".$cat['id_category']."'><label>".$cat['name']."</label>";  
						
						array_push($exclude, $cat['id_category']);          
							/*if ( $cat['id_category'] < 6 )
							{ $top_level_on = $cat['id_category']; } */
						$tree .= $obj_seller_product_category->buildChildCategoryRecursive($cat['id_category'],$id_lang,$checked_product_cat);        
					 }
					 $tree .= "</ul>";
				}

				$this->context->smarty->assign("categoryTree",$tree);
				//Prepair Category Tree Closed
				
				//Left Menu Links
				$customer_id = $this->context->cookie->id_customer;
				$market_place_shop = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `id`,`is_active` ,`about_us` from `" . _DB_PREFIX_ . "marketplace_shop` where id_customer =" . $customer_id . " ");
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
				//Left Menu Links Close

				Hook::exec('actionBeforeShowUpdatedProduct', array('marketplace_product_details' =>$pro_info));
			
				//images in product update product page
				$obj_mp_shopproduct = new MarketplaceShopProduct();
				$id_product_info = $obj_mp_shopproduct->findMainProductIdByMppId($id);
				
				$i = 0;
				$img_info = array();
				if($id_product_info)
				{
					$link = new Link();
					$id_product = $id_product_info['id_product'];
					
					$product = new Product($id_product);
					$id_lang = $this->context->language->id;
					$id_image_detail = $product->getImages($id_lang);
					$product_link_rewrite = Db::getInstance()->getRow("select * from `". _DB_PREFIX_."product_lang` where `id_product`=".$id_product." and `id_lang`=1");
					$name = $product_link_rewrite['link_rewrite'];
					
					
					
					if(!empty($id_image_detail))
					{
					  foreach($id_image_detail as $id_image_info)
					  {
						$img_info[$i]['id_image'] = $id_image_info['id_image'];
						$ids = $id_product.'-'.$id_image_info['id_image'];
						$img_info[$i]['image_link'] = $link->getImageLink($name,$ids);
						$img_info[$i]['cover'] = $id_image_info['cover'];
						$img_info[$i]['position'] = $id_image_info['position'];
						
						$i++;
						
					  }
					}
					
					$img = Product::getCover($id_product);
					$ids = $id_product.'-'.$img['id_image'];
					$image_id = $link->getImageLink($name,$ids);
					$count = count($img_info);
					$this->context->smarty->assign("img_info", $img_info);
					$this->context->smarty->assign("count", $count);
					$this->context->smarty->assign("id", $id);
					$this->context->smarty->assign("id_product", $id_product);
										
					$this->context->smarty->assign("image_id", $image_id);
					$this->context->smarty->assign("root_dir", _PS_ROOT_DIR_);
					$this->context->smarty->assign("is_approve",1);
					$imageediturl = $link->getModuleLink('marketplace','productimageedit');	
					$this->context->smarty->assign('imageediturl',$imageediturl);	
				}

				else
				{
					//product not approved yet
					$imageediturl = $link->getModuleLink('marketplace','productimageedit');	
					$this->context->smarty->assign('imageediturl',$imageediturl);
					$this->context->smarty->assign("is_approve",0);
					$obj_mp_pro_image = new MarketplaceProductImage();
					$mp_pro_image = $obj_mp_pro_image->findProductImageByMpProId(Tools::getValue('id'));
					if($mp_pro_image) {
						$this->context->smarty->assign("mp_pro_image",$mp_pro_image);
						$cover_img = $mp_pro_image['0']['seller_product_image_id'];
						$this->context->smarty->assign("cover_img",$cover_img);
					} else {
						$this->context->smarty->assign("mp_pro_image",'0');
					}

				$this->context->smarty->assign("pro_info",$pro_info);
				$this->context->smarty->assign("is_seller",1);			
				$this->context->smarty->assign("logic","update_product");
				$obj_currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
				$currency_sign = $obj_currency->sign;
				$this->context->smarty->assign("currency_sign",$currency_sign);
				$this->setTemplate('product_update.tpl');

			}
			else if(Tools::getIsset('edited') == 1) 
			{
				$id = Tools::getValue('id');
				$product_name = Tools::getValue('product_name');
				$short_description = Tools::getValue('short_description');
				$product_description = Tools::getValue('product_description');
				$product_price = Tools::getValue('product_price');
				$product_quantity = Tools::getValue('product_quantity');
				$product_category = Tools::getValue('product_category');
				if($product_name) {
					if($product_name=='') {
					$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
						Tools::redirect($upd_product_link."&is_main_er=1");
					} else {
						$is_generic_name = Validate::isGenericName($product_name);
						if(!$is_generic_name) {
							$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
							Tools::redirect($upd_product_link."&is_main_er=2");
						}
					}
					
					if($short_description) {
						$is_celan_short_desc = Validate::isCleanHtml($short_description);
						if(!$is_celan_short_desc) {
							$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
							Tools::redirect($upd_product_link."&is_main_er=3");
						}
					} 
					
					if($product_description) {
						$is_celan_pro_desc = Validate::isCleanHtml($product_description);
						if(!$is_celan_pro_desc) {
							$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
							Tools::redirect($upd_product_link."&is_main_er=4");
						}
					}
					
					if($product_price!='') {
						$is_product_price = Validate::isPrice($product_price);
						if(!$is_product_price) {
							$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
							Tools::redirect($upd_product_link."&is_main_er=5");
						}
					} else {
						$product_price =0;
					}
					
					if($product_quantity!='') {
						$is_product_quantity = Validate::isUnsignedInt($product_quantity);
						if(!$is_product_quantity) {
							$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
							Tools::redirect($upd_product_link."&is_main_er=6");
						}
					} else {
						$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
							Tools::redirect($upd_product_link."&is_main_er=6");
					}
					
					if($product_category == false){
						$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
						Tools::redirect($upd_product_link."&is_main_er=7");
					}
					$validExtensions = array('.jpg','.jpeg','.gif','.png','.JPG','.JPEG','.GIF','.PNG');
					if(isset($_FILES["product_image"])) {
						if($_FILES["product_image"]['size']>0) {
							$fileExtension   = strrchr($_FILES['product_image']['name'], ".");
							if(!in_array($fileExtension, $validExtensions)) 
							{
								$upd_product_link = $link->getModuleLink('marketplace','productupdate',array('id'=>$id,'editproduct'=>1));
								Tools::redirect($upd_product_link."&is_main_er=8");
							}
						}
					}
					Hook::exec('actionBeforeUpdateproduct');
					
					$obj_seller_product = new SellerProductDetail($id);
							
					$obj_seller_product->price = $product_price;
					$obj_seller_product->quantity = $product_quantity;
					$obj_seller_product->product_name = $product_name;
					$obj_seller_product->description = $product_description;
					$obj_seller_product->short_description = $short_description;
					$obj_seller_product->id_category = $product_category[0];
					
					$obj_seller_product->save();					 
					
					//Update new categories
					Db::getInstance()->delete('marketplace_seller_product_category','id_seller_product = '.$id);  //Delete previous
					//Add new category into table
					$obj_seller_product_category = new SellerProductCategory();
					$obj_seller_product_category->id_seller_product = $id;
					$obj_seller_product_category->is_default = 1;
					$i=0;
					foreach($product_category as $p_category){
						$obj_seller_product_category->id_category = $p_category;
						if($i != 0)
							$obj_seller_product_category->is_default = 0;
						$obj_seller_product_category->add();
						$i++;
					}
					//Update Close
					
					$is_active = $obj_seller_product->active;

					
					
					if($is_active == 1) {
						$obj_mpshop_pro = new MarketplaceShopProduct();
						$product_deatil = $obj_mpshop_pro->findMainProductIdByMppId($id);
						$main_product_id = $product_deatil['id_product'];
						
						if(isset($_FILES['product_image']) && $_FILES['product_image']["tmp_name"]!='') {
						
							
							$length = 6;
							$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
							$u_id = ""; 
							for ($p =0;$p<$length;$p++)  {
								$u_id .= $characters[mt_rand(0, Tools::strlen($characters))];
							}
							$image_name =$u_id.".jpg";
							$address = "modules/marketplace/img/product_img/";
							
							if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $address.$image_name)) {
								Db::getInstance()->insert('marketplace_product_image', 
															array(
																'seller_product_id' =>(int)$id,
																'seller_product_image_id' =>pSQL($u_id),
																'active' =>0,
															)
														);	
														
							}
						}

						if (isset($_FILES['images'])) {
							$other_images = $_FILES["images"]['tmp_name'];
							$other_images_name = $_FILES["images"]['name'];
							$count = count($other_images);
						} else {
							$count = 0;
						}
						
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
																'seller_product_id' =>(int)$id,
																'seller_product_image_id' =>pSQL($u_other_id),
																'active' =>0,
															));
									$image_name = $u_other_id . ".jpg";
									$address    = "modules/marketplace/img/product_img/";
									ImageManager::resize($other_images[$i],$address . $image_name);
									}
								}					
							}
						}

						$image_dir = 'modules/marketplace/img/product_img';
						
						$obj_seller_product->updatePsProductByMarketplaceProduct($id, $image_dir,1,$main_product_id);
					}
					else if($is_active == 0) 
					{
						if(isset($_FILES['product_image']) && $_FILES['product_image']["tmp_name"]!='') {
						
							
							$length = 6;
							$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
							$u_id = ""; 
							

							for ($p =0;$p<$length;$p++)  {
									$u_id .= $characters[mt_rand(0, Tools::strlen($characters))];
							}

							$image_name =$u_id.".jpg";
							$address = "modules/marketplace/img/product_img/";
							
							Db::getInstance()->insert('marketplace_product_image', array(
																		'seller_product_id' =>(int)$id,
																		'seller_product_image_id' =>pSQL($u_id)
																));
																
							move_uploaded_file($_FILES["product_image"]["tmp_name"],$address.$image_name);
							
						}

						if (isset($_FILES['images'])) {
							$other_images = $_FILES["images"]['tmp_name'];
							$other_images_name = $_FILES["images"]['name'];
							$count = count($other_images);
						} else {
							$count = 0;
						}
					
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
																		'seller_product_id' =>(int)$id,
																		'seller_product_image_id' =>pSQL($u_other_id)
																));
									$image_name = $u_other_id . ".jpg";
									$address    = "modules/marketplace/img/product_img/";
									ImageManager::resize($other_images[$i],$address . $image_name);
									}
								}					
							}
						}
					}			
					Hook::exec('actionUpdateproductExtrafield', array('marketplace_product_id' =>Tools::getValue('id')));
					$mkt_acc_link = $link->getModuleLink('marketplace','marketplaceaccount',array('edit'=>1,'l'=>3));
					Tools::redirectAdmin($mkt_acc_link);	
				}
			}
		}
		else
		{
			$myaccountpage = $link->getPageLink('my-account');
			Tools::redirect($myaccountpage);
		}
	}

		
	public function setMedia() 
	{
		parent::setMedia();
		$this->addJS(_MODULE_DIR_.'marketplace/views/js/imageedit.js');
		$this->addCSS(_MODULE_DIR_.'marketplace/css/product_details.css');
		$this->addCSS(array(
				_MODULE_DIR_.'marketplace/css/add_product.css',
				_MODULE_DIR_.'marketplace/css/marketplace_account.css'
			));

		//tinymce
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

		//for tiny mce lang
		Media::addJsDef(array('iso' => $this->context->language->iso_code));
		
		//Category tree
		$this->addJS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js');
		$this->addCSS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css');
		$this->addJS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery.checkboxtree.js');
		$this->addCSS(_MODULE_DIR_.'marketplace/views/js/categorytree/wk.checkboxtree.css');

	}

}
?>