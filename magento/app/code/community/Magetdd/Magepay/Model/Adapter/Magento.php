<?php

class Magetdd_Magepay_Model_Adapter_Magento extends Mage_Core_Model_Abstract
{
  public function getConfigValue($path, $storeId = null)
  {
      if (null === $storeId) {
          $storeId = $this->getStore();
      }
      return Mage::getStoreConfig($path);
  }

  public function getStore()
  {
      return Mage::app()->getStore();
  }

  public function getCurrentCustomerId()
  {
      return Mage::getSingleton('customer/session')->getCustomer()->getId();
  }

  public function getModel($alias)
  {
    return Mage::getModel($alias);
  }

  public function getModelCollection($alias)
  {
    return Mage::getModel($alias)->getCollection();
  }
}
