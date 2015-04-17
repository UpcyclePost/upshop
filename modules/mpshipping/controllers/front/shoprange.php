<?php
	if (!defined('_PS_VERSION_'))
		exit;
	class mpshippingshoprangeModuleFrontController extends ModuleFrontController	
	{
		public function initContent() 
		{
			parent::initContent();
			$mpshipping_id = Tools::getValue('range_mpshipping_id');
			$id_zone = Tools::getValue('range_mpshipping_id_zone');
			$id_country = Tools::getValue('range_mpshipping_id_country');
			$id_state = Tools::getValue('range_mpshipping_id_state');
			$shipping_method = Tools::getValue('range_shipping_method');
			//$id_state = 0 for all
			if($shipping_method==2) 
			{
				$delivery_method = $this->rangeByPrice($id_zone,$mpshipping_id);
				if($delivery_method) 
				{
					$this->impactEntry($delivery_method,$id_zone,$id_country,$id_state,$mpshipping_id);
					$success = 1;
				} 
				else 
				{
					$success = 0;
				}
			} 
			else if($shipping_method==1) 
			{
				$delivery_method = $this->rangeByWeight($id_zone,$mpshipping_id);
				if($delivery_method) 
				{
					$this->impactEntry($delivery_method,$id_zone,$id_country,$id_state,$mpshipping_id);
					$success = 1;
				} 
				else 
				{
					$success = 0;
				}
			}	
			die($success);
		}
		
		public function rangeByPrice($id_zone,$mpshipping_id) 
		{
			$obj_mpshipping_del = new Mpshippingdelivery();
			$delivery_method = $obj_mpshipping_del->getDliveryMethodForPriceRange($id_zone,$mpshipping_id);
			if($delivery_method) {
				return $delivery_method;
			} else {
				return false;
			}
		}
		
		public function rangeByWeight($id_zone,$mpshipping_id) 
		{
			$obj_mpshipping_del = new Mpshippingdelivery();
			$delivery_method = $obj_mpshipping_del->getDliveryMethodForWeightRange($id_zone,$mpshipping_id);
			if($delivery_method) {
				return $delivery_method;
			} else {
				return false;
			}
		}
		
		public function impactEntry($delivery_method,$id_zone,$id_country,$id_state,$mpshipping_id) 
		{
				//var_dump($_POST);
				$obj_shipping_imp = new Mpshippingimpact();
				$obj_shipping_imp->mp_shipping_id = $mpshipping_id;
				$obj_shipping_imp->id_zone = $id_zone;
				$obj_shipping_imp->id_country = $id_country;
				$obj_shipping_imp->id_state = $id_state;
				
				foreach($delivery_method as $delivery_me) {
					$shipping_delivery_id = $delivery_me['id'];
					$obj_shipping_imp->shipping_delivery_id = $shipping_delivery_id;
					$new_imapact_price = Tools::getValue('delivery'.$shipping_delivery_id);
					$obj_shipping_imp->impact_price = $new_imapact_price;
					
					$is_exist_impact = $obj_shipping_imp->isAllReadyInImpact($mpshipping_id,$shipping_delivery_id,$id_zone,$id_country,$id_state);
					if($is_exist_impact) {
						$obj_shipping_imp->id = $is_exist_impact['id'];
						$obj_shipping_imp->save();
					} else {
						$obj_shipping_imp->add();
					}
				}
				return true;
		}
	}
?>