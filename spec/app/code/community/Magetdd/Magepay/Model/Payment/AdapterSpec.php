<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Magetdd_Magepay_Model_Payment_AdapterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Magetdd_Magepay_Model_Payment_Adapter');
    }

    function it_should_be_able_to_generate_the_transaction_data(Mage_Sales_Model_Order_Payment $payment,
                                                               \Mage_Sales_Model_Order $order,
                                                               \Mage_Sales_Model_Order_Address $billingAddress)
    {
       $billingAddress->getCity()->willReturn('Toronto');
       $billingAddress->getStreet()->willReturn(array('71 King Street', 'Suite 300'));
       $billingAddress->getRegion()->willReturn('Ontario');
       $billingAddress->getRegionCode()->willReturn('ON');
       $billingAddress->getRegionId()->willReturn('12');
       $billingAddress->getPostcode()->willReturn('M5C1G4');
       $billingAddress->getCountryId()->willReturn('CA');
       $billingAddress->getTelephone()->willReturn('+1 647 466 7497');

       $order->getRemoteIp()->willReturn('196.162.33.94');
       $order->getIncrementId()->willReturn('120000011');
       $order->getCustomerName()->willReturn('Allan MacGregor');
       $order->getBaseTaxAmount()->willReturn(12.00);
       $order->getBaseGrandTotal()->willReturn(59.00);
       $order->getBillingAddress()->willReturn($billingAddress);

       $payment->getOrder()->willReturn($order);
       $payment->getCcCid()->willReturn('675');
       $payment->getCcType()->willReturn('VI');
       $payment->getCcNumber()->willReturn('4532197081728548');
       $payment->getCcExpYear()->willReturn('16');
       $payment->getCcExpMonth()->willReturn('08');
       $payment->hasLastTransId()->willReturn(true);
       $payment->hasParentTransactionId()->willReturn(true);
       $payment->getParentTransactionId()->willReturn('');
       $payment->getData("last_trans_id")->willReturn('5419D82D8C3E0BD7D1F743CA64250C95246954FE');

       $result = $this->generateTransactionData($payment);

       $result->shouldHaveKey('ccCardVerifyPresenceInd');
       $result->shouldHaveValue('ON');
       $result->shouldHaveCount(17);
    }

    /**
     * @return array
     */
    public function getMatchers()
    {
        return array(
            'beApproved' => function($subject) {
                    return $subject['approvalStatus'];
                },
            'beProcessing' => function($subject) {
                    return $subject['procStatus'] == 0;
                },
            'haveKey' => function($subject, $key) {
                    return array_key_exists($key, $subject);
                },
            'haveValue' => function($subject, $value) {
                    return in_array($value, $subject);
                },
        );
    }
}
