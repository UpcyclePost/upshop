<?php
if (!defined('_PS_VERSION_'))
	exit;
class mpshopbannerbannerfrontactionModuleFrontController extends ModuleFrontController	
{
	public function initContent() 
	{
		parent::initContent();
		$fun = Tools::getValue('fun');
		$mp_banner_id = Tools::getValue('mp_banner_id');
		
		if($fun=='delete') 
		{
			if ($this->deleteBanner($mp_banner_id))
				echo 1;
			else
				echo 0;
		}
		if($fun=='change_status') 
		{
			$mp_id_shop = Tools::getValue('shop');
			$obj = new MarketplaceShopBanner();
			$obj->setAllToInactive($mp_id_shop);
			$obj->setBannerActiveById($mp_banner_id);
			$link = new Link();
			$request_link = $link->getModuleLink('mpshopbanner','viewbannerlist');
			Tools::redirect($request_link);
		}
	}
	
	public function deleteBanner($mp_banner_id)
	{
		if (!$mp_banner_id)
			return false;
		$delete = Db::getInstance()->delete('marketplace_shop_banner','id='.$mp_banner_id);
		unlink('modules/mpshopbanner/img/banner_image/'.$mp_banner_id.'.jpg');
		if(!$delete)
			return false;
		return true;
	}
}
?>