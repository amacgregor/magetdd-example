<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Magetdd_Magepay_Block_Transaction_GridSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Magetdd_Magepay_Block_Transaction_Grid');
    }

    function let(\Magetdd_Magepay_Model_Adapter_Magento $mageAdapter)
    {
      $this->beConstructedWith(array('database_adapter' => $mageAdapter));
    }

    function it_can_retrieve_a_customer_transactions(\Magetdd_Magepay_Model_Resource_Transaction_Collection $collection, $mageAdapter)
    {
        $collection->addFieldToFilter('customer_id',1)->willReturn($collection);
        $mageAdapter->getCurrentCustomerId()->willReturn(1);
        $mageAdapter->getModelCollection('magetdd_magepay/transaction')->willReturn($collection);
        $this->getTransactions()->shouldReturnAnInstanceOf('Magetdd_Magepay_Model_Resource_Transaction_Collection');
    }
}
