@fixture-OroUPSBundle:AddressValidationUpsClient.yml
@fixture-OroFlatRateShippingBundle:FlatRateIntegration.yml
@fixture-OroPaymentTermBundle:PaymentTermIntegration.yml
@fixture-OroSaleBundle:GuestQuoteWithAddressFixture.yml
@feature-BB-24101
@regression
@behat-test-env

Feature: Address Validation - Edit Quote - Select Suggested Address For Not Address Book Address
  As an Administrator
  I should be able to validate and replace not address book address with the first suggested

  Scenario: Feature Background
    Given I login as administrator
    And I go to System/ Configuration
    And follow "Commerce/Shipping/Address Validation" on configuration sidebar
    When I fill "Address Validation Configuration Form" with:
      | Address Validation Service Use Default | false |
      | Address Validation Service             | UPS   |
    And I submit form
    Then I should see "Configuration saved" flash message

  Scenario: Replace entered address with suggested
    Given I go to Sales/ Quotes
    And I click edit PO1 in grid
    When I fill "Quote Form" with:
      | Shipping Address        | Enter other address |
      | Shipping Address Street | 801 Scenic Hwy      |
    And I click "Submit"
    Then I should see "Confirm Your Address - Address 1"
    When I click "Address Validation Result Form First Suggested Address Radio"
    And I click on "Use Selected Address Button"
    When I click "Save" in modal window
    Then I should see "Quote #Quote_1 successfully updated" flash message
    When I click "Edit"
    And "Quote Form" must contains values:
      | Shipping Address Street      | 801 SCENIC HWY |
      | Shipping Address City        | HAINES CITY 1  |
      | Shipping Address Postal Code | 33844-8562     |
      | Shipping Address State       | Florida        |

