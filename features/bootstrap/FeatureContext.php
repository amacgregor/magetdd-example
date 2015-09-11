<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\AfterStepScope;
/**
 * Defines application features from the specific context.
 */
 use MageTest\MagentoExtension\Context\MagentoContext;
 use Behat\Behat\Tester\Exception\PendingException;

 class FeatureContext extends MagentoContext implements Context, SnippetAcceptingContext
 {

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
        $page = $this->getSession()->getPage();
        $el   = $page->find('css', '.transaction-grid');
        if (!$el) {
            throw new RuntimeException('Grid not found');
        }
    }

    /**
     * @Then the grid shows a transaction :column_id
     */
    public function theGridShowsATransaction($column_id)
    {
        $page = $this->getSession()->getPage();
        $el   = $page->find('css', "th.{$column_id}");
        if (!$el) {
            throw new RuntimeException('Column not found');
        }
    }

    /**
     * @When click on the view details button
     */
    public function clickOnTheViewDetailsButton()
    {
        $page = $this->getSession()->getPage();
        $el   = $page->find('named', array('link', 'View Transaction'));
        if (!$el) {
            throw new RuntimeException('View transaction link not found.');
        }
        $el->press();
    }

    /**
     * @Then I should see the transaction details page
     */
    public function iShouldSeeTheTransactionDetailsPage()
    {
      $page = $this->getSession()->getPage();
      $el   = $page->find('css', '.page-title h1');
      if ($el->getText() != 'TRANSACTION DETAILS') {
          throw new RuntimeException('The controller action is not secure');
      }
    }

    /**
     * @Then I should be redirected to the login page
     */
    public function iShouldBeRedirectedToTheLoginPage()
    {
        $page = $this->getSession()->getPage();
        $el   = $page->find('css', '.page-title h1');
        if ($el->getText() != 'LOGIN OR CREATE AN ACCOUNT') {
            throw new RuntimeException('The controller action is not secure');
        }
    }
}
