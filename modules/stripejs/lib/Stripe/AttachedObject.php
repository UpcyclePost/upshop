<?php
/**
*Copyright (c) 2014. All rights reserved - NTS
*You are NOT allowed to modify the software. 
*It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*/

class Stripe_AttachedObject extends Stripe_Object
{
  public function replaceWith($properties)
  {
    $removed = array_diff(array_keys($this->_values), array_keys($properties));
    // Don't unset, but rather set to null so we send up '' for deletion.
    foreach ($removed as $k) {
      $this->$k = null;
    }

    foreach ($properties as $k => $v) {
      $this->$k = $v;
    }
  }
}
