<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Magetdd_Magepay_Model_TransactionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Magetdd_Magepay_Model_Transaction');
    }

    function it_should_have_a_transaction_id()
    {
        $this->getTransactionId()->shouldReturn(99999999999);
    }

    function it_should_have_an_order_id()
    {
        $this->getOrderId()->shouldReturn(888888888888);
    }

    function it_should_have_a_state()
    {
        $this->getState()->shouldReturn('processing');
    }

    function it_should_have_a_created_at_date()
    {
        $this->getCreatedAt()->shouldReturn('2015-09-09');
    }
}
