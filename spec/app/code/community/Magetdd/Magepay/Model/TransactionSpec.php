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
}
