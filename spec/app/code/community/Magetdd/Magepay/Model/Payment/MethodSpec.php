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

    // function let(\Demac_Chase_Model_Paymentech_Api_Adapter $apiAdapter,
    //              \Demac_Chase_Model_Adapter_DatabaseAdapter $databaseAdapter)
    // {
    //     $this->beConstructedWith(array('api_adapter' => $apiAdapter, 'database_adapter' => $databaseAdapter));
    // }

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
}
