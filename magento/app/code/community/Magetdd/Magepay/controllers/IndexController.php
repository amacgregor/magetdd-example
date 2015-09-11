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

    public function viewAction()
    {
      $transaction_id = $this->getRequest()->getParam('transaction_id');
      $transaction = Mage::getModel('magetdd_magepay/transaction')->load($transaction_id);

      if (!$transaction->getId())
      {
        Mage::getSingleton('customer/session')->addError('Transaction not found.');
        $this->_redirect('*/*/index');
      }

      Mage::register('current_transaction',$transaction);

      $this->loadLayout();
      $this->_initLayoutMessages('customer/session');
      $this->_initLayoutMessages('catalog/session');
      $this->getLayout()->getBlock('head')->setTitle($this->__('Transaction Details'));
      $this->renderLayout();
    }

}
