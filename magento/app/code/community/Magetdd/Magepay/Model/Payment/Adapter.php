<?php

class Magetdd_Magepay_Model_Payment_Adapter extends Mage_Core_Model_Abstract
{


  public function authorize() { return false;}
  public function void() { return false; }
  public function capture() {return false; }
  public function refund() { return false; }
  public function isPartialTransaction() { return false; }

  /**
   * Parse the payment data, order data and address information into a signle array called transactionData
   *
   * @param $payment
   */
  public function generateTransactionData($payment)
  {
      $order = $payment->getOrder();

      $result =
          $this->_getAvsData($order) +
          $this->_getOrderData($order) +
          $this->_getPaymentData($payment);
      return $result;
  }

  /**
   * Parse the order into an array with the required values
   *
   * @param $order
   * @return array
   */
  protected function _getOrderData($order)
  {
      $orderData = array(
          'orderID'           => $order->getIncrementId(),
          'amount'            => $order->getBaseGrandTotal(),
          'taxAmount'         => $order->getBaseTaxAmount(),
          'customerIpAddress' => $order->getRemoteIp(),
      );

      return $orderData;
  }

  /**
   * Parse the billing address into an array with the required values
   *
   * @param $order
   * @return array
   */
  protected function _getAvsData($order)
  {
      $billingAddress = $order->getBillingAddress();

      $avsData = array(
          'avsZip'            => $billingAddress->getPostcode(),
          'avsAddress1'       => $this->_getStreetDetails($billingAddress->getStreet(),0),
          'avsAddress2'       => $this->_getStreetDetails($billingAddress->getStreet(),1),
          'avsCity'           => $billingAddress->getCity(),
          'avsState'          => $billingAddress->getRegionCode(),
          'avsName'           => $order->getCustomerName(),
          'avsCountryCode'    => $billingAddress->getCountryId(),
      );


      return $avsData;
  }

  /**
   * Parse the payment data into an array with the required values
   *
   * @param $payment
   * @return array
   */
  protected function _getPaymentData($payment)
  {
      $paymentData = array(
          'cardBrand'       => $payment->getCcType(),
          'ccAccountNum'    => $payment->getCcNumber(),
          'ccExp'           => $payment->getCcExpYear() . sprintf("%02s", $payment->getCcExpMonth()),
          'ccCardVerifyNum' => $payment->getCcCid(),
      );

      if($payment->hasLastTransId() && !$payment->hasParentTransactionId()){
          $paymentData['txRefNum'] = $payment->getData('last_trans_id');
      }elseif($payment->hasParentTransactionId()){
          $paymentData['txRefNum'] = $payment->getParentTransactionId();
      }elseif($payment->hasTransactionId()) {
          $paymentData['txRefNum'] = $payment->getTransactionId();
      }

      if(isset($paymentData['ccCardVerifyNum']) &&
         ($paymentData['cardBrand'] == 'VI' || $paymentData['cardBrand'] == 'DI'  ))
      {
          $paymentData['ccCardVerifyPresenceInd'] = 1;
      }else {
          $paymentData['ccCardVerifyPresenceInd'] = null;
      }

      return $paymentData;
  }

  /**
   * Function for accessing the street address easily
   *
   * @param $address
   * @param $position
   * @return string
   */
  protected function _getStreetDetails($address, $position)
  {
      if(isset($address[$position]))
      {
          return $address[$position];
      } else {
          return '';
      }
  }
}
