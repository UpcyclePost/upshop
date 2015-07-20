<?php
	include_once dirname(__FILE__).'/../../classes/MarketplaceClassInclude.php';
	class AdminSellerProductDetailController extends ModuleAdminController 
	{

		public function __construct() 
		{

			$this->bootstrap = true;
			$this->table       = 'marketplace_seller_product';
			$this->className   = 'SellerProductDetail';
			$this->lang        = false;
		    $this->context     = Context::getContext();

			$i=0;
			
			$this->addRowAction('edit');
			$this->addRowAction('delete');
			$this->addRowAction('view');
			
			//$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_shop` mps ON (mps.`id` = a.`id_shop`)';
			$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_seller_info` mpsin ON (mpsin.`id` = a.`id_seller`)';
			$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_customer` mpc ON (mpc.`marketplace_seller_id` = a.`id_seller`)';
			$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'marketplace_shop_product` msp ON (msp.`marketplace_seller_id_product` = a.`id`)';
			$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.`id_product` = msp.`id_product`)';
			//$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'product_carrier` pc ON (p.id_product=pc.id_product)';
			//$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'order_detail` od ON (p.id_product=od.product_id)';
			$this->_select = 'mpsin.`shop_name`,mpsin.`seller_name`,mpc.`id_customer`,msp.id_product plink,msp.id_product pp,p.price,a.id as view,p.`id_product` as orders, p.`id_product` as shipping';
			
			$this->fields_list = array();
			$this->fields_list['view'] = array(
				'title' => $this->l('View'),
				'align' => 'center',
				'class' => 'fixed-width-xs',
				'orderby' => false,
				'search' => false,
				'callback' => 'printViewIcons',
				'remove_onclick' => true
			);
			
			$this->fields_list['id'] = array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'class' => 'fixed-width-xs',
				'remove_onclick' => true
			);
			
			$this->fields_list['id_customer'] = array(
				'title' => $this->l('Customer'),
				'align' => 'center',
				'callback' => 'editCustomerlink',
				'orderby' => false,
				'search' => false,
				'remove_onclick' => true
			);
			
			$this->fields_list['seller_name'] = array(
				'title' => $this->l('Seller Name'),
				'align' => 'center',
				'callback' => 'printSellerIcons',
				'remove_onclick' => true
			);
			
			$this->fields_list['pp'] = array(
				'title' => $this->l('Prestashop Link'),
				'align' => 'center',
				'callback' => 'printPPIcons',
				'orderby' => false,
				'search' => false,
				'remove_onclick' => true
			);
			
			$this->fields_list['plink'] = array(
				'title' => $this->l('Product page (Link)'),
				'align' => 'center',
				'callback' => 'printPLinkIcons',
				'orderby' => false,
				'search' => false,
				'remove_onclick' => true
			);
			
			$this->fields_list['product_name'] = array(
				'title' => $this->l('Product Name'),
				'align' => 'center',
				'remove_onclick' => true
			);
			
			$this->fields_list['orders'] = array(
				'title' => $this->l('Orders(Y/N)'),
				'align' => 'center',
				'search' => false,
				'callback' => 'printOrders',
				'remove_onclick' => true
			);
			
			$this->fields_list['shipping'] = array(
				'title' => $this->l('Shipping(Y/N)'),
				'align' => 'center',
				'search' => false,
				'callback' => 'printShipping',
				'remove_onclick' => true
			);
			
			$this->fields_list['shop_name'] = array(
				'title' => $this->l('Shop Name'),
				'align' => 'center',
				'remove_onclick' => true
			);
			
			$this->fields_list['price'] = array(
				'title' => $this->l('Price (tax excl.)'),
				'align' => 'center',
				'type' => 'price',
				'remove_onclick' => true
			);
			
			$hook_column = Hook::exec('addColumnInSellerProductTable', array('flase' => 1));
			
			if($hook_column){
				$column = explode('-',$hook_column);
				$num_colums = count($column);
				for($i=0; $i<$num_colums;$i = $i+2) {
					$this->fields_list[$column[$i]] = array(
						'title' => $this->l($column[$i+1]),
						'align' => 'center'
					);
					
				}
			}
				
			$this->fields_list['active'] = array(
				'title' => $this->l('Status'),
				'active' => 'status',
				'align' => 'center',
				'type' => 'bool',
				'orderby' => false,
				'remove_onclick' => true
			);	
			
			  // $this->list_no_link = true;
      if ($_GET['submitFiltermarketplace_seller_product']!='')
		{
			$_POST['submitFilter'] = '';
			$_POST['submitFiltermarketplace_seller_product'] = 1;
			$_POST['marketplace_seller_productFilter_shop_name'] = Tools::getValue('marketplace_seller_productFilter_shop_name');
		}
		
			$this->identifier  = 'id';
			$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));
			parent::__construct();

		}
		
	public function printViewIcons($view, $tr)
	{
		$link = new Link();
		$link = $link->getAdminLink('AdminSellerProductDetail').'&amp;viewmarketplace_seller_product&amp;id='.$view;
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-search-plus"></i> &nbsp;View
		</a>
	</span>
</span>';
        return $html;

	}
	
	public function editCustomerlink($id_customer, $tr)
	{
		
		$link = new Link();
		$link = $link->getAdminLink('AdminCustomers').'&amp;viewcustomer&amp;id_customer='.$id_customer;
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-search-plus"></i> &nbsp;'.$id_customer.'
		</a>
	</span>
</span>';
        return $html;

	}
	
	public function printPPIcons($pp, $tr)
	{
		
		$link = new Link();
		$link = $link->getAdminLink('AdminProducts').'&amp;updateproduct&amp;id_product='.$pp;
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-edit"></i> &nbsp;'.$pp.'
		</a>
	</span>
</span>';
        return $html;

	}
	
	public function printShipping($shipping, $tr)
	{
		$carriers = Db::getInstance()->getValue("SELECT count(id_product) FROM `"._DB_PREFIX_."product_carrier` where id_product=".(int)$shipping,false);
        return ($carriers>0?'YES':'NO');

	}
	public function printOrders($orders, $tr)
	{
		$Orders = Db::getInstance()->getValue("SELECT count(id_order) FROM `"._DB_PREFIX_."order_detail` where product_id=".(int)$orders,false);
        return ($Orders>0?'YES':'NO');

	}
	public function printPLinkIcons($plink, $tr)
	{
		$link = new Link();
		$link = $link->getProductLink((int)$plink);
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'" target="_blank">
			<i class="icon-search-plus"></i> &nbsp;'.$plink.'
		</a>
	</span>
</span>';
        return $html;

	}
	
	
	public function printSellerIcons($seller_name, $tr)
	{
		$link = new Link();
		$link = $link->getAdminLink('AdminSellerInfoDetail').'&amp;updatemarketplace_seller_info&amp;id='.$tr['id_seller'];
		
		$html = '<span class="btn-group-action">
	<span class="btn-group">
		<a class="btn btn-default" href="'.$link.'">
			<i class="icon-search-plus"></i> &nbsp;'.$seller_name.'
		</a>
	</span>
</span>';
        return $html;

	}
	
		public function initToolbar() 
		{
			$obj_mp_cutomer = new MarketplaceCustomer();
			$all_customer_is_seller = $obj_mp_cutomer->findIsallCustomerSeller();
			
			if($all_customer_is_seller)
			{
				parent::initToolbar();
				$this->page_header_toolbar_btn['new'] = array(
					'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
					'desc' => $this->l('Add new product')
				);
			}
			unset($obj_mp_cutomer);
			unset($all_customer_is_seller);
		}

		public function postProcess()
		{
			
			if (!$this->loadObject(true))
				return;
			
			$this->addJqueryPlugin(array('fancybox','tablednd'));
			$this->addCSS(_MODULE_DIR_.'marketplace/css/add_product.css');	

			//tinymce
			$this->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
			if (version_compare(_PS_VERSION_, '1.6.0.11', '>'))
				$this->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
			else
				$this->addJS(_PS_JS_DIR_.'tinymce.inc.js');

			//For Category tree
			$this->addJS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js');
			$this->addCSS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css');
			$this->addJS(_MODULE_DIR_.'marketplace/views/js/categorytree/jquery.checkboxtree.js');
			$this->addCSS(_MODULE_DIR_.'marketplace/views/js/categorytree/wk.checkboxtree.css');
			
			if (Tools::isSubmit('statusmarketplace_seller_product')) {
				$this->active_seller_product();
			}
			
			if($this->display == 'view') 
			{	

				$this->context       = Context::getContext();
				$id_lang             = $this->context->employee->id_lang;
				$id                  = Tools::getValue('id');
				$this->context->smarty->assign('set','0');
				$id = Tools::getValue('id');
				$add_size =  Configuration::get('add-size');
				$add_color =  Configuration::get('add-color');
				$add_border_color =  Configuration::get('add-border-color');
				$add_font_family =  Configuration::get('add-font-family');
			
				$this->context->smarty->assign("add_size",$add_size);
				$this->context->smarty->assign("add_color",$add_color);
				$this->context->smarty->assign("add_border_color",$add_border_color);
				$this->context->smarty->assign("add_font_family",$add_font_family);
				$obj_marketplace_product = new SellerProductDetail();
				$pro_info = $obj_marketplace_product->getMarketPlaceProductInfo($id);
				
				$this->context->smarty->assign('pro_info',$pro_info);
				///Is product activate one time
				$is_product_onetime_activate = $obj_marketplace_product->getMarketPlaceShopProductDetailBYmspid($id);
				if($is_product_onetime_activate) {
					$this->context->smarty->assign('is_product_onetime_activate',1);
					$link = new Link();
					$id_product = $is_product_onetime_activate['id_product'];
					
					$product = new Product($id_product);
					$id_image_detail = $product->getImages($id_lang);
					
					$product_link_rewrite = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `". _DB_PREFIX_."product_lang` where id_product=".$id_product." and id_lang=".$id_lang);
					$name = $product_link_rewrite['link_rewrite'];
					if(!empty($id_image_detail)) {
						$id_image = array();
						$image_link = array();
						$is_cover = array();
						$position = array();
						foreach($id_image_detail as $id_image_info) {
							$id_image[] = $id_image_info['id_image'];
							$ids = $id_product.'-'.$id_image_info['id_image'];
							$image_link[] = $link->getImageLink($name,$ids);
							$is_cover[] = $id_image_info['cover'];
							$position[] = $id_image_info['position'];
						}
						$this->context->smarty->assign('is_image_found',1);
						$this->context->smarty->assign('id_image',$id_image);
						$this->context->smarty->assign('image_link',$image_link);
						$this->context->smarty->assign('is_cover',$is_cover);
						$this->context->smarty->assign('position',$position);
						$this->context->smarty->assign('id_product',$id_product);
						
					} else {
						$this->context->smarty->assign('is_image_found',0);
					}
					
				} else {
					$this->context->smarty->assign('is_product_onetime_activate',0);
				}
				
				$unactive_image = $obj_marketplace_product->unactiveImage($pro_info['id']);
				if($unactive_image) {
					$this->context->smarty->assign('is_unactive_image',1);
					$this->context->smarty->assign('unactive_image',$unactive_image);
					
				}else {
					$this->context->smarty->assign('is_unactive_image',0);
				}
			}
			parent::postProcess();	
		}
		
		public function renderForm() 
		{
			
			$add_size =  Configuration::get('add-size');
			$add_color =  Configuration::get('add-color');
			$add_border_color =  Configuration::get('add-border-color');
			$add_font_family =  Configuration::get('add-font-family');
		
			$this->context->smarty->assign("add_size",$add_size);
			$this->context->smarty->assign("add_color",$add_color);
			$this->context->smarty->assign("add_border_color",$add_border_color);
			$this->context->smarty->assign("add_font_family",$add_font_family);

			//tinymce setup
			$this->context->smarty->assign('path_css',_THEME_CSS_DIR_);
			$this->context->smarty->assign('ad',__PS_BASE_URI__.basename(_PS_ADMIN_DIR_));//__PS_BASE_URI__.basename(_PS_ADMIN_DIR_)
			$this->context->smarty->assign('autoload_rte',true);
            $this->context->smarty->assign('lang',true);
            $this->context->smarty->assign('iso', $this->context->language->iso_code);

			$link = new Link();
			$selfcontrollerlink = $link->getAdminLink('AdminSellerProductDetail');
			$this->context->smarty->assign('selfcontrollerlink',$selfcontrollerlink);

			$id_lang = $this->context->cookie->id_lang;
			$id = Tools::getValue('id');
			$obj_marketplace_product = new SellerProductDetail();
			$obj_marketplace_product_category = new SellerProductCategory();
			$root = Category::getRootCategory();
			if($this->display == 'add')
			{
				//Prepair Category Tree 
				$root = Category::getRootCategory();
				$category =  Db::getInstance()->ExecuteS("SELECT a.`id_category`,l.`name` from `"._DB_PREFIX_."category` a LEFT  JOIN `"._DB_PREFIX_."category_lang` l  ON (a.`id_category`=l.`id_category`) where a.id_parent=".$root->id." and l.id_lang=".$id_lang." and l.`id_shop`=1 order by a.`id_category`");
					
				$tree = "<ul id='tree1'>";
				$tree .= "<li><input type='checkbox' checked='checked' class='product_category' name='product_category[]' value='".$root->id."'><label>".$root->name."</label>";
				//$depth = 1;
				$exclude = array();
				array_push($exclude, 0);
				
				foreach($category as $cat) {
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
						$tree .= "<li><input type='checkbox' name='product_category[]' class='product_category' value='".$cat['id_category']."'><label>".$cat['name']."</label>";  
						
						array_push($exclude, $cat['id_category']);          
						$tree .= $obj_marketplace_product_category->buildChildCategoryRecursive($cat['id_category'],$id_lang);       
					 }
					 $tree .= "</ul>";
				}
				$this->context->smarty->assign("categoryTree",$tree);
				//Category tree close
	
				$customer_info =Db::getInstance()->executeS("SELECT cus.`id_customer`,cus.`email` FROM `"._DB_PREFIX_."customer` cus INNER JOIN `"._DB_PREFIX_."marketplace_customer` mcus ON ( cus.id_customer = mcus.id_customer )");
				$this->context->smarty->assign('set','1');

				if(empty($customer_info))
					$this->context->smarty->assign('customer_info',-1);
				
				else
					$this->context->smarty->assign('customer_info',$customer_info);
				
				
				$this->fields_form = array(
					'submit' => array(
					'title' => $this->l('Save'),
					'class' => 'button'
					)
				);
				
			} 
			else if($this->display == 'edit') 
			{
				
				$this->context->smarty->assign('set','0');
				$id = Tools::getValue('id');
				$pro_info = $obj_marketplace_product->getMarketPlaceProductInfo($id);
				$checked_product_cat = $obj_marketplace_product->getMarketPlaceProductCategories($id);
				$defaultcatid = $obj_marketplace_product_category->getMpDefaultCategory($id);
				$this->context->smarty->assign('pro_info',$pro_info);
				
				//Prepair Category Tree 
				
				$category =  Db::getInstance()->ExecuteS("SELECT a.`id_category`,l.`name` from `"._DB_PREFIX_."category` a LEFT  JOIN `"._DB_PREFIX_."category_lang` l  ON (a.`id_category`=l.`id_category`) where a.id_parent=".$root->id." and l.id_lang=".$id_lang." and l.`id_shop`=1 order by a.`id_category`");
					
				$tree = "<ul id='tree1'>";
				$tree .= "<li><input type='checkbox'";
				if($checked_product_cat){       					//For old products which have uploded
							foreach($checked_product_cat as $product_cat){
								if($product_cat['id_category'] == $root->id)
									$tree .= "checked";
							}
				}
				else{
					if($defaultcatid == $root->id)
						$tree .= "checked";
				}
				$tree .= " name='product_category[]' class='product_category' value='".$root->id."'><label>".$root->name."</label>";
				//$depth = 1;
				$exclude = array();
				array_push($exclude, 0);
				
				foreach($category as $cat) {
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
						if($checked_product_cat){        					//For old products which have uploded
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
						$tree .= $obj_marketplace_product_category->buildChildCategoryRecursive($cat['id_category'],$id_lang,$checked_product_cat);        
					 }
					 $tree .= "</ul>";
				}
				$this->context->smarty->assign("categoryTree",$tree);
				
				//Category tree close
				
				//Is product activate one time
				$is_product_onetime_activate = $obj_marketplace_product->getMarketPlaceShopProductDetailBYmspid($id);
				if($is_product_onetime_activate) {
					$this->context->smarty->assign('is_product_onetime_activate',1);
					$link = new Link();
					$id_product = $is_product_onetime_activate['id_product'];
					$product = new Product($id_product);
					$id_image_detail = $product->getImages($id_lang);
					
					$product_link_rewrite = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `". _DB_PREFIX_."product_lang` where id_product=".$id_product." and id_lang=".$id_lang);
					$name = $product_link_rewrite['link_rewrite'];
					if(!empty($id_image_detail)) {
						$id_image = array();
						$image_link = array();
						$is_cover = array();
						$position = array();
						foreach($id_image_detail as $id_image_info) {
							$id_image[] = $id_image_info['id_image'];
							$ids = $id_product.'-'.$id_image_info['id_image'];
							$image_link[] = $link->getImageLink($name,$ids);
							$is_cover[] = $id_image_info['cover'];
							$position[] = $id_image_info['position'];
						}
						$this->context->smarty->assign('is_image_found',1);
						$this->context->smarty->assign('id_image',$id_image);
						$this->context->smarty->assign('image_link',$image_link);
						$this->context->smarty->assign('is_cover',$is_cover);
						$this->context->smarty->assign('position',$position);
						$this->context->smarty->assign('id_product',$id_product);
						
					} else {
						$this->context->smarty->assign('is_image_found',0);
					}
					
				} else {
					$this->context->smarty->assign('is_product_onetime_activate',0);
				}
				
				$unactive_image = $obj_marketplace_product->unactiveImage($pro_info['id']);
				if($unactive_image) {
					$this->context->smarty->assign('is_unactive_image',1);
					$this->context->smarty->assign('unactive_image',$unactive_image);
					
				} else {
					$this->context->smarty->assign('is_unactive_image',0);
				}
				
				$this->fields_form = array(
					'legend' => array(
						'title' =>	$this->l('Edit Shop'),
						),
					
					'submit' => array(
						'title' => $this->l('Save'),
						'class' => 'button'
					)
				);
				
				
			} 
			else if($this->display == 'view') 
			{
				$this->context       = Context::getContext();
				$id_lang             = $this->context->employee->id_lang;
				$id                  = Tools::getValue('id');
				$this->context->smarty->assign('set','0');
				$id = Tools::getValue('id');
				
				$obj_marketplace_product = new SellerProductDetail();
				$pro_info = $obj_marketplace_product->getMarketPlaceProductInfo($id);
				
				$this->context->smarty->assign('pro_info',$pro_info);
				///Is product activate one time
				$is_product_onetime_activate = $obj_marketplace_product->getMarketPlaceShopProductDetailBYmspid($id);
				if($is_product_onetime_activate) {
					$this->context->smarty->assign('is_product_onetime_activate',1);
					$link = new Link();
					$id_product = $is_product_onetime_activate['id_product'];
					$product = new Product($id_product);
					$id_image_detail = $product->getImages($id_lang);
					
					$product_link_rewrite = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("select * from `". _DB_PREFIX_."product_lang` where id_product=".$id_product." and id_lang=".$id_lang);
					$name = $product_link_rewrite['link_rewrite'];
					if(!empty($id_image_detail)) {
						$id_image = array();
						$image_link = array();
						$is_cover = array();
						$position = array();
						foreach($id_image_detail as $id_image_info) {
							$id_image[] = $id_image_info['id_image'];
							$ids = $id_product.'-'.$id_image_info['id_image'];
							$image_link[] = $link->getImageLink($name,$ids);
							$is_cover[] = $id_image_info['cover'];
							$position[] = $id_image_info['position'];
						}
						$this->context->smarty->assign('is_image_found',1);
						$this->context->smarty->assign('id_image',$id_image);
						$this->context->smarty->assign('image_link',$image_link);
						$this->context->smarty->assign('is_cover',$is_cover);
						$this->context->smarty->assign('position',$position);
						$this->context->smarty->assign('id_product',$id_product);
						
					} else {
						$this->context->smarty->assign('is_image_found',0);
					}
					
				} else {
					$this->context->smarty->assign('is_product_onetime_activate',0);
				}
				
				$unactive_image = $obj_marketplace_product->unactiveImage($pro_info['id']);
				if($unactive_image) {
					$this->context->smarty->assign('is_unactive_image',1);
					$this->context->smarty->assign('unactive_image',$unactive_image);
					
				} else {
					$this->context->smarty->assign('is_unactive_image',0);
				}
			}
			return parent::renderForm();
		}
	
		
		public function processSave() 
		{
			//set==1 for add new
			//set == 0 for edit existing product
			//when produc has been added then by deafult its not active
			
			$is_proceess = Tools::getValue('set');
			
			$product_name = Tools::getValue('product_name');
			$product_price = Tools::getValue('product_price');
			$product_quantity = Tools::getValue('product_quantity');
			$product_description = Tools::getValue('product_description');
			$product_category = Tools::getValue('product_category');
			$short_description = Tools::getValue('short_description');
			
			if($product_name=='') {
				$this->errors[] = Tools::displayError('Product name is requried field.');
			} else {
				$is_valid_name = Validate::isGenericName($product_name);
				if(!$is_valid_name) {
					$this->errors[] = Tools::displayError($this->l('Product name must not have Invalid characters <>;=#{}'));
				}
			}
			if($product_price=='') {
				$this->errors[] = Tools::displayError('Product price is requried field.');
			} else {
				if(!is_numeric($product_price)) {
					$this->errors[] = Tools::displayError('Product price is should be numeric.');
				} else if($product_price<=0) {
					$this->errors[] = Tools::displayError('Product price is should be greater than 0.');
				}
					
			}
			if($product_quantity=='') {
				$this->errors[] = Tools::displayError('Product quantity  requried field.');
			} else {
				$product_quantity = (int)$product_quantity;
				if(!is_int($product_quantity)) {
					$this->errors[] = Tools::displayError('Product quantity  should be integer.'.$product_quantity);
				} else if($product_quantity<=0) {
					$this->errors[] = Tools::displayError('Product quantity  should be greater than 0.');
				}
			}
			
			if($product_category == false){
				$this->errors[] = Tools::displayError('Please select atleast one category.');
			}
			
			if($is_proceess==1) {
				if (empty($this->errors)) {
					$customer_id = Tools::getValue('shop_customer');
					$approve_type = Configuration::getGlobalValue('PRODUCT_APPROVE');
					$obj_seller_product = new SellerProductDetail();
					$obj_mp_shop = new MarketplaceShop();
					$marketplace_shop = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($customer_id);
					
					$id_shop  = $marketplace_shop['id'];
					$id_seller = MarketplaceShop::findMpSellerIdByShopId($id_shop);
					
					$obj_seller_product->id_seller = $id_seller;
					$obj_seller_product->price = $product_price;
					$obj_seller_product->quantity = $product_quantity;
					$obj_seller_product->product_name = $product_name;
					$obj_seller_product->description = $product_description;
					$obj_seller_product->short_description = $short_description;
					$obj_seller_product->id_category = $product_category[0];
					$obj_seller_product->ps_id_shop = $this->context->shop->id;
					$obj_seller_product->id_shop = $id_shop;
					if($approve_type == 'admin')
					{
						$active = false;
						$obj_seller_product->active = 0;
					}
					else
					{
						$active = true;
						$obj_seller_product->active = 1;
					}
					$obj_seller_product->save();					 
					
					$seller_product_id    = $obj_seller_product->id;
					
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
					
					$address    = "../modules/marketplace/img/product_img/";
					
					if(isset($_FILES['product_image'])) {
						$length = 6;
						$characters= "0123456789abcdefghijklmnopqrstuvwxyz";
						$u_id= "";
						
						for ($p=0;$p<$length;$p++) {
							$u_id= $u_id.$characters[mt_rand(0, Tools::strlen($characters))];
						}
						if($_FILES['product_image']['size']>0) {
							Db::getInstance()->insert(
												'marketplace_product_image', array(
												'seller_product_id' => (int) $seller_product_id,
												'seller_product_image_id' => pSQL($u_id)
										));
							$image_name = $u_id . ".jpg";
							
							move_uploaded_file($_FILES["product_image"]["tmp_name"],$address.$image_name);
						}
						
					}
					if(isset($_FILES['images'])) {
						$other_images  = $_FILES["images"]['tmp_name'];
						$count = count($other_images);
						
					} else {
						$count = 0;
					}		
					
					for ($i = 0; $i < $count; $i++) {
						$length     = 6;
						$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
						$u_other_id = "";
						for ($p = 0; $p < $length; $p++) {
							$u_other_id .= $characters[mt_rand(0, Tools::strlen($characters))];
						}
						
						Db::getInstance()->insert('marketplace_product_image', array(
							'seller_product_id' => (int) $seller_product_id,
							'seller_product_image_id' => pSQL($u_other_id)
						));
						$image_name = $u_other_id . ".jpg";
						$address    = "../modules/marketplace/img/product_img/";
						move_uploaded_file($other_images[$i], $address . $image_name);
					}
					//For Default Approval Setting
					if($seller_product_id)
					{
						// if active, then entry of a product in ps_product table...
						if($active)
						{
							$obj_seller_product = new SellerProductDetail();
							$image_dir = "../modules/marketplace/img/product_img";
							// creating ps_product when admin setting is default
							$ps_product_id = $obj_seller_product->createPsProductByMarketplaceProduct($seller_product_id,$image_dir, $active);
							if($ps_product_id)
							{
								// mapping of ps_product and mp_product id
								$mps_product_obj = new MarketplaceShopProduct();
								$mps_product_obj->id_shop = $id_shop;
								$mps_product_obj->marketplace_seller_id_product = $seller_product_id;
								$mps_product_obj->id_product = $ps_product_id;
								$mps_product_obj->add();
							}
						}
								
					}
					Hook::exec('actionAddproductExtrafield', array('marketplace_product_id' => $seller_product_id));
					Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
				} else {
					$this->display = 'add';
				}
			}
			else {
				if (empty($this->errors)) {
					$id = Tools::getValue('market_place_product_id');
					$obj_seller_product = new SellerProductDetail($id);
					
					$obj_seller_product->price = $product_price;
					$obj_seller_product->quantity = $product_quantity;
					$obj_seller_product->product_name = $product_name;
					$obj_seller_product->description = $product_description;
					$obj_seller_product->short_description = $short_description;
					$obj_seller_product->id_category = $product_category[0];
					//save category
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
					
					if($is_active == 1){
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
							$address = "../modules/marketplace/img/product_img/";
							
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
						$image_dir = '../modules/marketplace/img/product_img';
						
						$obj_seller_product->updatePsProductByMarketplaceProduct($id, $image_dir,1,$main_product_id);
					}
					else if($is_active == 0) {
							
						if(isset($_FILES['product_image']) && $_FILES['product_image']["tmp_name"]!='') {
							$length = 6;
							$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
							$u_id = ""; 
							

							for ($p =0;$p<$length;$p++)  {
									$u_id .= $characters[mt_rand(0, Tools::strlen($characters))];
							}

							$image_name =$u_id.".jpg";
							$address = "../modules/marketplace/img/product_img/";
							
							Db::getInstance()->insert('marketplace_product_image', array(
																		'seller_product_id' =>(int)$id,
																		'seller_product_image_id' =>pSQL($u_id)
																));
																
							move_uploaded_file($_FILES["product_image"]["tmp_name"],$address.$image_name);
						}
					}
					Hook::exec('actionUpdateproductExtrafield', array('marketplace_product_id' =>Tools::getValue('market_place_product_id')));
					Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
				} else {
					$this->display = 'edit';
				}
			}
		}
		
		public function active_seller_product($mp_product_id=false,$come_from=false) 
		{
			if(!$mp_product_id)
				$mp_product_id = Tools::getValue('id');
			
			SellerProductDetail::toggle_seller_product($mp_product_id);

			if(!$come_from)
				Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
		}

		public function processDelete($id=true) 
		{
			if($id==true)
			{
				$marketplace_seller_product_id = (int)Tools::getValue('id');
			}
			else
			{
				$marketplace_seller_product_id = $id;
			}
			$delete_row_from_marketplace_seller_product = SellerProductDetail::delete_seller_product($marketplace_seller_product_id);
			if($delete_row_from_marketplace_seller_product)
				$redirect = self::$currentIndex.'&conf=1&token='.$this->token;
				
			$this->redirect_after = $redirect;
		}

		public function ajaxProcessDeleteUnactiveImage()
		{
			$id_image = Tools::getValue('id_image');
			$img_name = Tools::getValue('img_name');

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

		public function ajaxProcessDeleteActiveImage()
		{
			$id_image = Tools::getValue('id_image');
			$id_product = Tools::getValue('id_pro');
			$image = new Image($id_image);
			$status = $image->delete();
			Product::cleanPositions($id_product);
			Db::getInstance()->delete('image','id_image='.$id_image.' and id_product='.$id_product);
			if($status)
				echo 1;
			else
				echo 0;
		}

		public function ajaxProcessChangeImageCover()
		{
			$id_image = Tools::getValue('id_image');
			$id_product = Tools::getValue('id_pro');
			if(isset($id_image))
			{
				$product = new Product($id_product);
				$product->setCoverWs($id_image);
				echo 1;
			} 
			else
				echo 0;
		}



		protected function processBulkEnableSelection()
		{
			return $this->processBulkStatusSelection(1);
		}
		
		protected function processBulkDisableSelection()
		{
			 return $this->processBulkStatusSelection(0);
		}

		protected function processBulkStatusSelection($status)
		{
			$result = true;
				
			
			if($status==1) {
				if (is_array($this->boxes) && !empty($this->boxes))
				{
					foreach ($this->boxes as $id)
					{
						$obj_seller_product = new SellerProductDetail($id);
						if($obj_seller_product->active==0)
							$this->active_seller_product($id,true);

					}
				}
			} else if($status==0){
				
				if (is_array($this->boxes) && !empty($this->boxes))
				{
					foreach ($this->boxes as $id)
					{
						$obj_seller_product = new SellerProductDetail($id);
						if($obj_seller_product->active==1)
							$this->active_seller_product($id,true);

					}
				}
			}

			return $result;
		}

}

?>