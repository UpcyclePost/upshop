<?php
class marketplaceProductimageeditModuleFrontController extends ModuleFrontController
{
	public function init()
	{
		$this->display_header = false;
		$this->display_footer = false;
	}

	public function initContent()
	{
		//parent::initContent();
		$id_lang = $this->context->cookie->id_lang;
		$seller_product_id = Tools::getValue('id_product');
		$image_id = Tools::getValue('id_image');
		$is_delete = Tools::getValue('delete');
		$unactive_img = Tools::getValue('unactive');
		$changecover = Tools::getValue('changecover');
		$img_ps_dir = _MODULE_DIR_."marketplace/img/";
		$modules_dir = _MODULE_DIR_;
		if(isset($seller_product_id) AND $seller_product_id>0) 
		{
			$obj_marketplace_product = new SellerProductDetail();
			$is_product_onetime_activate = $obj_marketplace_product->getMarketPlaceShopProductDetailBYmspid($seller_product_id);
			if($is_product_onetime_activate) 
			{
				$link = new Link();
				$id_product = $is_product_onetime_activate['id_product'];
				$product = new Product($id_product);
				$id_image_detail = $product->getImages($id_lang);
				
				$product_link_rewrite = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * FROM `". _DB_PREFIX_."product_lang` WHERE id_product=".$id_product." and id_lang=".$id_lang);
				$name = $product_link_rewrite['link_rewrite'];

				if(!empty($id_image_detail)) 
				{
					$id_image = array();
					$image_link = array();
					$is_cover = array();
					$position = array();
					foreach($id_image_detail as $id_image_info) 
					{
						$id_image[] = $id_image_info['id_image'];
						$ids = $id_product.'-'.$id_image_info['id_image'];
						$image_link[] = $link->getImageLink($name,$ids);
						$is_cover[] = $id_image_info['cover'];
						$position[] = $id_image_info['position'];
					}
					$this->context->smarty->assign("id_image",$id_image);
					$this->context->smarty->assign("image_link",$image_link);
					$this->context->smarty->assign("is_cover",$is_cover);
					$this->context->smarty->assign("position",$position);
					$this->context->smarty->assign("id_product",$id_product);
				}

				$unactive_image = $obj_marketplace_product->unactiveImage($seller_product_id);
				if($unactive_image) 
					$this->context->smarty->assign("unactive_image",$unactive_image);
					
				
				$this->context->smarty->assign("product_activated",1);
			}
			else
			{
				$unactive_image_only = $obj_marketplace_product->unactiveImage($seller_product_id);
				if($unactive_image_only) 
					$this->context->smarty->assign("unactive_image_only",$unactive_image_only);
			}
			$this->context->smarty->assign("img_ps_dir",$img_ps_dir);
			$this->context->smarty->assign("modules_dir",$modules_dir);
			$this->setTemplate('imageedit.tpl');
		}

		//Delete active image
		if($image_id AND $is_delete)
		{
			$id_image = Tools::getValue('id_image');
			$image = new Image($id_image );
			$status = $image->delete();
			Product::cleanPositions($id_image );
			$delete =  Db::getInstance()->delete('image','id_image='.$id_image .' and id_product='.$id_image);
			if($status) 
				echo 1;
			else
				echo 0;
		}

		//Delete unactive image
		if($image_id AND $unactive_img)
		{
			$id_image = Tools::getValue('id_image');
			if(!$id_image)
				$id_image = '';
			$img_name = Tools::getValue('img_name');
			if(!$img_name)
				$img_name = '';
			$delete =  Db::getInstance()->delete("marketplace_product_image","id=".$id_image." and seller_product_image_id	='".$img_name."'");
			$dir = _MODULE_DIR_.'marketplace/img/product_img/';
			
			if($delete) 
			{
				unlink($dir.$img_name.'jpg');
				echo 1;
			} 
			else
				echo 0;
		}

		//Change covor status
		if($image_id AND $changecover)
		{
			$id_image = Tools::getValue('id_image');
			if($id_image)
			{
				$is_cover = Tools::getValue('is_cover');
				$id_pro = Tools::getValue('id_pro');
				$product = new Product($id_pro);
				$product->setCoverWs($id_image);
				echo 1;
			} 
			else
				echo 0;
		}
	}
	public function setMedia() 
 	{
		parent::setMedia();
		$this->addCSS(_MODULE_DIR_.'marketplace/css/image_edit.css');
 	} 
}
?>
