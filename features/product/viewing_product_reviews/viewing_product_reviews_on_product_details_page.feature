@viewing_product_reviews
Feature: Viewing product reviews on product's details page
    In order to know other customer's opinion about product
    As a Customer
    I want to read product reviews on product's details page

    Background:
        Given there is a product "Necronomicon"
        And there is a customer with email "h.p.lovecraft@arkham.com"
        And there is a customer with email "robert.e.howard@conan.com"
        And there is a customer with email "jrr.tolkien@middle-earth.com"
        And there is a customer with email "sir.terry@pratchett.com"
        And this product has a review titled "Great book" and rated 5 added by customer "h.p.lovecraft@arkham.com", created 3 days ago
        And this product has also a review titled "Scary and dark" and rated 4 added by customer "robert.e.howard@conan.com"
        And this product has also a review titled "Too gloomy" and rated 3 added by customer "jrr.tolkien@middle-earth.com"
        And this product has also a review titled "Classic" and rated 5 added by customer "sir.terry@pratchett.com"
        And I am logged in as a customer

    @ui
    Scenario: Viewing last 3 product reviews on product's details page
        When I check this product's details
        Then I should see 3 product reviews
        And I should see reviews titled "Classic", "Too gloomy" and "Scary and dark"
        But I should not see review titled "Good book"
