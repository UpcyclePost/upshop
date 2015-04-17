<?php
	if (!defined('_PS_VERSION_'))
	exit;
	class mpshippingassignshippingforallModuleFrontController extends ModuleFrontController	
	{
		public function init(){
			$this->display_header = false;
			$this->display_footer = false;
		}
		public function initContent() 
		{
			$mp_shipping_methods = Tools::getValue('shipping_method');
			$mp_id_seller = Tools::getValue('mp_id_seller');
			$obj_shipping_method = new Mpshippingmethod();
			$mp_seller_products = $obj_shipping_method->getAllProducts($mp_id_seller);
			$error = array();
			$obj_mp_shipping_product_map = new Mpshippingproductmap();
			foreach($mp_seller_products as $mp_pro){
				$obj_mp_shipping_product_map->deleteMpShippingProductMapDetails($mp_pro['id']);
					foreach ($mp_shipping_methods as $mp_shipping_id) { 
						$obj_mp_shipping_map = new Mpshippingmap();
						$ps_carrier_id = $obj_mp_shipping_map->getCarrierId($mp_shipping_id);
						$obj_carrier = new Carrier($ps_carrier_id);
						$obj_mp_shipping_product_map->mp_shipping_id = $mp_shipping_id;
						$obj_mp_shipping_product_map->ps_id_carriers = $obj_carrier->id_reference;
						$obj_mp_shipping_product_map->mp_product_id = $mp_pro['id'];
						$result = $obj_mp_shipping_product_map->add();
						if($result){
							if($mp_pro['active'] == 1){
								$ps_shop_id = $obj_shipping_method->getMpShippingPsShopId($mp_shipping_id);
								$carriers[] = $obj_carrier->id_reference;
								$obj_mpshop_pro = new MarketplaceShopProduct();	
								$product_detail = $obj_mpshop_pro->findMainProductIdByMppId($mp_pro['id']);
								$ps_product_id = $product_detail['id_product'];						
								$obj_product = new Product($ps_product_id);
								$obj_product->setCarriers($carriers);
							}
							
						}else{
							$error[] = $mp_pro['id'];
						}
				}	
			}
			if(empty($error)){
				echo 1;
			}else{
				echo 0;
			}
			
		}

	}
?>