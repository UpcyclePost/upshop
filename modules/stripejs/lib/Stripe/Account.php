<?php
/**
*Copyright (c) 2014. All rights reserved - NTS
*You are NOT allowed to modify the software. 
*It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*/

class Stripe_Account extends Stripe_SingletonApiResource
{
  public static function constructFrom($values, $apiKey=null)
  {
    $class = get_class();
    return self::scopedConstructFrom($class, $values, $apiKey);
  }

  public static function retrieve($apiKey=null)
  {
    $class = get_class();
    return self::_scopedSingletonRetrieve($class, $apiKey);
  }
}
