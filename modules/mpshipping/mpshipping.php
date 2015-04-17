<?php
	if (!defined('_PS_VERSION_'))
		exit;
	include_once 'classes/Mpshippinginclude.php';
	include_once dirname(__FILE__).'/../marketplace/classes/MarketplaceClassInclude.php';
	
	class mpshipping extends Module 
	{
		const INSTALL_SQL_FILE = 'install.sql';
		
		public function __construct() 
		{
			$this->name = 'mpshipping';
			$this->tab = 'front_office_features';
			$this->version = '0.3';
			$this->author = 'webkul';
			$this->need_instance = 1;
			$this->dependencies = array('marketplace');
			parent::__construct();
			$this->displayName = $this->l('Shipping by country');
			$this->description = $this->l('Provide seller to create own shipping method');
		}
		
		public function callAssociateModuleToShop() 
		{
			$module_id = Module::getModuleIdByName($this->name);
			Configuration::updateGlobalValue('MPSHIPPING_MODULE_ID',$module_id);
			return true;
		}
		
		public function callInstallTab() 
		{
			$this->installTab('AdminMpsellershipping','Mp Seller shipping','AdminMarketplaceManagement');
			return true;
		}
		
		public function installTab($class_name,$tab_name,$tab_parent_name=false)
		{
			//creating tab in admin within marketplace tab
			$tab = new Tab();
			$tab->active = 1;
			$tab->class_name = $class_name;
			$tab->name = array();
			foreach (Language::getLanguages(true) as $lang)
				$tab->name[$lang['id_lang']] = $tab_name;

			if($tab_parent_name) 
			{
				$tab->id_parent = (int)Tab::getIdFromClassName($tab_parent_name);
			} 
			else 
			{
				$tab->id_parent = 0;
			}
			
			$tab->module = $this->name;
			return $tab->add();
		}
		
		public function hookDisplayMpmyaccountmenuhook() 
		{
			$link = new Link();
			$id_customer = $this->context->cookie->id_customer;

			//find customer is marketplace seller or not
			$mp_customer = new MarketplaceCustomer();
			$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);

			//mpmenu 0 for myaccount page
			$this->context->smarty->assign('mpmenu',0);

			//$mp_customer_info have false value if customer is not marketplace seller
			if($mp_customer_info) 
			{
				$is_seller = $mp_customer_info['is_seller'];
				if($is_seller==1) 
				{
					$sellershippinglist = $link->getModuleLink('mpshipping','sellershippinglist');
					$this->context->smarty->assign('sellershippinglist',$sellershippinglist);
					return $this->display(__FILE__, 'mpshipping_link.tpl');
				}
			}
		}
		
		public function hookDisplayMpmenuhookext() 
		{
			$link = new Link();
			$id_customer = $this->context->cookie->id_customer;

			//find customer is marketplace seller or not
			$mp_customer = new MarketplaceCustomer();
			$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);

			//mpmenu 1 for marketplace menu page
			$this->context->smarty->assign('mpmenu',1);
			if($mp_customer_info) 
			{
				$is_seller = $mp_customer_info['is_seller'];
				if($is_seller==1) {
					$sellershippinglist = $link->getModuleLink('mpshipping','sellershippinglist');
					$this->context->smarty->assign('sellershippinglist',$sellershippinglist);
					return $this->display(__FILE__, 'mpshipping_link.tpl');
				}
			}
		}

		//this function display available shipping method on add product page
		
		public function hookDisplayMpaddproductfooterhook() 
		{
			if(Tools::getValue('shop')) 
			{
				$mp_id_shop = Tools::getValue('shop');

				//get all shipping method created by seller and which was activated by admin
				$obj_mp_shipping_method = new Mpshippingmethod();
				$mp_shipping_data = $obj_mp_shipping_method->getMpShippingMethods($mp_id_shop);

				$this->context->smarty->assign('mp_shipping_data',$mp_shipping_data);
				$this->context->smarty->assign('height','0.00');
				$this->context->smarty->assign('width','0.00');
				$this->context->smarty->assign('depth','0.00');
				$this->context->smarty->assign('weight','0.00');
				$this->context->smarty->assign('mp_shipping_data',$mp_shipping_data);
				return $this->display(__FILE__, 'addproduct_shipping.tpl');
			}
		}
		
		//this function display available shipping method on add product page

		public function hookDisplayMpupdateproductfooterhook() 
		{
			$mp_product_id = Tools::getValue('id');
			$obj_mp_seller_product_detail = new SellerProductDetail($mp_product_id);
			$mp_id_shop = $obj_mp_seller_product_detail->id_shop;

			//get all shipping method created by seller and which was activated by admin
			$obj_mp_shipping_method = new Mpshippingmethod();
			$mp_shipping_data = $obj_mp_shipping_method->getMpShippingMethods($mp_id_shop);

			

			$obj_mpshipping_pro = new Mpshippingproduct();

			//get product weight,height,depth related information
			$product_weight_desc = $obj_mpshipping_pro->findWeightInfoByMpProID($mp_product_id);

			$this->context->smarty->assign('height',$product_weight_desc[0]['height']);
			$this->context->smarty->assign('width',$product_weight_desc[0]['width']);
			$this->context->smarty->assign('depth',$product_weight_desc[0]['depth']);
			$this->context->smarty->assign('weight',$product_weight_desc[0]['weight']);
			
			//if seller have own whipping method
			if($mp_shipping_data)
			{
				$this->context->smarty->assign('mp_shipping_data',$mp_shipping_data);
				
				//check is any shipping method assigned on seller product or not
				$obj_mp_shipping_product_map = new Mpshippingproductmap();
				$mp_shipping_product_map_details = $obj_mp_shipping_product_map->getMpShippingProductMapDetails($mp_product_id);				
				if($mp_shipping_product_map_details)
				{
					$this->context->smarty->assign('mp_shipping_product_map_details',$mp_shipping_product_map_details);
				 }

				 //check is mpvirtualproduct module install or not
				 //if it install then check is product is virtual product or any simple product
				 //if product is virtual product then we can not shown any shipping method
				 $ismpinstall = Module::isInstalled('mpvirtualproduct');
				 if($ismpinstall)
				 {
				 	include_once dirname(__FILE__).'/../mpvirtualproduct/classes/MarketplaceVirtualProduct.php';
				 	$obj_mvp = new MarketplaceVirtualProduct();
					$is_virtual_product = $obj_mvp->isMpProductIsVirtualProduct($mp_product_id);
					if(empty($is_virtual_product))
					{
				 		return $this->display(__FILE__, 'addproduct_shipping.tpl');
				 	}
			   	}
			   	else
			   	{
			   		return $this->display(__FILE__, 'addproduct_shipping.tpl');
			   	}
			}
		}
		
		//checking validation before adding product if not validated product has been not added
		public function hookActionBeforeAddproduct() 
		{
			$width = Tools::getValue('width');
			$height = Tools::getValue('height');
			$depth = Tools::getValue('depth');
			$weight = Tools::getValue('weight');
			$customer_id  = $this->context->cookie->id_customer;
			$obj_mpshop = new MarketplaceShop();
			$link = new Link();

			//find marketplace shop information by customer id
			$marketplace_shop = $obj_mpshop->getMarketPlaceShopInfoByCustomerId($customer_id);
      		$mp_id_shop = $marketplace_shop['id'];

			if($width != '')
			{
				$is_width_float = Validate::isFloat($width);
				if(!$is_width_float)
				{
					$addproduct_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>'width_error'));
					Tools::redirect($addproduct_link);
				}
			}
			if($height != '')
			{
				$is_height_float = Validate::isFloat($height);
				if(!$is_height_float)
				{
					$addproduct_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>'height_error'));
					Tools::redirect($addproduct_link);
				}
			}
			if($depth != '')
			{
				$is_depth_float = Validate::isFloat($depth);
				if(!$is_depth_float)
				{
					$addproduct_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>'depth_error'));
					Tools::redirect($addproduct_link);
				}
			}
			if($weight != '')
			{
				$is_weight_float = Validate::isFloat($weight);
				if(!$is_weight_float)
				{
					$addproduct_link = $link->getModuleLink('marketplace','addproduct',array('shop'=>$mp_id_shop,'is_main_er'=>'weight_error'));
					Tools::redirect($addproduct_link);
				}
			}

		}
		
		//add shipping method on seller product.
		public function hookActionAddproductExtrafield($params)
		{
			//get marketplace product data
			$marketplace_product_id = $params['marketplace_product_id'];
			$obj_mp_seller_product_detail = new SellerProductDetail($marketplace_product_id);

			//check is product is activated or not this condtion comes when admin set auto approve product
			$obj_mpshop_pro = new MarketplaceShopProduct();
			$product_detail = $obj_mpshop_pro->findMainProductIdByMppId($marketplace_product_id);
			$ps_product_id = $product_detail['id_product'];


			$mp_id_shop = $obj_mp_seller_product_detail->id_shop;

			//check is product choose as virtual product
			$is_virtual_product = Tools::getValue('mp_is_virtual');


			$obj_mp_seller_product_detail = new SellerProductDetail($marketplace_product_id);

			if(empty($is_virtual_product))
			{
				//check is carrier choose by seller or not or seller have is own shipping method or not
				$mp_shipping_carrier = Tools::getValue('carriers');
				
				if(isset($mp_shipping_carrier) && !empty($mp_shipping_carrier))
				{
					$carriers = array();
					foreach($mp_shipping_carrier as $mp_shipping_carrier)
					{
						$mp_shipping_id = $mp_shipping_carrier;
						
						//find main carrier id by mp_shipping_id
						$obj_mp_shipping_map = new Mpshippingmap();
						$mp_shipping_carrier_id = $obj_mp_shipping_map->getCarrierId($mp_shipping_id);

						//get all information about main carrier
						$obj_carrier = new Carrier($mp_shipping_carrier_id);
						
						//add shipping method to product and save a map in to table
						$obj_mp_shipping_product_map = new Mpshippingproductmap();
						$obj_mp_shipping_product_map->mp_shipping_id = $mp_shipping_id;
						$obj_mp_shipping_product_map->ps_id_carriers = $obj_carrier->id_reference;
						$obj_mp_shipping_product_map->mp_product_id = $marketplace_product_id;
						$obj_mp_shipping_product_map->add();
						
						//$product_status = $obj_mp_seller_product_detail->getProductStatus($marketplace_product_id);
						//check is product active or not if active then we need to update main product too
						$product_status = $obj_mp_seller_product_detail->active;
						if($product_status == 1)
						{
							$obj_mp_shipping_method = new Mpshippingmethod();

							//get prestashop shop id its help full in case of multistore
							$ps_shop_id = $obj_mp_shipping_method->getMpShippingPsShopId($mp_shipping_id);

							$carriers[] = $obj_carrier->id_reference;

							$obj_product = new Product($ps_product_id);

							//set carriers on main product that means prestashop product which was seller product
							$obj_product->setCarriers($carriers);
						}
					}

					//setting width,height,depth,weight for product

					$width = (float)Tools::getValue('width');
					$height = (float)Tools::getValue('height');
					$depth = (float)Tools::getValue('depth');
					$weight = (float)Tools::getValue('weight');
					$obj_mpshipping_pro = new Mpshippingproduct();
					$obj_mpshipping_pro->width = $width;
					$obj_mpshipping_pro->height = $height;
					$obj_mpshipping_pro->depth = $depth;
					$obj_mpshipping_pro->weight = $weight;
					$obj_mpshipping_pro->mp_product_id = $marketplace_product_id;
					$obj_mpshipping_pro->save();


					$product_status = $obj_mp_seller_product_detail->active;
					if($product_status == 1)
					{
						//set width,height,depth,weight for main product
						$obj_product = new Product($ps_product_id);
						$obj_product->width = $width;
						$obj_product->height = $height;
						$obj_product->depth = $depth;
						$obj_product->weight = $weight;
						$obj_product->save();
					}

				}
				else
				{
					//check seller not choose any shipping method but they have any own shipping method or not
					$obj_mp_shipping_method = new Mpshippingmethod();
					$mp_shipping_data = $obj_mp_shipping_method->getMpShippingMethods($mp_id_shop);


					if($mp_shipping_data) 
					{
						//if seller have own shipping method and which was activated by admin and they are not choose any shipping method at th time of creating product then automatically first active shipping method assigned to that product.

						$mp_shipping_id = $mp_shipping_data[0]['id'];
						$obj_mp_shipping_map = new Mpshippingmap();
						$mp_shipping_carrier_id = $obj_mp_shipping_map->getCarrierId($mp_shipping_id);
						$obj_carrier = new Carrier($mp_shipping_carrier_id);
						$obj_mp_shipping_product_map = new Mpshippingproductmap();
						$obj_mp_shipping_product_map->mp_shipping_id = $mp_shipping_id;
						$obj_mp_shipping_product_map->ps_id_carriers = $obj_carrier->id_reference;
						$obj_mp_shipping_product_map->mp_product_id = $marketplace_product_id;
						$obj_mp_shipping_product_map->add();

						$obj_mp_seller_product_detail = new SellerProductDetail($marketplace_product_id);
							//$product_status = $obj_mp_seller_product_detail->getProductStatus($marketplace_product_id);
						
						$width = (float)Tools::getValue('width');
						$height = (float)Tools::getValue('height');
						$depth = (float)Tools::getValue('depth');
						$weight = (float)Tools::getValue('weight');
						$obj_mpshipping_pro = new Mpshippingproduct();
						$obj_mpshipping_pro->width = $width;
						$obj_mpshipping_pro->height = $height;
						$obj_mpshipping_pro->depth = $depth;
						$obj_mpshipping_pro->weight = $weight;
						$obj_mpshipping_pro->mp_product_id = $marketplace_product_id;
						$obj_mpshipping_pro->save();

						$product_status = $obj_mp_seller_product_detail->active;
						if($product_status == 1)
						{
							$ps_shop_id = $obj_mp_shipping_method->getMpShippingPsShopId($mp_shipping_id);
							$carriers[] = $obj_carrier->id_reference;
						
							$obj_product = new Product($ps_product_id);
							$obj_product->width = $width;
							$obj_product->height = $height;
							$obj_product->depth = $depth;
							$obj_product->weight = $weight;
							$obj_product->save();
							$obj_product->setCarriers($carriers);
						}
						
					}

				}
			}
		}
		
		//run at the time when seller update product
		public function hookActionUpdateproductExtrafield($params) 
		{
			$marketplace_product_id = $params['marketplace_product_id'];
			$obj_mpshop_pro = new MarketplaceShopProduct();
			$product_detail = $obj_mpshop_pro->findMainProductIdByMppId($marketplace_product_id);
			$ps_product_id = $product_detail['id_product'];
			$mp_shipping_carrier = Tools::getValue('carriers');

			$obj_mp_seller_product_detail = new SellerProductDetail($marketplace_product_id);			

			//check is carrier choose by seller or not or seller have is own shipping method or not

			if(isset($mp_shipping_carrier) && !empty($mp_shipping_carrier))
			{
				//if seller choose any shipping method then we need to delete old map
				$obj_mp_shipping_product_map = new Mpshippingproductmap();
				$obj_mp_shipping_product_map->deleteMpShippingProductMapDetails($marketplace_product_id);

				//then start loop for all shipping method choose by seller
				foreach($mp_shipping_carrier as $mp_shipping_carrier)
				{
					$mp_shipping_id = $mp_shipping_carrier;

					//get main carrier id by mp_shipping_id
					$obj_mp_shipping_map = new Mpshippingmap();
					$mp_shipping_carrier_id = $obj_mp_shipping_map->getCarrierId($mp_shipping_id);

					//create object for main carrier so that we got all information related to that carrier
					$obj_carrier = new Carrier($mp_shipping_carrier_id);
					
					$obj_mp_shipping_product_map->mp_product_id = $marketplace_product_id;
					$obj_mp_shipping_product_map->mp_shipping_id = $mp_shipping_id;
					$obj_mp_shipping_product_map->ps_id_carriers = $obj_carrier->id_reference;
					$obj_mp_shipping_product_map->add();

					$obj_mp_shipping_method = new Mpshippingmethod();
					$ps_shop_id = $obj_mp_shipping_method->getMpShippingPsShopId($mp_shipping_id);
					$carriers[] = $obj_carrier->id_reference;
					$product_status = $obj_mp_seller_product_detail->active;

					//if product is active then we set carrier on main product
					if($product_status == 1)
					{
						$obj_product = new Product($ps_product_id);
						$obj_product->setCarriers($carriers);
					}
			
				}

				//update weight,height detail for marketplace product in marketplace table
				$obj_mpshipping_pro = new Mpshippingproduct();
				$product_weight_desc = $obj_mpshipping_pro->findWeightInfoByMpProID($marketplace_product_id);
				$product_weight_desc_id = $product_weight_desc[0]['id'];

				$width = (float)Tools::getValue('width');
				$height = (float)Tools::getValue('height');
				$depth = (float)Tools::getValue('depth');
				$weight = (float)Tools::getValue('weight');
				$obj_mpshipping_pro_det = new Mpshippingproduct($product_weight_desc_id);
				$obj_mpshipping_pro_det->width = $width;
				$obj_mpshipping_pro_det->height = $height;
				$obj_mpshipping_pro_det->depth = $depth;
				$obj_mpshipping_pro_det->weight = $weight;
				$obj_mpshipping_pro_det->mp_product_id = $marketplace_product_id;
				$obj_mpshipping_pro_det->save();

				//update weight,height detail for marketplace product in main table

				$product_status = $obj_mp_seller_product_detail->active;
				if($product_status == 1)
				{
					$obj_product = new Product($ps_product_id);
					$obj_product->width = $width;
					$obj_product->height = $height;
					$obj_product->depth = $depth;
					$obj_product->weight = $weight;
					$obj_product->save();
				}
			}
		}
		
		//display validation error
		public function hookDisplayMpaddproductheaderhook($params) 
		{
			return $this->display(__FILE__, 'validate_shipping_variable.tpl');

		}
		

		//this code run when product has been activated by admin in backend
		public function hookActionToogleProductStatus($params) 
		{
		
			$mp_product_id = Tools::getValue('id');
			
			$ps_product_id = $params['main_product_id'];
			
			$obj_mp_shipping_product_map = new Mpshippingproductmap();
			$mp_shipping_product_map_details = $obj_mp_shipping_product_map->getMpShippingProductMapDetails($mp_product_id);
			if(!empty($mp_shipping_product_map_details))
			{
				// $carriers = array();
				foreach($mp_shipping_product_map_details as $mp_shipping_product_map_details)
				{
					$mp_shipping_id = $mp_shipping_product_map_details['mp_shipping_id'];
					$mp_carrier_id = $mp_shipping_product_map_details['ps_id_carriers'];
					$obj_mp_shipping_method = new Mpshippingmethod();
					$ps_shop_id = $obj_mp_shipping_method->getMpShippingPsShopId($mp_shipping_id);
					
					//$obj_product->id = $mp_product_id;
					//$obj_product->id_shop = $ps_shop_id;
					
					$carriers[] = $mp_carrier_id;
							
				}
				$obj_product = new Product($ps_product_id);							
				$obj_product->setCarriers($carriers);

				$obj_mpshipping_pro = new Mpshippingproduct();
				$product_weight_desc = $obj_mpshipping_pro->findWeightInfoByMpProID($mp_product_id);

				$obj_product->width = $product_weight_desc[0]['width'];
				$obj_product->height = $product_weight_desc[0]['height'];
				$obj_product->depth = $product_weight_desc[0]['depth'];
				$obj_product->weight = $product_weight_desc[0]['weight'];
				$obj_product->save();
			}
		}

		//this funtion assign admin carrier to his/her product(main product) which was not seller product
		public function assignCarriersToMainProduct($id_product)
		{
			$id_lang = $this->context->language->id;
			$obj_shipmap = new Mpshippingproductmap();
			$obj_carr = new Carrier();

			$carr_detials_final = $obj_shipmap->getAllPrestaCarriers();

			if ($carr_detials_final)
			{
				$carr_ref = array();
				foreach ($carr_detials_final as $carr)
					if (!$obj_shipmap->checkMpCarriers($carr['id_carrier']))
						$carr_ref[] = $carr['id_reference'];

				if (!$obj_shipmap->checkMpProduct($id_product))
					$obj_shipmap->setProductCarrier($id_product, $carr_ref);
			}
		}

		public function getContent()
		{
			$link = new Link();
			$smarty_vars = array('this_path' => $this->_path,
								 'admin_mpshipping_url' => $link->getAdminLink('AdminMpsellershipping'));

			$this->context->smarty->assign($smarty_vars);
			return $this->display(__FILE__, './views/templates/admin/admin.tpl');
		}

		public function hookActionProductSave($params)
		{
			$id_product = $params['id_product'];
			$this->assignCarriersToMainProduct($id_product);
		}

		//when admin update any carrier then we need to update our mapping table and as well as product map too
		//this function has been cal at that time
		public function hookActionCarrierUpdate($params)
		{
			$id_reference = $params['carrier']->id_reference;
			$obj_shipmap = new Mpshippingproductmap();
			$id_lang = $this->context->language->id;
			$start = 0;
			$limit = 0;
			$order_by = 'id_product';
			$order_way = 'ASC';

			$ps_prod_info = Product::getProducts($id_lang, $start, $limit, $order_by, $order_way, false, true);

			$carr_detials = $obj_shipmap->getAllPrestaCarriers();

			if ($carr_detials)
			{

				$carr_ref = array();
				foreach ($carr_detials as $carr)
					if (!$obj_shipmap->checkMpCarriers($carr['id_carrier']))
						$carr_ref[] = $carr['id_reference'];

				if (!empty($carr_ref))
					array_merge($carr_ref, $id_reference);

				foreach ($ps_prod_info as $product)
				{
					if (!$obj_shipmap->checkMpCarriers($params['id_carrier']))
					{
						if (!$obj_shipmap->checkMpProduct($product['id_product']))
						{
							$obj_prod = new Product($product['id_product']);
							$obj_prod->setCarriers($carr_ref);
						}
					}
				}
			}
		}
		
		public function hookDisplayMpproductdetailheaderhook($params) 
		{
			$link = new Link();
			$obj_mp_shipping_method = new Mpshippingmethod();
			$mp_id_shop = Tools::getValue('shop');
			$id_customer = $this->context->cookie->id_customer;
			$mp_customer = new MarketplaceCustomer();
			$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);
			$mp_id_seller = $mp_customer_info['marketplace_seller_id'];
			$mp_shipping_data = $obj_mp_shipping_method->getMpShippingMethods($mp_id_shop);
			$ajax_link = $link->getModuleLink('mpshipping','assignshippingforall');
			if(!empty($mp_shipping_data)){
				$this->context->smarty->assign('shipping_method',$mp_shipping_data);
				$this->context->smarty->assign('ajax_link',$ajax_link);
				$this->context->smarty->assign('mp_id_seller',$mp_id_seller);
				return $this->display(__FILE__, 'assign_shipping_method.tpl');
			}

		}
		
		public function install()
		{
			$ismpinstall = Module::isInstalled('marketplace');
			 if($ismpinstall) {
				if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
						return (false);
					else if (!$sql = Tools::file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
						return (false);
					$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
					$sql = preg_split("/;\s*[\r\n]+/", $sql);
					foreach ($sql AS $query)
						if($query)
							if(!Db::getInstance()->execute(trim($query)))
								return false;
				if (!parent::install()
					|| !$this->callInstallTab()
					|| !$this->registerHook('displayMpmyaccountmenuhook')
					|| !$this->registerHook('displayMpmenuhookext')				
					|| !$this->registerHook('displayMpaddproductfooterhook') 
					|| !$this->registerHook('displayMpupdateproductfooterhook') 
					|| !$this->registerHook('actionAddproductExtrafield')
					|| !$this->registerHook('actionUpdateproductExtrafield')
					|| !$this->registerHook('actionBeforeAddproduct')
					|| !$this->registerHook('actionBeforeUpdateproduct')
					|| !$this->registerHook('displayMpaddproductheaderhook')
					|| !$this->registerHook('displayMpupdateproductheaderhook')
					|| !$this->registerHook('actionToogleProductStatus')
					|| !$this->registerHook('actionProductSave')
					|| !$this->registerHook('actionProductUpdate')
					|| !$this->registerHook('actionCarrierUpdate')
					|| !$this->registerHook('actionCarrierProcess')
					|| !$this->registerHook('displayMpproductdetailheaderhook')
					)
					return false;
				else {
					if(!$this->callAssociateModuleToShop()) {
						return false;
					} else {
						return true;	
					}
				}
			} else {
				$this->errors[] = Tools::displayError($this->l('Marketplace Module Not install.'));
				return false;
			}
		}

		public function callUninstallTab() 
		{
			$this->uninstallTab('AdminMpsellershipping');
			return true;
		}
		public function uninstallTab($class_name)
		{
			$id_tab = (int)Tab::getIdFromClassName($class_name);
			if ($id_tab)
			{
				$tab = new Tab($id_tab);
				return $tab->delete();
			}
			else
				return false;
		}

		public function dropTable()
	    {
	        $table_name = array('mp_shipping_method', 'mp_shipping_delivery',
	                            'mp_range_price', 'mp_range_weight',
	                            'mp_shipping_impact', 'mp_shipping_map',
	                            'mp_shipping_product_map', 'mp_shipping_cart',
	                            'mp_shipping_product');

	        foreach($table_name as $name)
	            if (!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.$name))
	                return false;

	        return true;
	    }

		public function deleteOverrideFile()
	    {
	        $override_cart_file = _PS_ROOT_DIR_.'/override/classes/Cart.php';
	        $override_paymentmodule_file = _PS_ROOT_DIR_.'/override/classes/PaymentModule.php';
	        $responce = @unlink($override_cart_file);
	        $responce = @unlink($override_paymentmodule_file);

	        $override_classindex_file = _PS_ROOT_DIR_.'/cache/class_index.php';
	        if (file_exists($override_classindex_file))
	        	$responce = @unlink($override_classindex_file);
	        
	        return $responce;
	    }

		public function uninstall()
		{
			if (!parent::uninstall()
				|| !$this->dropTable()
				|| !$this->deleteOverrideFile()
				|| !$this->callUninstallTab()) 
				return false; 

			return true;

		}
	}
?>