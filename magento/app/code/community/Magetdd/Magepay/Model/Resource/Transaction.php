<?php

class Magetdd_Magepay_Model_Resource_Transaction extends Mage_Core_Model_Resource_Db_Abstract
{
  protected $_isPkAutoIncrement = false;

  protected function _construct()
  {
      $this->_init('magetdd_magepay/transaction', 'transaction_id');
  }
}
