<?php

class Magetdd_Magepay_Model_Transaction extends Mage_Core_Model_Abstract
{
    public function __construct()
    {
        parent::_construct();
        $this->_init('magetdd_magepay/transaction');
    }

    public function getTransactionId()
    {
        return $this->_getData('transaction_id');
    }

    public function getOrderId()
    {
        return $this->_getData('order_id');
    }

    public function getState()
    {
        return $this->_getData('state');
    }

    public function getCreatedAt()
    {
        return $this->_getData('created_at');
    }
}
