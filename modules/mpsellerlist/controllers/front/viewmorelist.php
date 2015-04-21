<?php

if(!defined('_PS_VERSION_')) exit;
class mpsellerlistviewmorelistModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$link = new Link();
		$obj_seller = new SellerInfoDetail();
		$alp = Tools::getValue('alp');
		$submit_search_seller = Tools::getValue('submit_search_seller');
		$all_active_seller = false;
		if ($alp)
		{
			$this->context->smarty->assign('alph', $alp);
			$all_active_seller = $obj_seller->findAllActiveSellerInfoByLimit(false, false, true, false, $alp);
		}
		else
		{
			if ($submit_search_seller)
			{
				$all_active_seller = $obj_seller->findAllActiveSellerInfoByLimit(false, false, true, true, Tools::getValue('search_seller_block_input'));
				$this->context->smarty->assign('alph', '0');
			}
			else
			{
				$all_active_seller = $obj_seller->findAllActiveSellerInfoByLimit();
				$this->context->smarty->assign('alph', '0');
			}
		}

		$active_seller = 1;
		if($all_active_seller) 
		{
			$shop_store_link = array();
			$shop_img = array();
			foreach($all_active_seller as $act_seller) 
			{
				$shop_store_link[] = $link->getModuleLink('marketplace','shopstore', array('shop' => $act_seller['mp_shop_id']));
				$img_file = 'modules/marketplace/img/shop_img/'.$act_seller['id'].'-'.$act_seller['shop_name'].'.jpg';
				if(file_exists($img_file))	
					$shop_img[] = $act_seller['id'].'-'.$act_seller['shop_name'].'.jpg';
				else
					$shop_img[] = 'defaultshopimage.jpg';
			}
			$this->context->smarty->assign('shop_img', $shop_img);
			$this->context->smarty->assign('shop_store_link', $shop_store_link);
			$this->context->smarty->assign('all_active_seller', $all_active_seller);
		} 
		else
		 	$active_seller = 0;
		 		
		$viewmorelist_link = $link->getModuleLink('mpsellerlist','viewmorelist');
		$this->context->smarty->assign('viewmorelist_link', $viewmorelist_link);
		$this->context->smarty->assign('active_seller', $active_seller);
		$this->setTemplate('viewmorelist.tpl');
	}

		public function setMedia() 
		{
			parent::setMedia();
			$this->addCSS(_MODULE_DIR_.'mpsellerlist/views/css/sellerlist.css');
		}
	}

?>