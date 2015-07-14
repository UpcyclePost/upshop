<?php

Class ProductController extends ProductControllerCore
{

	public function initContent()
	{


		$adjacent_products = $this->product->getAdjacentProducts();

		$this->context->smarty->assign(array(
			'prev_product'=> $adjacent_products['previous'],
			'next_product'=> $adjacent_products['next']
		));

		parent::initContent();
	}	
}