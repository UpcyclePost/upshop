<?php
if (!defined('_PS_VERSION_'))
	exit;
class marketplaceallreviewsModuleFrontController extends ModuleFrontController	
{
 public function initContent() 
 {
	
	$seller_id = Tools::getValue('seller_id');
	$link = new Link();
	$obj_reviews = new Reviews();
	
	$reviews_info = $obj_reviews->getSellerReviews($seller_id);
	if($reviews_info)
	{
	    $reviews_details = array();
		$i = 0;
		foreach($reviews_info as $reviews)
		{
			$customer_info =Db::getInstance()->getRow("select * from `"._DB_PREFIX_."customer` where `id_customer`=".$reviews['id_customer']."");
			$customer_info = new Customer($reviews['id_customer']);
			if($customer_info)
			{
				$reviews_details[$i]['customer_name'] = $customer_info->firstname." ".$customer_info->lastname;
				$reviews_details[$i]['customer_email'] = $customer_info->email;
			}
		    else
			{
				$reviews_details[$i]['customer_name'] = "Not Available";
			    $reviews_details[$i]['customer_email'] = "Not Available";
		    }
				   
			$reviews_details[$i]['rating'] = $reviews['rating'];
		    $reviews_details[$i]['review'] = $reviews['review'];
		    $reviews_details[$i]['time'] = $reviews['date_add'];
				   
			$i++;
		}
		$reviews_count = count($reviews_info);
		$this->context->smarty->assign("reviews_count", $reviews_count);
		$this->context->smarty->assign("reviews_details", $reviews_details);
	}
	else
	 $this->context->smarty->assign("reviews_count", 0);
	


	$customer_id = $this->context->cookie->id_customer;
	if($customer_id)
	{
		$market_place_shop = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `id`,`shop_name`,`is_active` ,`about_us` from `" . _DB_PREFIX_ . "marketplace_shop` where id_customer =" . $customer_id . " ");
		$seller_info = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select `shop_name` from `" . _DB_PREFIX_ . "marketplace_seller_info` where id =" . $seller_id . " ");
		if($market_place_shop)
		{
			if($market_place_shop['shop_name'] == $seller_info['shop_name'])
			{
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
				$this->context->smarty->assign("is_seller", 1);
				$this->context->smarty->assign("logic",'all_reviews');
				$this->context->smarty->assign("cust",'1');
			}else{
				$this->context->smarty->assign("cust",'0');
			}
			
		}else{
			$this->context->smarty->assign("cust",'0');
		}
		
	}else{
		$this->context->smarty->assign("cust",'0');
	}
	
	$this->setTemplate('all_reviews.tpl');
	parent::initContent();
 }

 public function setMedia() 
 {
	parent::setMedia();
	$this->addCSS(_MODULE_DIR_.'marketplace/css/shop_store.css');
	$this->addCSS(_MODULE_DIR_.'marketplace/css/store_profile.css');
	$this->addCSS(_MODULE_DIR_.'marketplace/css/marketplace_account.css');
	$this->addCSS(_MODULE_DIR_.'marketplace/css/all_reviews.css');
 } 
}
?>