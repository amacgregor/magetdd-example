<?php

class Magetdd_Magepay_Model_Payment_Method extends Mage_Payment_Model_Method_Cc
{
  protected $_code                    = 'magetdd_magepay';
  protected $_canAuthorize            = true;
  protected $_canVoid                 = true;
  protected $_canCapture              = true;
  protected $_canRefund               = true;
  protected $_canCapturePartial       = true;
  protected $_canRefundInvoicePartial = true;
  protected $_canSaveCc     = false;


  public function __construct(array $services = array())
  {
      if (isset($services['api_adapter'])) {
          $this->_apiAdapter = $services['api_adapter'];
      } else {
          $this->_apiAdapter = new Magetdd_Magepay_Model_Payment_Adapter();
      }
  }

  public function canSaveCc()
  {
    return $this->_canSaveCc;
  }

  public function validateCreditCardNumber($cardNumber)
  {
    $number = preg_replace('/\D/', '', $cardNumber);

    $number_length  = strlen($number);
    $parity         = $number_length % 2;

    $total=0;
    for ($i=0; $i < $number_length; $i++) {
       $digit = $number[$i];
       if ($i % 2 == $parity) {
           $digit *= 2;
           if ($digit > 9) {
               $digit-=9;
           }
       }
       $total += $digit;
    }
    return ($total % 10 == 0) ? true : false;
  }

  /**
  * Authorize a Payment against the gateway
  *
  * @param Varien_Object $payment
  * @param float $amount
  * @return bool|Mage_Payment_Model_Abstract
  */
  public function authorize(Varien_Object $payment, $amount)
  {
    parent::authorize($payment, $amount);

    // Prepare the request data
    $transactionData = $this->_apiAdapter->generateTransactionData($payment);

    // Do request to the api matching method
    $result = $this->_apiAdapter->authorize($transactionData);

    $payment->setTransactionId($result['txRefNum']);
    $payment->setIsTransactionClosed(false);

    return $this;
  }
}
