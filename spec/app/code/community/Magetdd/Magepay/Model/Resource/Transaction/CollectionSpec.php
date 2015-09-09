<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Magetdd_Magepay_Model_Resource_Transaction_CollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Magetdd_Magepay_Model_Resource_Transaction_Collection');
    }

    function it_extends_the_resource_db_collection()
    {
      $this->shouldBeAnInstanceOf('Mage_Core_Model_Resource_Db_Collection_Abstract');
    }
}

abstract class Mage_Core_Model_Resource_Db_Collection_Abstract {
  public function __construct($resource = null)
  {
    return true;
  }
  protected function _init($mode, $resource) {
    return true;
  }
}
