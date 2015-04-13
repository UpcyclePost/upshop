<?php
class MpPaymentShippingTrackingUpdateOrderStatusProcessModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		$flag = Tools::getValue('flag');
		$l = Tools::getValue('l');
		$shop = Tools::getValue('shop');
		$id_order = Tools::getValue('id_order');
		$id_order_state = Tools::getValue('id_order_state_checked');
		$shipping_submited = Tools::getValue('shipping_info_set');
		$delivery_submited = Tools::getValue('delivery_info_set');

		if($shipping_submited == 1)
		{
			$shipping_description = Tools::getValue('edit_shipping_description');
			$shipping_date = Tools::getValue('shipping_date');
			if(isset($shipping_description) && isset($shipping_date))
			{
				$obj_shipping_detail = new MarketplaceShippingInfo();
				$is_shipping_info = $obj_shipping_detail->getShippingDetailsByOrderId($id_order);
				if($is_shipping_info)
					$obj_shipping_detail = new MarketplaceShippingInfo($is_shipping_info['id']);
				else
					$obj_shipping_detail = new MarketplaceShippingInfo();
				$obj_shipping_detail->order_id = $id_order;
				$obj_shipping_detail->shipping_description = $shipping_description;
				$obj_shipping_detail->shipping_date = $shipping_date;
				$obj_shipping_detail->save();
				$this->redirectByIdOrderAndIdOrderState($id_order, $id_order_state, $flag, $shop, $l);
			}
		}
		elseif($delivery_submited == 1)
		{
			$delivery_description = Tools::getValue('edit_received_by');
			$delivery_date = Tools::getValue('delivery_date');
			if(isset($delivery_description) && isset($delivery_date))
			{
				$obj_delivery_detail = new MarketplaceDeliveryInfo();
				$is_delivery_info = $obj_delivery_detail->getDeliveryDetailsByOrderId($id_order);
				if($is_delivery_info)
					$obj_delivery_detail = new MarketplaceDeliveryInfo($is_delivery_info['id']);
				else
					$obj_delivery_detail = new MarketplaceDeliveryInfo();
				$obj_delivery_detail->order_id = $id_order;
				$obj_delivery_detail->received_by = $delivery_description;
				$obj_delivery_detail->delivery_date = $delivery_date;
				$obj_delivery_detail->save();
				$this->redirectByIdOrderAndIdOrderState($id_order, $id_order_state, $flag, $shop, $l);
			}
		}
		else
			$this->redirectByIdOrderAndIdOrderState($id_order, $id_order_state, $flag, $shop, $l);
	}

	public function redirectByIdOrderAndIdOrderState($id_order, $id_order_state, $flag, $shop, $l)
	{
		$link = new Link();
		$params = array('flag' => $flag, 'shop' => $shop, 'l' => $l, 'id_order' => $id_order);
		$update_url_link = $link->getModuleLink('marketplace', 'marketplaceaccount', $params);
		$params = array('flag' => $flag, 'shop' => $shop, 'l' => $l, 'id_order' => $id_order, 'is_order_state_updated' => 1);
		$update_url_link_order = $link->getModuleLink('marketplace', 'marketplaceaccount', $params);
		$obj_delivery_detail = new MarketplaceDeliveryInfo();
		$is_updated = $obj_delivery_detail->updateOrderByIdOrderAndIdOrderState($id_order, $id_order_state);
		if($is_updated)
			Tools::redirect($update_url_link_order);
		else
			Tools::redirect($update_url_link);
	}
}
?>