<?php
include_once dirname(__FILE__).'/../../classes/Mpshippinginclude.php';

class AdminMpsellershippingController extends ModuleAdminController
{
	public function __construct()
	{
		$this->table = 'mp_shipping_method';
		$this->className = 'Mpshippingmethod';
		$this->context     = Context::getContext();
		$this->bootstrap = true;
		
		$this->fields_list = array(
				'id' => array(
					'title' => $this->l('Id') ,
					'align' => 'center',
					'class' => 'fixed-width-xs'
				),
				'mp_shipping_name' => array(
					'title' => $this->l('Shipping Name') ,
					'align' => 'center'
				),	
				
				'transit_delay' => array(
					'title' => $this->l('Transit Delay') ,
					'align' => 'center'
				),
				'seller_name' => array(
					'title' => $this->l('Seller Name') ,
					'align' => 'center'
				),	
				'shop_name' => array(
					'title' => $this->l('Shop Name') ,
					'align' => 'center'
				),
				'active' => array(
					'title' => $this->l('Status'),
					'active' => 'status',
					'align' => 'center',
					'type' => 'bool',
					'orderby' => false
				),
			);
		$this->_join .= "Left join `"._DB_PREFIX_."marketplace_seller_info` mpsi on  (a.`mp_id_seller`=mpsi.id)";
		$this->_select .= "mpsi.`seller_name` as seller_name,mpsi.`shop_name` as shop_name";
		$this->_where .='and is_done=1 and deleted=0';
		$this->list_no_link = true;
		$this->identifier = 'id';
		parent::__construct();
		
		if (!$this->module->active)
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
	}
	
		
	public function setMedia()
	{
		
		$this->addCSS(array(_MODULE_DIR_.$this->module->name.'/views/css/style.css')); 
		$this->addJS(array(_MODULE_DIR_.$this->module->name.'/views/js/fieldform.js')); 
		return parent::setMedia();
	}
	
	public function renderForm() 
	{
		return parent::renderForm();
	}
	
	
	public function initToolBar()
	{
		parent::initToolBar();
		unset($this->toolbar_btn['new']);
	}
	
	public function postProcess() 
	{
		if(Tools::isSubmit('statusmp_shipping_method')) {
			$this->toggleStatus();
		}
		parent::postProcess();

	}
	
	public function renderView()
	{	
		return parent::renderView();
	}

	public function assignCarriersToMainProduct()
	{
		$obj_shipmap = new Mpshippingproductmap();
		$obj_carr = new Carrier();

		$id_lang = $this->context->language->id;
		$start = 0;
		$limit = 0;
		$order_by = 'id_product';
		$order_way = 'ASC';

		$carr_detials_final = $obj_shipmap->getAllPrestaCarriers();

		$carr_ref = array();
		foreach ($carr_detials_final as $carr)
			if (!$obj_shipmap->checkMpCarriers($carr['id_carrier']))
				$carr_ref[] = $carr['id_reference'];

		$ps_prod_info = Product::getProducts($id_lang, $start, $limit, $order_by, $order_way, false, true);
		foreach ($ps_prod_info as $product)
			if (!$obj_shipmap->checkMpProduct($product['id_product']))
				$obj_shipmap->setProductCarrier($product['id_product'],$carr_ref);
	}

	public function ajaxProcessUpdateCarrierToMainProducts()
	{
		$id_lang = $this->context->language->id;
		$obj_carr = new Carrier();
		$carr_detials = $obj_carr->getCarriers($id_lang, true);
		if (empty($carr_detials))
		{
			$json = array('status' => 'ko', 'msg' => 'No Carriers available');
			die(Tools::jsonEncode($json));
		}
		else
		{
			$this->assignCarriersToMainProduct();
			$json = array('status' => 'ok', 'msg' => 'Carriers assigned successfully.');
			die(Tools::jsonEncode($json));
		}
	}
	
	public function toggleStatus() 
	{
		$mp_shipping_id = Tools::getValue('id');
		$obj_mp_shipping_met = new Mpshippingmethod($mp_shipping_id);
		$obj_mp_map = new Mpshippingmap();
		$is_mapped = $obj_mp_map->isAllreadyMapByShippingID($mp_shipping_id);
		if($is_mapped) 
		{
			if($obj_mp_shipping_met->active==1) 
			{
				$obj_mp_shipping_met->active = 0;
				$obj_mp_shipping_met->save();
				$ps_id_carriers = $is_mapped['ps_id_carriers'];
				$obj_carrier = new Carrier($ps_id_carriers);
				$obj_carrier->active = 0;
				if ($obj_mp_shipping_met->is_free)
					$obj_carrier->is_free = 1;
				$obj_carrier->save();
				Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
			}
			else
			{
				$obj_mp_shipping_met->active = 1;
				$obj_mp_shipping_met->save();
				$ps_id_carriers = $is_mapped['ps_id_carriers'];
				$obj_carrier = new Carrier($ps_id_carriers);
				if ($obj_mp_shipping_met->is_free)
					$obj_carrier->is_free = 1;
				$obj_carrier->active = 1;
				$obj_carrier->save();
				Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
			}
		} 
		else 
		{
			$obj_mp_shipping_met->active = 1;
			$obj_mp_shipping_met->save();
			$is_added = $obj_mp_shipping_met->addToCarrier($mp_shipping_id);
			$obj_mp_map->mp_shipping_id	 = $mp_shipping_id;
			$obj_mp_map->ps_id_carriers	 = $is_added;
			$obj_mp_map->save();
			$img_dir = dirname(__FILE__).'/../../img/logo/';
			if(file_exists($img_dir.$mp_shipping_id.'.jpg')) 
			{
				copy($img_dir.$mp_shipping_id.'.jpg',_PS_IMG_DIR_.'s/'.$is_added.'.jpg');
			}
			Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
		}
	}	
}