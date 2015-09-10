Feature: Customers can see all their previous transactions with MagePay
  As a logged in customer
  I need to be able to see all my transactions with MagePay
  When I go to My account page under transaction history

Scenario: View transaction history
  Given I log in as customer "amacgregor@magetdd.com" identified by "tddIsAwesome!"
    And I have at least one previous transaction
  When I go to "/magepay/"
  Then I should see the transaction grid

Scenario: The transaction grid shows each transaction information
  Given I log in as customer "amacgregor@magetdd.com" identified by "tddIsAwesome!"
    And I have at least one previous transaction
  When I go to "/magepay/"
  Then I should see the transaction grid
    And the grid shows a transaction "id"
    And the grid shows a transaction "state"
    And the grid shows a transaction "created_at"

Scenario: View transaction detailed information as a customer
  Given I log in as customer "amacgregor@magetdd.com" identified by "tddIsAwesome!"
    And I have at least one previous transaction
  When I go to "/magepay/"
    And click on the view details button
  Then I should see the transaction details page
