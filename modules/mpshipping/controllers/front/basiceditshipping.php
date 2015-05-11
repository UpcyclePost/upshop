<?php
if (!defined('_PS_VERSION_'))
	exit;
class mpshippingbasiceditshippingModuleFrontController extends ModuleFrontController	
{
	public function initContent() 
	{
		parent::initContent();		
		$link = new Link();
		$id_customer = $this->context->cookie->id_customer;
		if($id_customer) 
		{
			$mp_customer = new MarketplaceCustomer();
			$mp_customer_info = $mp_customer->findMarketPlaceCustomer($id_customer);
			if($mp_customer_info) 
			{
				$is_seller = $mp_customer_info['is_seller'];
				if($is_seller==1) 
				{
					$obj_mp_shop = new MarketplaceShop();
					$mp_shop_info = $obj_mp_shop->getMarketPlaceShopInfoByCustomerId($id_customer);
					
					$mp_id_shop = $mp_shop_info['id'];						
					$is_main_error = Tools::getValue('is_main_error');
					
					if($is_main_error) 
						$this->context->smarty->assign('is_main_error',$is_main_error);
					else
						$this->context->smarty->assign('is_main_error',-1);
					
					$mp_shipping_id = Tools::getValue('id_shipping');
					$this->context->smarty->assign('mp_shipping_id',$mp_shipping_id);
					
					$step_count = Tools::getValue('step');
					if($step_count)
					
						$this->context->smarty->assign('step_count',$step_count);
					else 
					{
						$step_count = 1;
						$this->context->smarty->assign('step_count',1);
					}
					
					$zone_detail = Zone::getZones();
					$this->context->smarty->assign('zones',$zone_detail);
					
					//marketplace shipping detail
					$obj_mpshipping_method = new Mpshippingmethod($mp_shipping_id);
						
					$this->context->smarty->assign('mp_shipping_name',$obj_mpshipping_method->mp_shipping_name);
					$this->context->smarty->assign('transit_delay',$obj_mpshipping_method->transit_delay);
					$this->context->smarty->assign('shipping_method',$obj_mpshipping_method->shipping_method);
					$this->context->smarty->assign('tracking_url',$obj_mpshipping_method->tracking_url);
					$this->context->smarty->assign('grade',$obj_mpshipping_method->grade);
					$this->context->smarty->assign('shipping_handling',$obj_mpshipping_method->shipping_handling);
					$this->context->smarty->assign('is_free',$obj_mpshipping_method->is_free);
					$this->context->smarty->assign('max_width',$obj_mpshipping_method->max_width);
					$this->context->smarty->assign('max_height',$obj_mpshipping_method->max_height);
					$this->context->smarty->assign('max_depth',$obj_mpshipping_method->max_depth);
					$this->context->smarty->assign('max_weight',$obj_mpshipping_method->max_weight);
					$this->context->smarty->assign('is_free',$obj_mpshipping_method->is_free);
					
					//@shipping_method==1 billing accroding to weight
					//@shipping_method==2 billing accroding to price
					$shipping_method = $obj_mpshipping_method->shipping_method;
					$ranges = array();
					if ($obj_mpshipping_method->shipping_method==1) 
					{
						//find all range according to weight available for this shipping method
						$obj_range_weight = new Mprangeweight();
						$obj_range_weight->mp_shipping_id = $mp_shipping_id;
						$different_range = $obj_range_weight->getAllRangeAccordingToShippingId();
						if($different_range) 
							$ranges = $different_range;
						else
							$this->context->smarty->assign('different_range',-1);
					} 
					elseif ($obj_mpshipping_method->shipping_method==2) 
					{
						// find range by price available for shipping method
						$obj_range_price = new Mprangeprice();
						$obj_range_price->mp_shipping_id = $mp_shipping_id;
						$different_range = $obj_range_price->getAllRangeAccordingToShippingId();
						if($different_range) 
							$ranges = $different_range;
						else
							$this->context->smarty->assign('different_range',-1);								
					}
					
					if (!count($ranges))
						$ranges[] = array('id_range' => 0, 'delimiter1' => 0, 'delimiter2' => 0);

					/*echo "<pre>";
					print_r($ranges);
					die;*/
					$this->context->smarty->assign('ranges',$ranges);
					
					//find zone where shipping method deliver product
					$obj_mp_delivery = new Mpshippingdelivery();
					$id_zone_detail = $obj_mp_delivery->getIdZoneByShiipingId($mp_shipping_id);
					
					if($id_zone_detail) 
					{
						$fields_value = array();
						foreach($id_zone_detail as $id_zo_det) 
							$fields_value['zones'][$id_zo_det['id_zone']]=1;

						$this->context->smarty->assign('fields_value',$fields_value);
						
						//get delivery details by shipping id its provide price for different range
						$delivery_shipping_detail = $obj_mp_delivery->getDeliveryDetailByShiipingId($mp_shipping_id);
						
						if($delivery_shipping_detail) 
						{
							$price_by_range = array();
							foreach($delivery_shipping_detail as $delivery_shipping) 
							{
								if($shipping_method==2)
									$price_by_range[$delivery_shipping['mp_id_range_price']][$delivery_shipping['id_zone']] = round($delivery_shipping['base_price'],2);
								else
									$price_by_range[$delivery_shipping['mp_id_range_weight']][$delivery_shipping['id_zone']] = round($delivery_shipping['base_price'],2);
							}
							
							$this->context->smarty->assign('price_by_range',$price_by_range);
						}
					}
					$extra = array('shop'=>(int)$mp_id_shop);
					$dash_board_link = $link->getModuleLink('marketplace','marketplaceaccount',$extra);
					
					$sellershippinglist_link = $link->getModuleLink('mpshipping','sellershippinglist');
					
					$mpshippingprocess_link = $link->getModuleLink('mpshipping','mpshippingupdatebasic',$extra);
					
					$mpshippingprocess_link_update = $link->getModuleLink('mpshipping','mpshippingupdatebasic',array('shop'=>(int)$mp_id_shop,'submitupdatecarrier'=>1));
					
					$this->context->smarty->assign('self',dirname(__FILE__));
					$this->context->smarty->assign('sellershippinglist_link',$sellershippinglist_link);
					$this->context->smarty->assign('dash_board_link',$dash_board_link);
					
					$this->context->smarty->assign('mpshippingprocess_link',$mpshippingprocess_link);
					
					$this->context->smarty->assign('mpshippingprocess_link_update',$mpshippingprocess_link_update);
					$currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
					
					$this->context->smarty->assign('currency_sign', $currency->sign);
					$this->context->smarty->assign('PS_WEIGHT_UNIT', Configuration::get('PS_WEIGHT_UNIT'));
					$this->context->smarty->assign('PS_DIMENSION_UNIT', Configuration::get('PS_DIMENSION_UNIT'));
					$this->setTemplate('basicupdateshipping.tpl');
				}
			}
		}
	}
		
	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(_MODULE_DIR_.'mpshipping/css/sellershippinglist.css');
		$this->addJs(_MODULE_DIR_.'mpshipping/js/editmpshipping.js');
		$this->addJqueryPlugin('typewatch');
	}
}