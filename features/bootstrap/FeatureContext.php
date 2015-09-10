<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
 use MageTest\MagentoExtension\Context\MagentoContext;
 use Behat\Behat\Tester\Exception\PendingException;
 use \Mage;

 class FeatureContext extends MagentoContext implements Context, SnippetAcceptingContext
 {
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I have at least one previous transaction
     */
    public function iHaveAtLeastOnePreviousTransaction()
    {
      $customer = \Mage::getModel("customer/customer");
      $customer->setWebsiteId(\Mage::app()->getStore()->getWebsiteId());
      $customer->loadByEmail('amacgregor@magetdd.com');

      if(!$customer->hasEntityId()){
        throw new RuntimeException('Test Customer does not exists.');
      }

      $transactionCollection = \Mage::getModel('magetdd_magepay/transaction')
        ->getCollection()
        ->addFieldToFilter('customer_id', $customer->getEntityId());

      if($transactionCollection->getSize() == 0) {
        $data = array(
          'transaction_id' => 99999999999,
          'order_id'       => 888888888888,
          'state'          => 'processing',
          'created_at'     => '2015-09-09',
          'customer_id'    =>  $customer->getEntityId(),
        );

        $model = \Mage::getModel('magetdd_magepay/transaction');
        $model->setData($data);
        $model->save();

        return true;
      }
    }

    /**
     * @Then I should see the transaction grid
     */
    public function iShouldSeeTheTransactionGrid()
    {
        throw new PendingException();
    }

    /**
     * @Then the grid shows a transaction :arg1
     */
    public function theGridShowsATransaction($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When click on the view details button
     */
    public function clickOnTheViewDetailsButton()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see the transaction details page
     */
    public function iShouldSeeTheTransactionDetailsPage()
    {
        throw new PendingException();
    }

}
