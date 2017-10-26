@adding_topic
Feature: Adding topic as a customer
    In order to share a topic with other customers
    As a Customer
    I want to be able to add topic

    Background:
        Given I am logged in as a customer

    Scenario: Adding topic as a customer
        Given I want to add topic
        When I leave a comment "Luke I am your father", titled "Important"
        And I add it
        Then I should be notified that it has been successfully created