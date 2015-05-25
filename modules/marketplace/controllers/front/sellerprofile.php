<?php
class marketplaceSellerprofileModuleFrontController extends ModuleFrontController	
{
	public function initContent() 
	{
		$link = new Link();
		$id_lang = $this->context->language->id;
		// review submit process 
		if (Tools::isSubmit('submit_feedback'))
		{
			$id_customer = $this->context->cookie->id_customer;
			$seller_id = Tools::getValue('seller_id');
			$feedback = Tools::getValue('feedback');
			$rating = Tools::getValue('rating_image');
			$id_shop = Tools::getValue('shop');
			$obj_reviews = new Reviews();
			if ($id_customer)
			{
				$cust_info = new Customer($id_customer);
				$obj_reviews->id_customer = $id_customer;
				$obj_reviews->customer_email = $cust_info->email;
			}
			else
				$obj_reviews->customer_email = 'As a guest'; //If guest review not login customer

			$obj_reviews->id_seller = $seller_id;
			$obj_reviews->rating = $rating;
			$obj_reviews->review = $feedback;
			$obj_reviews->active = 0;
			$obj_reviews->save();
			$review = $obj_reviews->id;

			if ($review)
				Tools::redirect($link->getModuleLink('marketplace', 'sellerprofile', array('shop' => $id_shop, 'review_submitted' => 1)));
		}// submit_feedback close

		// on review submit success
		if (Tools::getValue('review_submitted'))
			$this->context->smarty->assign('review_submitted', 1);

		$id_shop = Tools::getValue('shop');
		$id_product =0;

		if ($id_shop != '') 
		{
			if (Tools::getIsset('all_reviews'))  //if click on all reviews
			{
				$seller_id = Tools::getValue('seller_id');
	            $link = new link();
	            $obj_reviews = new Reviews();
	            $reviews_info = $obj_reviews->getSellerReviews($seller_id);
	            if ($reviews_info)
	            {
		            $reviews_details1 = array();
		            $i = 0;
		            foreach($reviews_info as $reviews)
	            	{
						$obj_customer = new Customer($reviews['id_customer']);
						if ($reviews['id_customer'])
						{
							$reviews_details1[$i]['customer_name'] = $obj_customer->firstname." ".$obj_customer->lastname;
							$reviews_details1[$i]['customer_email'] = $obj_customer->email;
						}
						else
						{
							$reviews_details1[$i]['customer_name'] = "Not Available";
							$reviews_details1[$i]['customer_email'] = "Not Available";
						}

						$reviews_details1[$i]['rating'] = $reviews['rating'];
						$reviews_details1[$i]['review'] = $reviews['review'];
						$reviews_details1[$i]['time'] = $reviews['date_add'];

						$i++;
	            	}
					$reviews_count = count($reviews_info);
					$this->context->smarty->assign("reviews_count", $reviews_count);
					$this->context->smarty->assign("reviews_details1", $reviews_details1);
					$this->context->smarty->assign("all_reviews",1);
				}
			}
			else
				$this->context->smarty->assign("reviews_count", 0);

			$marketplace_shop = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `shop_name`,`id_customer` from `"._DB_PREFIX_."marketplace_shop` where `id` =".$id_shop." ");

			if ($marketplace_shop)
			{
				$shop_name = $marketplace_shop['shop_name'];
				$id_customer = $marketplace_shop['id_customer'];

				$marketplace_seller_id_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `marketplace_seller_id`,`is_seller` from `"._DB_PREFIX_."marketplace_customer` where `id_customer` =".$id_customer." ");

				if ($marketplace_seller_id_info)
				{
					$is_seller_active = $marketplace_seller_id_info['is_seller'];
					$marketplace_seller_id = $marketplace_seller_id_info['marketplace_seller_id'];
					if ($is_seller_active == 1)
					{
						$market_place_seller_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `"._DB_PREFIX_."marketplace_seller_info` where `id` =".$marketplace_seller_id." ");
						if ($market_place_seller_info)
						{
							$business_email = $market_place_seller_info['business_email'];
							$phone = $market_place_seller_info['phone'];
							$fax = $market_place_seller_info['fax'];
							$customer_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `"._DB_PREFIX_."customer` where `id_customer` =".$id_customer." ");

							$product_detail = Db::getInstance()->executeS("select * from `"._DB_PREFIX_."marketplace_shop_product` mpsp join `"._DB_PREFIX_."product` p on (p.`id_product`=mpsp.`id_product`) join `"._DB_PREFIX_."product_lang` pl on (p.`id_product`=pl.`id_product`) where mpsp.`id_shop`=$id_shop and pl.`id_shop`=".$this->context->shop->id." and pl.`id_lang`=".$this->context->cookie->id_lang." order by p.`date_add` limit 10");

							$i = 0;
							$all_product_id = array();
							$all_product_price = array();
							$all_product_name = array();
							$product_image_link = array();
							foreach($product_detail as $product_detail1) 
							{
								$all_product_id[$i] = $product_detail1['id_product'];
								$all_product_price[$i] = number_format($product_detail1['price'],2,'.','');
								$all_product_name[$i] = $product_detail1['name'];
								$product_obj = new Product($product_detail1['id_product'], false, $id_lang);
								$cover_image_id = Product::getCover($product_obj->id);
								$product_image_link[$i][0] = $product_obj->link_rewrite;
								$product_image_link[$i][3] = $this->context->language->id;
								if ($cover_image_id)
									$product_image_link[$i][1] = $product_obj->id.'-'.$cover_image_id['id_image'];
								else
				                {
				                    $product_image_link[$i][1] = "";
				                    $product_image_link[$i][2] = $this->context->language->iso_code;
								}
								$i++;
							}

							if (isset($all_product_id))
							{
								if(is_array($all_product_id))
									$count_latest_pro = count($all_product_id);
							}
							else
							{
								$count_latest_pro = 0;
								$all_product_id="";
								$product_image_link="";
								$all_product_name="";
								$all_product_price="";
							}
							
							$obj_reviews = new Reviews();
				            $reviews_info = $obj_reviews->getSellerReviews($marketplace_seller_id);
							if($reviews_info)
							{
								$reviews_details = array();
								$i = 0;
								$rating = 0;
								foreach($reviews_info as $reviews)
								{
							    	if($i < 2)
									{
							   			$obj_customer = new Customer($reviews['id_customer']);
							   			if($reviews['id_customer'])
							   			{
											$reviews_details[$i]['customer_name'] = $obj_customer->firstname." ".$obj_customer->lastname;
											$reviews_details[$i]['customer_email'] = $obj_customer->email;
							   			}
							   			else
							   			{
							    			$reviews_details[$i]['customer_name'] = "Not Available";
											$reviews_details[$i]['customer_email'] = "Not Available";
							   			}
								   		$reviews_details[$i]['rating'] = $reviews['rating'];
								   		$reviews_details[$i]['review'] = $reviews['review'];
								   		$reviews_details[$i]['time'] = $reviews['date_add'];
							   		}
							   		$rating = $rating + $reviews['rating'];
							   		$i++;
							 	}
								$avg_rating = (double)($rating/$i);
								$reviews_count = count($reviews_info);
								$this->context->smarty->assign("reviews_count", $reviews_count);
								$this->context->smarty->assign("avg_rating", $avg_rating);
								$this->context->smarty->assign("reviews_details", $reviews_details);
							}
							else
							{
						 		$this->context->smarty->assign("reviews_count",0);
					 			$this->context->smarty->assign("avg_rating", 0);
							}

							// menu link create
							$param = array('shop'=>$id_shop);					
							$link_collection = $link->getModuleLink('marketplace','shopcollection',$param);
							$seller_profile = $link->getModuleLink('marketplace','sellerprofile',$param);
							$link_store = $link->getModuleLink('marketplace','shopstore',$param);
							$link_contact = $link->getModuleLink('marketplace','contact',$param);
							$account_dashboard = $link->getModuleLink('marketplace', 'marketplaceaccount',$param);
							
							$param1 = array('flag'=>'1','all_reviews'=>'1','shop'=>$id_shop);
							$all_reviews_links = $link->getModuleLink('marketplace','allreviews',$param1);
				            $logo_path = 'modules/marketplace/img/seller_img/'.$marketplace_seller_id_info['marketplace_seller_id'].'.jpg';

							if (file_exists($logo_path))
							  $path = _MODULE_DIR_ . 'marketplace/img/seller_img/'.$marketplace_seller_id_info['marketplace_seller_id'].'.jpg';
				            else
							  $path = _MODULE_DIR_ . 'marketplace/img/seller_img/defaultimage.jpg';

                            $this->context->smarty->assign('seller_img_path',$path);	
							$this->context->smarty->assign("id_product",$id_product);
							$this->context->smarty->assign("phone",$phone);
							$this->context->smarty->assign("fax",$fax);
							$this->context->smarty->assign("business_email",$business_email);
							$this->context->smarty->assign("count_latest_pro",$count_latest_pro);
							$this->context->smarty->assign("all_product_id",$all_product_id);
							$this->context->smarty->assign("product_image_link",$product_image_link);
							$this->context->smarty->assign("all_product_price",$all_product_price);
							$this->context->smarty->assign("all_product_name",$all_product_name);
							$this->context->smarty->assign("id_shop",$id_shop);
							$this->context->smarty->assign("seller_id",$marketplace_seller_id);
							$this->context->smarty->assign("shop_name",$shop_name);
							$this->context->smarty->assign("id_customer",$id_customer);
							$this->context->smarty->assign("market_place_seller_info",$market_place_seller_info);
							$this->context->smarty->assign("customer_info",$customer_info);
							$this->context->smarty->assign("module_path",_MODULE_DIR_);
							$this->context->smarty->assign("link_contact",$link_contact);
							$this->context->smarty->assign("link_store",$link_store);
							$this->context->smarty->assign("link_collection",$link_collection);
							$this->context->smarty->assign("seller_profile",$seller_profile);
							$this->context->smarty->assign("all_reviews_links",$all_reviews_links);
							$this->context->smarty->assign("account_dashboard", $account_dashboard);
							$this->setTemplate('seller-profile.tpl'); 
							
						} //market_place_seller_info check close
						else
						{
							Tools::redirect(__PS_BASE_URI__.'pagenotfound');
						}
					} //is_seller_active statuc close
					else
					{
						Tools::redirect(__PS_BASE_URI__.'pagenotfound'); // seller is deactivated by admin
					}
				} //marketplace_seller_id_info check close
				else
				{
					Tools::redirect(__PS_BASE_URI__.'pagenotfound');
				}
			} // marketplace_shop check close
			else 
			{
				Tools::redirect(__PS_BASE_URI__.'pagenotfound');
			}
		} // id shop ckeck close
		else
		{
			Tools::redirect(__PS_BASE_URI__.'pagenotfound');
		}
		parent::initContent();
	}

	public function setMedia() 
	{
		parent::setMedia();
		$this->addCSS(_MODULE_DIR_.'marketplace/css/store_profile.css');
		$this->addCSS(_MODULE_DIR_.'marketplace/views/css/product_slider/ps_gray.css');
		$this->addJS(_MODULE_DIR_.'marketplace/rateit/lib/jquery.raty.min.js');
		$this->addJS(_MODULE_DIR_.'marketplace//views/js/mp_product_slider.js');
		$this->addJS(_MODULE_DIR_.'marketplace/views/js/seller_review.js');
	}
}
?>