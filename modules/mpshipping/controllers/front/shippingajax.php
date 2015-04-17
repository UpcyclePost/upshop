<?php
	if (!defined('_PS_VERSION_'))
	exit;
	class mpshippingshippingajaxModuleFrontController extends ModuleFrontController	
	{
		public function initContent() 
		{
			parent::initContent();
			$fun = Tools::getValue('fun');
			if($fun=='find_country') 
			{
				$id_zone = Tools::getValue('id_zone');
				$country_detail = $this->findCountry($id_zone);
				$json_array_rev = Tools::jsonEncode($country_detail);
				echo $json_array_rev;
			}
			else if($fun=='find_state') 
			{
				$id_country = Tools::getValue('id_country');
				$state_detail = $this->findState($id_country);
				$json_array_rev = Tools::jsonEncode($state_detail);
				echo $json_array_rev;
				
			} 
			else if($fun=='find_range') 
			{
				$id_zone = Tools::getValue('id_zone');
				$id_country = Tools::getValue('id_country');
				$id_state = Tools::getValue('id_state');
				$shipping_method = Tools::getValue('shipping_method');
				$mpshipping_id = Tools::getValue('mpshipping_id');
				
				//shipping_method 2 for price
				//shipping_method 1 for weight
				if($shipping_method==2) 
				{
					$delivery_method = $this->rangeByPrice($id_zone,$mpshipping_id);
					if($delivery_method) 
					{
						$current_price = $this->CurrentPrice($delivery_method,$id_zone,$id_country,$id_state,$mpshipping_id);
						$json_array_rev = Tools::jsonEncode($current_price);
						echo $json_array_rev;
					} 
					else 
					{
						echo 0;
					}
				} 
				else if($shipping_method==1) 
				{
					$delivery_method = $this->rangeByWeight($id_zone,$mpshipping_id);
					if($delivery_method) 
					{
						$current_price = $this->CurrentPrice($delivery_method,$id_zone,$id_country,$id_state,$mpshipping_id);
						$json_array_rev = Tools::jsonEncode($current_price);
						echo $json_array_rev;
					} 
					else 
					{
						echo 0;
					}
				}
			} 
			else if($fun=='range_add') 
			{
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
						$success = array('su'=>1);
					} 
					else 
					{
						$success = array('su'=>0);
					}
				} 
				else if($shipping_method==1) 
				{
					$delivery_method = $this->rangeByWeight($id_zone,$mpshipping_id);
					if($delivery_method) 
					{
						$this->impactEntry($delivery_method,$id_zone,$id_country,$id_state,$mpshipping_id);
						$success = array('su'=>1);
					} 
					else 
					{
						$success = array('su'=>0);
					}
				}
				$json_array_rev = Tools::jsonEncode($success);
				echo $json_array_rev;
			}
		}
		
		public function findCountry($id_zone) 
		{
			$obj_shipping_imp = new Mpshippingimpact();
			return $obj_shipping_imp->getCountriesByZoneId($id_zone, $this->context->language->id);
		}
		
		public function findState($id_country)
		{
			$obj_shipping_imp = new Mpshippingimpact();
			return $obj_shipping_imp->getStatesByIdCountry($id_country);
		}
		
		public function rangeByPrice($id_zone,$mpshipping_id) 
		{
			$obj_mpshipping_del = new Mpshippingdelivery();
			$delivery_method = $obj_mpshipping_del->getDliveryMethodForPriceRange($id_zone,$mpshipping_id);
			if($delivery_method) 
			{
				return $delivery_method;
			} 
			else 
			{
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
		
		//enter impact price
		public function impactEntry($delivery_method,$id_zone,$id_country,$id_state,$mpshipping_id) {
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
		
		//find current impact price by zone and delivery method
		public function CurrentPrice($delivery_method,$id_zone,$id_country,$id_state,$mpshipping_id) 
		{
			$current_price_array = array();
			foreach($delivery_method as $delivery_me) 
			{
				$shipping_delivery_id = $delivery_me['id'];
				$delimiter1 = $delivery_me['delimiter1'];
				$delimiter2 = $delivery_me['delimiter2'];
				$id_range = $delivery_me['id_range'];
				$mp_shipping_impact =  new Mpshippingimpact();
				$is_in_impact = $mp_shipping_impact->isAllReadyInImpact($mpshipping_id,$shipping_delivery_id,$id_zone,$id_country,$id_state);
				
				if($is_in_impact) {
					$impact_price = $is_in_impact['impact_price'];
				} else {
					$impact_price = 0;
				}
				
				$current_price_array[] = array('id'=>$shipping_delivery_id,'delimiter1'=>$delimiter1,'delimiter2'=>$delimiter2,'id_range'=>$id_range,'impact_price'=>$impact_price);
			}
			
			return $current_price_array;
		}
	}
?>