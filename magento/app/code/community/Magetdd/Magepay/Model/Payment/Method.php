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

    /**
     * Capture an already preauthorized payment
     *
     * Even if payments are marked for capture, they will not be settle until the end of day event is called or
     * alternative an account can be set to capture periodically in mutliples of 15minutes
     *
     * @param Varien_Object $payment
     * @param float $amount
     * @return bool|Mage_Payment_Model_Abstract
     */
    public function capture(Varien_Object $payment, $amount, $forceAuthorizeCapture = false)
    {
        parent::capture($payment, $amount);

        try {
            // Prepare the request data
            $transactionData = $this->_apiAdapter->generateTransactionData($payment);
            $transactionData['amount'] = $amount;

            // Do request to the api matching method
            $result = $this->_apiAdapter->capture($transactionData);

            $payment->setTransactionAdditionalInfo('raw_details_info', $result);
            $payment->setIsTransactionClosed(true);

        } catch (Exception $e) {
            $this->refund($payment, $amount);
            Mage::logException($e);
            Mage::throwException("Demac Chase PaymentTech Capture Error: " . $e->getMessage());
        }
        return $this;
    }

    /**
     * Void an authorized payment
     *
     * @param Varien_Object $payment
     * @return bool|Mage_Payment_Model_Abstract
     */
    public function void(Varien_Object $payment)
    {
        parent::void($payment);

        try {
            $transactionData = $this->_apiAdapter->generateTransactionData($payment);

            // Do request to the api matching method
            $result = $this->_apiAdapter->void($transactionData);
            unset($transactionData['amount']);

            $payment->setTransactionAdditionalInfo('raw_details_info', $result);
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::throwException("Demac Chase PaymentTech Void Error: " . $e->getMessage());
        }
        return $this;
    }

    /**
     * Cancel action for the payment
     *
     * This method will reuse the same logic as the void transaction, cancelling an order in Magento will not refund
     * any funds previously capture.
     *
     * @param Varien_Object $payment
     * @return bool|Mage_Payment_Model_Abstract
     */
    public function cancel(Varien_Object $payment)
    {
        parent::cancel($payment);
        $this->void($payment);
        return $this;
    }

    /**
     * Refund a an already capture payment, called by the credit memo.
     *
     * @param Varien_Object $payment
     * @param float $amount
     * @return bool|Mage_Payment_Model_Abstract
     */
    public function refund(Varien_Object $payment, $amount)
    {
        parent::refund($payment, $amount);

        try {
            // Prepare the request data
            $transactionData = $this->_apiAdapter->generateTransactionData($payment);
            $transactionData['amount'] = $amount;

            // Do request to the api matching method
            $result = $this->_apiAdapter->refund($transactionData);
            $payment->setTransactionId($result['txRefNum']);
            $payment->setTransactionAdditionalInfo('raw_details_info', $result);

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::throwException("Demac Chase PaymentTech Refund Error: " . $e->getMessage());
        }
        return $this;
    }




}
