<?php
if (!defined('_PS_VERSION_'))
	exit;

include_once dirname(__FILE__).'/../marketplace/classes/SellerProductDetail.php';
include_once dirname(__FILE__).'/../marketplace/classes/MarketplaceShopProduct.php';
include_once dirname(__FILE__).'/classes/MarketplaceProductSeo.php';

class mpproductseo extends Module 
{
	const INSTALL_SQL_FILE = 'install.sql';
	public function __construct() 
	{
		$this->name = 'mpproductseo';
		$this->tab = 'front_office_features';
		$this->version = '1.6';
		$this->author = 'Webkul';
		$this->need_instance = 1;
		$this->dependencies = array('marketplace');
		parent::__construct();
		$this->displayName = $this->l('Product SEO addon');
		$this->description = $this->l('Provide adding meta tags for a product');
	}
	
	
	public function hookDisplayMpaddproductfooterhook() 
	{
		if(Tools::getValue('shop'))
			$mp_id_shop = Tools::getValue('shop');

		return $this->display(__FILE__, 'add_product_seo.tpl');
	}
	
	public function hookDisplayMpupdateproductfooterhook() 
	{
		$mp_product_id = Tools::getValue('id');
		$obj_mp_seller_product_detail = new SellerProductDetail($mp_product_id);
		$obj_product_seo = new MarketplaceProductSeo();
		$meta_info = $obj_product_seo->getMetaInfo($mp_product_id);
		if($meta_info)
		{
			$this->context->smarty->assign('editproduct', $_GET['editproduct']);
			$this->context->smarty->assign('meta_info', $meta_info);
	 	}
		return $this->display(__FILE__, 'add_product_seo.tpl');
	}

	public function hookActionAddproductExtrafield($params) 
	{
		$marketplace_product_id = $params['marketplace_product_id'];
		$meta_title = Tools::getValue('meta_title');
		$meta_desc = Tools::getValue('meta_desc');
		$product_name = Tools::getValue('product_name');
		$friendly_url = Tools::getValue('friendly_url');
		if($friendly_url == ""){
			$friendly_url = $product_name;
		}else{
			$friendly_url = $friendly_url;
		}
		$obj_mp_product_seo = new MarketplaceProductSeo();
		$obj_mp_product_seo->mp_product_id = $marketplace_product_id;
		$obj_mp_product_seo->meta_title = $meta_title;
		$obj_mp_product_seo->meta_description = $meta_desc;
		$obj_mp_product_seo->friendly_url = Tools::link_rewrite($friendly_url);
		$obj_mp_product_seo->add();
		$approve_type = Configuration::getGlobalValue('PRODUCT_APPROVE');
		if($approve_type == 'default')
		{
			$obj_mpshop_pro = new MarketplaceShopProduct();
			$product_detail = $obj_mpshop_pro->findMainProductIdByMppId($marketplace_product_id);
			$ps_product_id = $product_detail['id_product'];
			if(!empty($ps_product_id))
			{
				$obj_product = new Product($ps_product_id);
				foreach (Language::getLanguages(true) as $lang)
				{
					$obj_product->meta_description[$lang['id_lang']] = $meta_desc;
					$obj_product->meta_title[$lang['id_lang']] = $meta_title;
					$obj_product->link_rewrite[$lang['id_lang']] = Tools::link_rewrite($friendly_url);
				}
				$obj_product->save();
			}

		}
	}
	
	public function hookActionUpdateproductExtrafield($params) 
	{
		$mp_product_id = $params['marketplace_product_id'];
		$obj_mpshop_pro = new MarketplaceShopProduct();
		$product_detail = $obj_mpshop_pro->findMainProductIdByMppId($mp_product_id);
		$ps_product_id = $product_detail['id_product'];
		$meta_title = Tools::getValue('meta_title');
		$meta_desc = Tools::getValue('meta_desc');
		$product_name = Tools::getValue('product_name');
		$friendly_url = Tools::getValue('friendly_url');
		if($friendly_url == ""){
			$friendly_url = $product_name;
		}else{
			$friendly_url = $friendly_url;
		}
		$obj_mp_product_seo = new MarketplaceProductSeo();
		$meta_id = $obj_mp_product_seo->getMetaInfoId($mp_product_id);
		if ($meta_id)
			$obj_mp_product_seo->id = $meta_id['id'];
		
		$obj_mp_product_seo->mp_product_id = $mp_product_id;
		$obj_mp_product_seo->meta_title = $meta_title;
		$obj_mp_product_seo->meta_description = $meta_desc;
		$obj_mp_product_seo->friendly_url = Tools::link_rewrite($friendly_url);
		$obj_mp_product_seo->save();
		$meta_info = $obj_mp_product_seo->getMetaInfo($mp_product_id);
		if (!empty($ps_product_id))
		{
			$obj_product = new Product($ps_product_id);
			foreach (Language::getLanguages(true) as $lang)
			{
				$obj_product->meta_description[$lang['id_lang']] = $meta_info['meta_description'];
				$obj_product->meta_title[$lang['id_lang']] = $meta_info['meta_title'];
				$obj_product->link_rewrite[$lang['id_lang']] = Tools::link_rewrite($meta_info['friendly_url']);
			}
			$obj_product->save();
		}
	}

	public function hookActionToogleProductStatus($params) 
	{
	
		$mp_product_id = Tools::getValue('id');
		$ps_product_id = $params['main_product_id'];
		$obj_mp_shop_product = new MarketplaceShopProduct();
		$obj_product_seo = new MarketplaceProductSeo();
		$meta_info = $obj_product_seo->getMetaInfo($mp_product_id);
		if (!empty($meta_info))
		{
			$obj_product = new Product($ps_product_id);
			foreach (Language::getLanguages(true) as $lang)
			{
				$obj_product->meta_description[$lang['id_lang']] = $meta_info['meta_description'];
				$obj_product->meta_title[$lang['id_lang']] = $meta_info['meta_title'];
				$obj_product->link_rewrite[$lang['id_lang']] = Tools::link_rewrite($meta_info['friendly_url']);
			}
			$obj_product->save();
		}

	}
	
	public function install() 
	{
		if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			return (false);
		else if (!$sql = Tools::file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			return (false);

		$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
		$sql = preg_split("/;\s*[\r\n]+/", $sql);

		foreach ($sql as $query)
			if($query)
				if(!Db::getInstance()->execute(trim($query)))
					return false;

		if (!parent::install() 		
			|| !$this->registerHook('displayMpaddproductfooterhook') 
			|| !$this->registerHook('displayMpupdateproductfooterhook') 
			|| !$this->registerHook('actionBeforeAddproduct')
			|| !$this->registerHook('actionAddproductExtrafield')
			|| !$this->registerHook('actionUpdateproductExtrafield')
			|| !$this->registerHook('actionBeforeUpdateproduct')
			|| !$this->registerHook('actionToogleProductStatus')
			)
			return false;

		return true;
	}

	public function dropTable($table_name_without_prefix) 
	{
		$drop =Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.$table_name_without_prefix);
		if(!$drop)
			return false;
		return true;
	}

	public function uninstall() 
	{
		if (!parent::uninstall() || !$this->dropTable('mp_product_seo')) 
			return false; 
		return true;
	}
}
?>