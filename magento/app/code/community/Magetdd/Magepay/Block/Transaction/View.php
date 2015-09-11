<?php

class Magetdd_Magepay_Block_Transaction_View extends Mage_Core_Block_Template
{
  public function getTransaction()
  {
      return Mage::registry('current_transaction');
  }
}
