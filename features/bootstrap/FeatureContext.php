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
        throw new PendingException();
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
