@bills @compta @delete
Feature: Bill removal
  In order to manage book entries
  As a user from office
  I need to be able to delete a bill

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And there are payment methods:
      | name   |
      | chèque |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And there are customers:
      | company   |
      | Philibert |
      | Ludibay   |
    And there are products:
      | name        | price |
      | Sex Toy     | 10.00 |
      | Playstation | 15.99 |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Delete a bill
    Given there are bills:
      | company | payment_method |
      | Ludibay | chèque         |
    And bill from customer "Ludibay" has following products:
      | name        | quantity |
      | Sex Toy     | 1        |
      | Playstation | 1        |
    And I am on "/compta/facture"
    When I press "Supprimer"
    And I wait until modal is visible
    And I follow "Supprimer"
    Then I should be on "/compta/facture/"
    And I should not see "Ludibay"