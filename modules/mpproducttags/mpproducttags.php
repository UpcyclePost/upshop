<?php
	if (!defined('_PS_VERSION_'))
	exit;
	include_once dirname(__FILE__).'/../marketplace/classes/SellerProductDetail.php';
	include_once dirname(__FILE__).'/../marketplace/classes/MarketplaceShopProduct.php';
	include_once dirname(__FILE__).'/classes/MarketplaceProductTag.php';
	class mpproducttags extends Module {
		const INSTALL_SQL_FILE = 'install.sql';
		
		public function __construct() {
			$this->name = 'mpproducttags';
			$this->version = '0.6';
			$this->author = 'WEBKUL';
			$this->need_instance = 1;
			
			parent::__construct();
			$this->displayName = $this->l('Product Tags');
			$this->description = $this->l('Provide adding tags for a product');
		}
		
		
		public function hookDisplayMpaddproductfooterhook($params) {
			global $smarty;
			global $cookie;	
			if(Tools::getValue('shop')) {
				$mp_id_shop = Tools::getValue('shop');
				$mp_seller_id = MarketplaceShop::findMpSellerIdByShopId($mp_id_shop);
				
			}
			return $this->display(__FILE__, 'add_product_tag.tpl');
		}
		
		public function hookDisplayMpupdateproductfooterhook($params) {
			global $smarty;
			global $cookie;
			$mp_product_id = Tools::getValue('id');
			$obj_mp_seller_product_detail = new SellerProductDetail($mp_product_id);
			$mp_id_shop = $obj_mp_seller_product_detail->id_shop;
			$obj_mp_product_tag = new MarketplaceProductTag();
			$product_tags = $obj_mp_product_tag->getProductTags($mp_product_id);
			if(!empty($product_tags)){
				foreach($product_tags as $tag_id){
					$tag_name = $obj_mp_product_tag->getTagnameById($tag_id['id']);
					$tag_arr[] = $tag_name['name'];
				}
			$product_tag = implode('","', $tag_arr);
		}
				if($product_tag){
					$smarty->assign("editproduct",$_GET['editproduct']);
					$smarty->assign('product_tag',$product_tag);
			 	}
			 		
			
			return $this->display(__FILE__, 'add_product_tag.tpl');
			
		}
		
		public function hookActionBeforeAddproduct($params) {
			$flag = 0;
			$link = new Link();
			$mp_id_shop = Tools::getValue('shop');
			$product_tag = Tools::getValue('hidden-tags');
			$tag_arr = explode(",",$product_tag);
			$obj_mp_product_tag = new MarketplaceProductTag();
			if(!empty($tag_arr)){
				foreach($tag_arr as $tag){
					$isvalidtag = preg_match('/^[^!<>;?=+#"°{}_$%]*$/u', $tag);
					if($isvalidtag == 0){
						$flag = 1;
					}
				}
			}
		
			if($flag ==1) {
				$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'invalid_tag'=>1));
				Tools::redirect($add_product_link);
			}

		}
		public function hookDisplayMpaddproductheaderhook($params) {
			global $smarty;
			global $cookie;
			$link = new Link();
			$error = Tools::getValue('invalid_tag');
			if($error) {
				$smarty->assign("invalid_tag","1");
				return $this->display(__FILE__, 'invalid_tag.tpl');
			}			
		}
		public function hookActionAddproductExtrafield($params) {
			$marketplace_product_id = $params['marketplace_product_id'];
			$id_lang = Context::getContext()->language->id;
			$product_tag = Tools::getValue('hidden-tags');
			if($product_tag!=''){
			$tag_arr = explode(",",$product_tag);
			$obj_mp_product_tag = new MarketplaceProductTag();
				foreach($tag_arr as $tag){
					$obj_tag = new Tag();
					$obj_tag->id_lang = $id_lang;
					$tag_exist = $obj_mp_product_tag->checkIfTagExist($tag);
					if(empty($tag_exist)){
						$obj_tag->name = $tag;
						$obj_tag->add();
						$tag_ids[] = Db::getInstance()->Insert_ID();
					}else{
						$tag_id_arr = $obj_mp_product_tag->getTagIdByName($tag);
						$tag_ids[] = $tag_id_arr['id_tag'];
					}
				}
					if(!empty($tag_ids)){
							foreach ($tag_ids as $tag_id) {
								$obj_mp_product_tag->mp_product_id = $marketplace_product_id;
								$obj_mp_product_tag->tag_id = $tag_id;
								$obj_mp_product_tag->add();
							}
					
				}
				$approve_type = Configuration::getGlobalValue('PRODUCT_APPROVE');
				if($approve_type == 'default'){
					$obj_mpshop_pro = new MarketplaceShopProduct();
					$product_detail = $obj_mpshop_pro->findMainProductIdByMppId($marketplace_product_id);
					$ps_shop_id = $obj_mpshop_pro->findShopIdByMpsid($marketplace_product_id);
					$ps_product_id = $product_detail['id_product'];
				  	if(!empty($ps_product_id)){
						$product_tags = $obj_mp_product_tag->getProductTags($marketplace_product_id);
						$obj_product = new Product($ps_product_id,false,null,$ps_shop_id,null);
						$obj_product->setWsTags($product_tags);
					}
				}
			}
		}
		
	
		
		public function hookActionBeforeUpdateproduct($params) {
			$flag = 0;
			$link = new Link();
			$mp_id_shop = Tools::getValue('shop');
			$product_tag = Tools::getValue('hidden-tags');
			$tag_arr = explode(",",$product_tag);
			$obj_mp_product_tag = new MarketplaceProductTag();
			if(!empty($tag_arr)){
				foreach($tag_arr as $tag){
					$isvalidtag = preg_match('/^[^!<>;?=+#"°{}_$%]*$/u', $tag);
					if($isvalidtag == 0){
						$flag = 1;
					}
				}
			}
		
			if($flag ==1) {
				$add_product_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'invalid_tag'=>1));
				Tools::redirect($add_product_link);
			}
		}
		
		public function hookActionUpdateproductExtrafield($params) {
			$mp_product_id = $params['marketplace_product_id'];
			$obj_mpshop_pro = new MarketplaceShopProduct();
			$product_detail = $obj_mpshop_pro->findMainProductIdByMppId($mp_product_id);
			$ps_shop_id = $obj_mpshop_pro->findShopIdByMpsid($mp_product_id);
			$ps_product_id = $product_detail['id_product'];
			$id_lang = Context::getContext()->language->id;
			$obj_mp_product_tag = new MarketplaceProductTag();
			$tags = Tools::getValue('hidden-tags');
			if($tags!=''){
				$tag_arr = explode(",",$tags);
					foreach($tag_arr as $tag){
						$obj_tag = new Tag();
						$obj_tag->id_lang = $id_lang;
						$tag_exist = $obj_mp_product_tag->checkIfTagExist($tag);
						if(empty($tag_exist)){
							$obj_tag->name = $tag;
							$obj_tag->add();
							$tag_ids[] = Db::getInstance()->Insert_ID();
						}else{
							$tag_id_arr = $obj_mp_product_tag->getTagIdByName($tag);
							$tag_ids[] = $tag_id_arr['id_tag'];
						}
					}
					if(!empty($tag_ids)){
						$delete_mp_product_tag = $obj_mp_product_tag->deleteMpProductTags($mp_product_id);
						if($delete_mp_product_tag){
							foreach ($tag_ids as $tag_id) {
								$obj_mp_product_tag->mp_product_id = $mp_product_id;
								$obj_mp_product_tag->tag_id = $tag_id;
								$obj_mp_product_tag->add();
							}
						
						}
					
					}
			  
			
			if(!empty($ps_product_id)){
				$product_tags = $obj_mp_product_tag->getProductTags($mp_product_id);
				$obj_product = new Product($ps_product_id,false,null,$ps_shop_id,null);
				$obj_product->setWsTags($product_tags);
				}
			}else{
				$delete_mp_product_tag = $obj_mp_product_tag->deleteMpProductTags($mp_product_id);
				$obj_product = new Product($ps_product_id,false,null,$ps_shop_id,null);
				$obj_product->deleteTags();
			}
		}
		public function hookActionToogleProductStatus($params) {
		
			$mp_product_id = Tools::getValue('id');
			$ps_product_id = $params['main_product_id'];
			$obj_mp_shop_product = new MarketplaceShopProduct();
			$ps_shop_id = $obj_mp_shop_product->findShopIdByMpsid($mp_product_id);
			$id_lang = Context::getContext()->language->id;
			$obj_mp_product_tag = new MarketplaceProductTag();
			$product_tags = $obj_mp_product_tag->getProductTags($mp_product_id);
			if(!empty($product_tags)){
				$obj_product = new Product($ps_product_id,false,null,$ps_shop_id,null);
				$obj_product->setWsTags($product_tags);
				}
				
		}

	
		
		public function install() {
			$ismpinstall = Module::isInstalled('marketplace');
			 if($ismpinstall) {
				if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
						return (false);
					else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
						return (false);
					$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
					$sql = preg_split("/;\s*[\r\n]+/", $sql);
					foreach ($sql AS $query)
						if($query)
							if(!Db::getInstance()->execute(trim($query)))
								return false;
				if (!parent::install() 		
					|| !$this->registerHook('displayMpaddproductfooterhook') 
					|| !$this->registerHook('displayMpupdateproductfooterhook') 
					|| !$this->registerHook('actionBeforeAddproduct')
					|| !$this->registerHook('displayMpaddproductheaderhook')
					|| !$this->registerHook('actionAddproductExtrafield')
					|| !$this->registerHook('actionUpdateproductExtrafield')
					|| !$this->registerHook('actionBeforeUpdateproduct')
					|| !$this->registerHook('actionToogleProductStatus')
					)
					return false;
				else {
					return true;
				}
			} else {
				$this->errors[] = Tools::displayError($this->l('Marketplace Module Not install.'));
				return false;
			}
		}
	
		public function drop_table($table_name_without_prefix) {
			$drop =Db::getInstance()->execute("DROP TABLE `"._DB_PREFIX_.$table_name_without_prefix."`");
			if(!$drop)
				return false;
			return true;	

		}
		public function uninstall() {

			if (parent::uninstall() == false  || !$this->drop_table('mp_product_tags')) 
				return false; 

			return true;

		}
	}
?>