@fixture-OroUPSBundle:AddressValidationUpsClient.yml
@fixture-OroFlatRateShippingBundle:FlatRateIntegration.yml
@fixture-OroPaymentTermBundle:PaymentTermIntegration.yml
@fixture-OroCheckoutBundle:address_validation/AddressValidationCheckoutFixture.yml
@behat-test-env
@feature-BB-24101
@regression

Feature: Address Validation - Single-Page - Shipping - Dialog Displayed With No Customer User Update Checkbox
  As an Buyer
  I should see that customer user update checkbox are not displayed for suggested address
  when user does not have enough permissions on single step checkout for shipping address

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
    When I go to Customers/ Customer User Roles
    And click edit "Buyer" in grid
    And select following permissions:
      | Customer User Address | Edit:None |
    And I save form
    Then I should see "Customer User Role has been saved" flash message

  Scenario: Select suggested address in address validation modal
    Given I signed in as AmandaRCole@example.org on the store frontend
    When I open page with shopping list List 1
    And I click "Create Order"
    And I click on "Shipping Address Select"
    And I select "ORO, Fifth avenue, 10115 Berlin, Germany" from "Shipping Address"
    And I click "Submit Order"
    Then I should see "Confirm Your Address"
    When I click "Address Validation Result Form First Suggested Address Radio Storefront"
    Then I should not see "Update Address"
