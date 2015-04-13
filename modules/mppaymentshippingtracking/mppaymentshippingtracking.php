<?php
if (!defined('_PS_VERSION_'))
        exit;
include_once dirname(__FILE__).'/classes/MarketplaceShippingInfo.php';
include_once dirname(__FILE__).'/classes/MarketplaceDeliveryInfo.php';
class MpPaymentShippingTracking extends Module
{
 	const INSTALL_SQL_FILE = 'install.sql';
    private $_postErrors = array();
	public function __construct()
	{
		$this->name          = 'mppaymentshippingtracking';
		$this->tab           = 'front_office_features';
		$this->version       = '1.6.1';
		$this->author        = 'Webkul';
		$this->need_instance = 0;
		$this->dependencies = array('marketplace');
				  
		parent::__construct();	  
		$this->displayName     = $this->l('Marketplace Payment Shipping Tracking');
		$this->description     = $this->l('Seller can manage payment status and can add shipping tracking number');
	}

	/**
     * function for add JS file
     */
    public function hookDisplayHeader()
	{
		$this->context->controller->addJS(($this->_path).'js/mppaymentshippingtracking.js');
		$this->context->controller->addCSS(($this->_path).'css/mppaymentshippingtracking.css');
	}

	/**
	 * [hookFinalShipping description]
	 * @return [type] [description]
	 */
	public function hookDisplayMpordershippingrighthook()
	{
		$flag = Tools::getValue('flag');
		$l = Tools::getValue('l');
		$shop = Tools::getValue('shop');
		$id_order = Tools::getValue('id_order');
		$is_order_state_updated = Tools::getValue('is_order_state_updated');
		$states = OrderState::getOrderStates($this->context->language->id);
		$img_url = _PS_IMG_;

		$order = new Order($id_order);
		$history = $order->getHistory($this->context->language->id);
		$currentState = $order->getCurrentOrderState();
		$current_id_lang = $this->context->language->id;
		$link = new Link();
		$params = array('flag' => $flag, 'shop' => $shop, 'l' => $l, 'id_order' => $id_order);
		$update_url_link = $link->getModuleLink('mppaymentshippingtracking', 'updateorderstatusprocess', $params);
		$update_tracking_number_link = $link->getModuleLink('mppaymentshippingtracking', 'updateordertrackingnumber');
		$this->context->smarty->assign("update_url_link",$update_url_link);
		$this->context->smarty->assign("update_tracking_number_link",$update_tracking_number_link);
		$this->context->smarty->assign("states",$states);
		$this->context->smarty->assign("current_id_lang",$current_id_lang);
		$this->context->smarty->assign("order",$order);
		$this->context->smarty->assign("history",$history);
		$this->context->smarty->assign("currentState",$currentState);
		$this->context->smarty->assign("img_url",$img_url);
		$this->context->smarty->assign("is_order_state_updated",$is_order_state_updated);
		$this->context->smarty->assign("shipping_details",1);

		$obj_shipping_detail = new MarketplaceShippingInfo();
		$shipping_details = $obj_shipping_detail->getShippingDetailsByOrderId($id_order);
		if($shipping_details)
		{
			$this->context->smarty->assign("shipping_date",$shipping_details['shipping_date']);
			$this->context->smarty->assign("shipping_description",$shipping_details['shipping_description']);
		}
		$obj_delivery_detail = new MarketplaceDeliveryInfo();
		$delivery_details = $obj_delivery_detail->getDeliveryDetailsByOrderId($id_order);
		if($delivery_details)
		{
			$this->context->smarty->assign("delivery_date",$delivery_details['delivery_date']);
			$this->context->smarty->assign("received_by",$delivery_details['received_by']);
		}
		return $this->display(__FILE__, 'updateOrder.tpl');
	}

	/**
	 * [install description]
	 * @return [type] [description]
	 */
	public function install()
	{
		if (!file_exists(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE))
			return false;
		else if (!$sql = Tools::file_get_contents(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE))
			return false;
			
		$sql = str_replace(array(
			'PREFIX_',
			'ENGINE_TYPE'
		), array(
				_DB_PREFIX_,
				_MYSQL_ENGINE_
			), $sql);
			
		$sql = preg_split("/;\s*[\r\n]+/", $sql);
		foreach ($sql AS $query)
			if ($query)
				if (!Db::getInstance()->execute(trim($query)))
					return false;
	 
		if (!parent::install() 
	  	  || !$this->registerHook('DisplayMpordershippingrighthook') 
	  	  || !$this->registerHook('displayHeader'))
		  return false;
		return true;
	}

	/**
	 * [uninstall description]
	 * @return [type] [description]
	 */
	public function uninstall()
    {
        if(!parent::uninstall() 
            || !$this->dropTable())
            return false;
        return true;
    }

    /**
     * [dropTable description]
     * @return [type] [description]
     */
    public function dropTable()
    {
        $table_name = array('marketplace_shipping', 'marketplace_delivery');
        foreach($table_name as $name)
            if (!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.$name))
                return false;

        return true;
    }
}	
?>