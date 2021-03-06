@adding_game_play_comments
Feature: Adding game play comments
    In order to react on articles
    As a Visitor
    I want to be able to add a comment

    Background:
        Given there is a product "Puerto Rico"
        And this product has one game play from customer "kevin@example.com"
        And I am a logged in customer

    @ui
    Scenario: Adding comment to a game play
        Given I want to react on this game play
        When I leave a comment "Great article for every boardgame players."
        And I add it
        Then I should be notified that it has been successfully created
