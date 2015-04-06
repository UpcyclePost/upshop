<?php
class MarketplaceContactSellerProcessModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		$id_lang = Context::getContext()->cookie->id_lang;
		$id_product = Tools::getValue('id_product');
		$id_customer = Tools::getValue('id_customer');
		$id_seller = Tools::getValue('seller_id');
		$subject = Tools::getValue('query_subject');
		$description = Tools::getValue('query_desc');
		$seller_email = Tools::getValue('seller_email');
		$from_name = Tools::getValue('mp_guest_name');
		$from_email = Tools::getValue('mp_guest_email');

		$link = new Link();
		$obj_mpseller = new SellerInfoDetail($id_seller);
		$obj_product = new Product($id_product, false, $id_lang);
		//$quantity = StockAvailable::getQuantityAvailableByProduct($id_product);

		$save_query = Db::getInstance()->insert('customer_query', array('id_product' => $id_product,
													'id_customer' => $id_customer,
													'id_customer_to' => $id_seller,
													'title' => $subject,
													'description' => $description,
													'cust_email' => $from_email));

		if ($save_query)
		{
			$templateVars = array('{from_email}' => $from_email,
								'{from_name}' => $from_name,
								'{seller_name}' => $obj_mpseller->seller_name,
								'{product_name}' => $obj_product->name,
								'{price}' => $obj_product->price,
								'{subject}' => $subject,
								'{description}' => $description,
								'{product_link}' => $link->getProductLink($obj_product));

			$temp_path = _PS_MODULE_DIR_.'marketplace/mails/';
			
			$send = Mail::Send(
				(int)$id_lang,
				'mail_send_seller',
				Mail::l('New query created on product'),
				$templateVars,
				$seller_email,
				null,
				$from_email,
				null,
				null,
				null,
				$temp_path,
				false,
				null,
				null);
			if ($send)
				die(Tools::jsonEncode(array('status' => 'ok', 'msg' => 'Mail successfully sent.')));
			else
				die(Tools::jsonEncode(array('status' => 'ko', 'msg' => 'Some error while sending mail')));
		}
		else
		 	die(Tools::jsonEncode(array('status' => 'ko', 'msg' => 'Some error sending message to seller.')));
	}
}
?>