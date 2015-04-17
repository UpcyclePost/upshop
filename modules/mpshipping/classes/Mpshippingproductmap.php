<?php
	class Mpshippingproductmap extends ObjectModel
	{
		public $id;	
		public $mp_shipping_id;
		public $ps_id_carriers;
		public $mp_product_id;
		public $date_add;		
		public $date_upd;
		
		public static $definition = array(
			'table' => 'mp_shipping_product_map',
			'primary' => 'id',
			'fields' => array(
				'mp_shipping_id' => array('type' => self::TYPE_INT ,'validate' => 'isUnsignedInt', 'required' => true),
				'ps_id_carriers' => array('type' => self::TYPE_INT ,'validate' => 'isUnsignedInt', 'required' => true),
				'mp_product_id' => array('type' => self::TYPE_INT ,'validate' => 'isUnsignedInt', 'required' => true),
				'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
				'date_upd' =>array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
			),
		);
		
		public function add($autodate = true, $null_values = false)
		{
			if (!parent::add($autodate, $null_values))
				return false;
			return Db::getInstance()->Insert_ID();
		}
	
		public function update($null_values = false)
		{
			Cache::clean('getContextualValue_'.$this->id.'_*');
			$success = parent::update($null_values);
			return $success;
		}

		public function delete()
		{
			return parent::delete();
		}
		
		public function getMpShippingProductMapDetails($mp_product_id)
		{
			$mp_shipping_product_map_details = Db::getInstance()->executeS('
				SELECT * FROM `'._DB_PREFIX_.'mp_shipping_product_map` 
				WHERE `mp_product_id` = '.(int)$mp_product_id.'
				');
		
			if(empty($mp_shipping_product_map_details)){
				return false;
			}else{
				return $mp_shipping_product_map_details;
			}
		}


		public function deleteMpShippingProductMapDetails($mp_product_id)
		{
			Db::getInstance()->execute(
				'DELETE FROM `'._DB_PREFIX_.'mp_shipping_product_map`
				WHERE `mp_product_id` = '.(int)$mp_product_id.'');
		
		}

		public function checkMpProduct($id_product)
		{
			$mp_product = Db::getInstance()->getValue('SELECT `marketplace_seller_id_product`
														FROM `'._DB_PREFIX_.'marketplace_shop_product`
														WHERE `id_product` = '.(int)$id_product);
			if ($mp_product)
				return true;

			return false;
		}

		public function checkMpCarriers($id_carrier)
		{
			$mp_carrier = Db::getInstance()->getValue('SELECT `mp_shipping_id`
														FROM `'._DB_PREFIX_.'mp_shipping_map`
														WHERE `ps_id_carriers` = '.(int)$id_carrier);
			if ($mp_carrier)
				return true;

			return false;
		}

		public function setProductCarrier($id_product, $carr_ref)
		{
			$obj_prod = new Product($id_product);
			$ps_carriers = $obj_prod->getCarriers();
			if (empty($ps_carriers))
				$obj_prod->setCarriers($carr_ref);
		}

		public function getAllPrestaCarriers()
		{
			$id_lang = Context::getContext()->language->id;
			$obj_carr = new Carrier();
			$carr_detials = $obj_carr->getCarriers($id_lang, true);

			if (!$carr_detials)
				return false;

			$carr_details_mod = $obj_carr->getCarriers($id_lang, true, false, false, null,2);

			if ($carr_details_mod)
				$carr_detials_final = array_merge($carr_detials, $carr_details_mod);
			else
				$carr_detials_final = $carr_detials;

			return $carr_detials_final;
		}

		public function getMpShippingForProducts($id_mp_shipping)
		{
			$mp_ship = Db::getInstance()->getValue('SELECT `mp_shipping_id`
														FROM `'._DB_PREFIX_.'mp_shipping_product_map`
														WHERE `mp_shipping_id` = '.(int)$id_mp_shipping);
			if ($mp_ship)
				return true;

			return false;
		}
	}