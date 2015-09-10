<?php
class Magetdd_Magepay_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
      $this->loadLayout();
      $this->_initLayoutMessages('customer/session');
      $this->_initLayoutMessages('catalog/session');
      $this->getLayout()->getBlock('head')->setTitle($this->__('Magepay Transactions'));
      $this->renderLayout();
    }
}
