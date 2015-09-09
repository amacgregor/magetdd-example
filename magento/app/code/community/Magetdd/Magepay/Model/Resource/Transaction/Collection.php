<?php

class Magetdd_Magepay_Model_Resource_Transaction_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
  protected function _construct()
  {
    parent::_construct();
    $this->_init('magetdd_magepay/transaction');
  }
}
