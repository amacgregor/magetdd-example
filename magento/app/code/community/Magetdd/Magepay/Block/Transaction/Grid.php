<?php

class Magetdd_Magepay_Block_Transaction_Grid extends Mage_Core_Block_Template
{
  private $_databaseAdapter;

  public function __construct(array $services = array())
  {
    // Initialize the database adapter for Magento
    if (isset($services['database_adapter'])) {
        $this->_databaseAdapter = $services['database_adapter'];
    } else {
        $this->_databaseAdapter = new Magetdd_Magepay_Model_Adapter_Magento();
    }
  }

  public function getTransactions()
  {
    $transactionCollection = $this->_databaseAdapter
      ->getModelCollection('magetdd_magepay/transaction')
      ->addFieldToFilter('customer_id', $this->getCustomerId());

    return $transactionCollection;
  }

  protected function getCustomerId()
  {
    return $this->_databaseAdapter->getCurrentCustomerId();
  }
}
