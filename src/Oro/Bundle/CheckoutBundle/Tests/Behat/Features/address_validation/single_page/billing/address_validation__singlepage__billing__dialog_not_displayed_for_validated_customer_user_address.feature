@fixture-OroUPSBundle:AddressValidationUpsClient.yml
@fixture-OroFlatRateShippingBundle:FlatRateIntegration.yml
@fixture-OroPaymentTermBundle:PaymentTermIntegration.yml
@fixture-OroCheckoutBundle:address_validation/AddressValidationCheckoutFixture.yml
@fixture-OroCheckoutBundle:address_validation/ValidatedAddressBookAddresses.yml
@behat-test-env
@feature-BB-24101
@regression

Feature: Address Validation - Single-Page - Billing - Dialog Not Displayed For Validated Customer User Address
  As an Buyer
  I should see not see Address Validation modal for already validated customer user address

  Scenario: Feature Background
    Given I login as administrator
    And I activate "Single Page Checkout" workflow
    And I go to System/ Configuration
    And follow "Commerce/Shipping/Address Validation" on configuration sidebar
    When I fill "Address Validation Configuration Form" with:
      | Address Validation Service Use Default | false |
      | Address Validation Service             | UPS   |
    And I submit form
    Then I should see "Configuration saved" flash message
    When I fill "Address Validation Configuration Checkout Form" with:
      | Validate Billing Addresses During Checkout Use Default  | false |
      | Validate Billing Addresses During Checkout              | true  |
      | Validate Shipping Addresses During Checkout Use Default | false |
      | Validate Shipping Addresses During Checkout             | false |
    And I submit form
    Then I should see "Configuration saved" flash message

  Scenario: Update selected customer address with suggested
    Given I signed in as AmandaRCole@example.org on the store frontend
    When I open page with shopping list List 1
    And I click "Create Order"
    And I click on "Billing Address Select"
    And I select "ORO, 805 Scenic Hwy, HAINES CITY FL US 33844" from "Billing Address"
    And I click "Submit Order"
    Then I should see "Thank You For Your Purchase!"
