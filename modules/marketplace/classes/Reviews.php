<?php
class Reviews extends ObjectModel
{
	public $id_review;
	public $id_seller;
	public $id_customer;
	public $customer_email;
	public $rating;
	public $review;
	public $active;
	public $date_add;
	
	public static $definition = array(
		'table' => 'seller_reviews',
		'primary' => 'id_review',
		'fields' => array(
			'id_seller' => array('type' => self::TYPE_INT),
			'id_customer' => array('type' => self::TYPE_INT),
			'customer_email' => array('type' => self::TYPE_STRING),	
			'rating' => array('type' => self::TYPE_INT),	
			'review' => array('type' => self::TYPE_STRING),
			'active' => array('type' => self::TYPE_INT),
			'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
		),
	);

	public function getSellerReviews($seller_id)
	{
		$reviews_info = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."seller_reviews`
								WHERE `id_seller`=".$seller_id." AND `active` = 1 ORDER BY `date_add` DESC LIMIT 2");

		if ($reviews_info)
			return $reviews_info;

		return false;
	}

	public function getReviewById($id_review)
	{
		$reviews = Db::getInstance()->getRow("SELECT * FROM `"._DB_PREFIX_."seller_reviews`
								WHERE `id_review`=".$id_review);

		if ($reviews)
			return $reviews;

		return false;
	}
}