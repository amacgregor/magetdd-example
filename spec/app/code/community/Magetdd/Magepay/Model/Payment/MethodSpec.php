<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Magetdd_Magepay_Model_Payment_MethodSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Magetdd_Magepay_Model_Payment_Method');
    }

    function it_should_extend_the_base_credit_card_class()
    {
        $this->shouldHaveType('Mage_Payment_Model_Method_Cc');
    }

    function let(\Magetdd_Magepay_Model_Payment_Adapter $apiAdapter)
    {
        $this->beConstructedWith(array('api_adapter' => $apiAdapter));
    }

    function it_should_have_a_payment_code()
    {
        $this->getCode()->shouldReturn('magetdd_magepay');
    }

    function it_can_autorize_payments()
    {
        $this->canAuthorize()->shouldReturn(true);
    }

    function it_can_void_payments(\Varien_Object $payment)
    {
        $this->canVoid($payment)->shouldReturn(true);
    }

    function it_can_capture_payments()
    {
        $this->canCapture()->shouldReturn(true);
    }

    function it_can_capture_partial()
    {
        $this->canCapturePartial()->shouldReturn(true);
    }

    function it_can_refund_payments()
    {
        $this->canRefund()->shouldReturn(true);
    }

    function it_can_refund_invoice_partial()
    {
        $this->canRefundPartialPerInvoice()->shouldReturn(true);
    }

    function it_can_be_used_in_a_multishipping_checkout()
    {
        $this->canUseForMultishipping()->shouldReturn(true);
    }

    function it_can_be_used_in_the_checkout()
    {
        $this->canUseCheckout()->shouldReturn(true);
    }

    function it_can_be_used_in_the_admin()
    {
        $this->canUseInternal()->shouldReturn(true);
    }

    function it_should_not_allow_to_save_a_credit_cart()
    {
        $this->canSaveCc()->shouldReturn(false);
    }

    function it_should_not_allow_recurring_profiles()
    {
        $this->canManageRecurringProfiles()->shouldReturn(false);
    }

    function it_should_not_allow_to_create_billing_agreements()
    {
        $this->canCreateBillingAgreement()->shouldReturn(false);
    }

    function it_cannot_fetch_the_transaction_information()
    {
        $this->canFetchTransactionInfo()->shouldReturn(false);
    }

    function it_cannot_review_a_payment(\Mage_Payment_Model_Info $payment)
    {
        $this->canReviewPayment($payment)->shouldReturn(false);
    }

    function it_should_have_a_form_block()
    {
        $this->getFormBlockType()->shouldReturn('payment/form_cc');
    }

    function it_should_have_a_info_block()
    {
        $this->getInfoBlockType()->shouldReturn('payment/info_cc');
    }

    function it_should_validate_a_credit_card_number()
    {
        $this->validateCreditCardNumber('4929876734881603')->shouldReturn(true);
    }

    function it_should_authorize_a_payment($apiAdapter,
                                           Mage_Sales_Model_Order_Payment $payment,
                                           \Mage_Sales_Model_Order $order)
    {
    $transData = array(
        'avsZip'            => 'M5B1M4',
        'avsAddress1'       => '211 Yonge Street',
        'avsAddress2'       => '6th Floor',
        'avsCity'           => 'Toronto',
        'avsState'          => 'ON',
        'avsName'           => 'Allan MacGregor',
        'avsCountryCode'    => 'CA',
        'orderID'           => '120000011',
        'amount'            => 50,
        'taxAmount'         => 12,
        'cardBrand'         => 'VI',
        'ccAccountNum'      => 4929529115304709,
        'ccExp'             => 1608,
        'ccCardVerifyNum'   => 675,
    );

    $order->addStatusHistoryComment(Argument::any())->willReturn(true);
    $payment->getOrder()->willReturn($order);
    $payment->setLastTransId(Argument::any())->willReturn(true);
    $payment->setTransactionId(Argument::any())->willReturn(true);
    $payment->setIsTransactionClosed(false)->willReturn(true);
    $payment->setTransactionAdditionalInfo(Argument::any(),Argument::type('Array'))->willReturn(true);

    $result = array(
        'transType'           => 'A',
        'merchantID'          => '780000213760',
        'terminalID'          => '001',
        'cardBrand'           => 'VI',
        'orderID'             => '0000000120000011',
        'txRefNum'            => '5418228FA9F356AF503FA6969AED4F85B8885468',
        'txRefIdx'            => 0,
        'respDateTime'        => '20140916074415',
        'procStatus'          => "0",
        'approvalStatus'      => "1",
        'respCode'            => '00',
        'avsRespCode'         => 'Z',
        'cvvRespCode'         => 'N',
        'authorizationCode'   => '097049',
        'procStatusMessage'   => 'Approved',
        'respCodeMessage'     => '',
        'hostRespCode'        => '00',
        'hostAVSRespCode'     => 'Z',
        'hostCVVRespCode'     => 'N'
    );

    $apiAdapter->generateTransactionData($payment)
        ->willReturn($transData)->shouldBeCalled();

    $apiAdapter->authorize($transData)->willReturn($result)->shouldBeCalled();

    $amount = 50; // Authorization amount for the order
    $this->authorize($payment, $amount)->shouldReturn($this);
}

function it_should_capture_a_payment($apiAdapter, Mage_Sales_Model_Order_Payment $payment)
{
    $transData = array(
        'amount'            => 50,
        'taxAmount'         => 12,
        'orderID'           => '0000000120000011',
        'bin'               => '000002',
        'merchantID'        => '780000213760',
        'terminalID'        => '001'
    );

    $result = array(
        'bin'               => '000002',
        'merchantID'        => '700000203790',
        'terminalID'        => '001',
        'orderID'           => '0000000100000012',
        'txRefNum'          => '5419D82D8C3E0BD7D1F743CA64250C95246954FE',
        'txRefIdx'          => '2',
        'splitTxRefIdx'     => '',
        'amount'            => '5000',
        'respDateTime'      => '20140918100120',
        'procStatus'        => '0',
        'procStatusMessage' => '',
    );

    $apiAdapter->generateTransactionData($payment)
        ->willReturn($transData)->shouldBeCalled();

    $apiAdapter->capture($transData)->willReturn($result)->shouldBeCalled();
    $apiAdapter->isPartialTransaction(Argument::type('array'))->willReturn(false);

    $amount = 50; // Amount to capture
    $this->capture($payment, $amount)->shouldReturn($this);
}

function it_should_void_a_payment($apiAdapter, Mage_Sales_Model_Order_Payment $payment)
{
    $transData = array(
        'orderID'           => '0000000100000012',
        'bin'               => '000002',
        'merchantID'        => '700000203790',
        'terminalID'        => '001',
    );

    $result = array(
        'bin'               => '000002',
        'merchantID'        => '700000203790',
        'terminalID'        => '001',
        'orderID'           => '0000000100000012',
        'txRefNum'          => '5421541C35988640372B6978F3268B1354265440',
        'txRefIdx'          => '2',
        'splitTxRefIdx'     => '',
        'respDateTime'      => '20140918100120',
        'procStatus'        => '0',
        'approvalStatus'    => '1',
        'procStatusMessage' => '',
        'retryTrace'        => '',
        'retryAttempCount'  => '',
        'lastRetryDate'     => '',
    );

    $apiAdapter->generateTransactionData($payment)
        ->willReturn($transData)->shouldBeCalled();

    $apiAdapter->void($transData)->willReturn($result)->shouldBeCalled();

    $this->void($payment)->shouldReturn($this);
}

function it_should_refund_a_payment($apiAdapter, Mage_Sales_Model_Order_Payment $payment)
{
    $transData = array(
        'orderID'           => '0000000100000012',
        'bin'               => '000002',
        'merchantID'        => '700000203790',
        'terminalID'        => '001',
        'txRefNum'          => '5421541C35988640372B6978F3268B1354265440',
        'amount'            => 20,
    );

    $result = array(
        'bin'               => '000002',
        'merchantID'        => '700000203790',
        'terminalID'        => '001',
        'orderID'           => '0000000100000012',
        'txRefNum'          => '5421541C35988640372B6978F3268B1354265440',
        'txRefIdx'          => '2',
        'splitTxRefIdx'     => '',
        'respDateTime'      => '20140918100120',
        'procStatus'        => '0',
        'approvalStatus'    => '1',
        'procStatusMessage' => '',
    );

    $apiAdapter->generateTransactionData($payment)
        ->willReturn($transData)->shouldBeCalled();

    $amount = 20;
    $apiAdapter->refund($transData)->willReturn($result,$amount)->shouldBeCalled();

    $this->refund($payment, $amount)->shouldReturn($this);
  }

  function it_should_cancel_a_payment($apiAdapter, Mage_Sales_Model_Order_Payment $payment)
  {
      $transData = array(
          'orderID'           => '0000000100000012',
          'bin'               => '000002',
          'merchantID'        => '700000203790',
          'terminalID'        => '001',
      );

      $result = array(
          'bin'               => '000002',
          'merchantID'        => '700000203790',
          'terminalID'        => '001',
          'orderID'           => '0000000100000012',
          'txRefNum'          => '5421541C35988640372B6978F3268B1354265440',
          'txRefIdx'          => '2',
          'splitTxRefIdx'     => '',
          'amount'            => '6200',
          'respDateTime'      => '20140918100120',
          'procStatus'        => '0',
          'procStatusMessage' => '',
      );

      $apiAdapter->generateTransactionData($payment)
          ->willReturn($transData)->shouldBeCalled();

      $apiAdapter->void($transData)->willReturn($result)->shouldBeCalled();

      $this->cancel($payment)->shouldReturn($this);
  }
}

/**
 * Class Mage_Sales_Model_Order_Payment
 * @package spec
 *
 * @method Mage_Sales_Model_Order_Payment getCcNumber()
 * @method Mage_Sales_Model_Order_Payment getCcCid()
 * @method string getCcType()
 * @method string getCcOwner()
 * @method string getCcExpYear()
 * @method string hasLastTransId()
 * @method string hasParentTransactionId()
 * @method string getParentTransactionId()
 * @method string getCcExpMonth()
 * @method Mage_Sales_Model_Order_Payment setTransactionId()
 * @method Mage_Sales_Model_Order_Payment setLastTransId()
 * @method Mage_Sales_Model_Order_Payment setTransactionAdditionalInfo()
 * @method Mage_Sales_Model_Order_Payment setIsTransactionClosed()
 */
class Mage_Sales_Model_Order_Payment extends \Mage_Sales_Model_Order_Payment {}
