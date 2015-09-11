<?php
class Magetdd_Magepay_IndexController extends Mage_Core_Controller_Front_Action
{
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function preDispatch()
    {
        parent::preDispatch();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    public function indexAction()
    {
      $this->loadLayout();
      $this->_initLayoutMessages('customer/session');
      $this->_initLayoutMessages('catalog/session');
      $this->getLayout()->getBlock('head')->setTitle($this->__('Magepay Transactions'));
      $this->renderLayout();
    }
}
