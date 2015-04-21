<?php
if (!defined('_PS_VERSION_'))
	exit;
class mpshopbanneraddnewbannerprocessModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$link = new Link();		
		$banner_name = Tools::getValue('banner_name');
		$is_active = Tools::getValue('group1');
		$id_shop = Tools::getValue('shop');
		$obj = new MarketplaceShopBanner();
		if($is_active == 1)
			$obj->setAllToInactive($id_shop);
		
		if($id_shop)
		{
			$obj->name = $banner_name;
			$obj->mp_id_shop = $id_shop;
			$obj->is_active = $is_active;
			if ($_FILES["file"]["size"] > 0)
			{
				$banner_id = $obj->add();
				if($banner_id)
				{
					$dir = 'modules/mpshopbanner/img/banner_image/';
					if ($_FILES["file"]["error"] == 0)
						move_uploaded_file($_FILES["file"]["tmp_name"], $dir.''.$banner_id.'.jpg');
				}
				$request_link = $link->getModuleLink('mpshopbanner', 'viewbannerlist');
				Tools::redirect($request_link);
			}
			else
			{
				$extra = array('error'=>'1');
				$request_link = $link->getModuleLink('mpshopbanner', 'addnewbanner', $extra);
				Tools::redirect($request_link);
			}
		}
	}
}
?>