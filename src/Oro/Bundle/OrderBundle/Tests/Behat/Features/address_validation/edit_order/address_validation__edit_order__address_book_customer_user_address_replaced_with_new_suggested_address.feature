@fixture-OroOrderBundle:OrderAddressesFixture.yml
@fixture-OroUPSBundle:AddressValidationUpsClient.yml
@feature-BB-24101
@regression
@behat-test-env

Feature: Address Validation - Edit Order - Address Book Customer User Address Replaced With New Suggested Address
  As an Administrator
  I should be able to fill the address book form on order edit page with a new address when customer user save checkbox is not applied

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
    Given I go to Sales/ Orders
    And I click edit order1 in grid
    When I fill "Order Form" with:
      | Shipping Address | ORO, 801 Scenic Hwy, HAINES CITY FL US 33844 |
    Then I should see "Confirm Your Address - Address 1"
    When I click "Address Validation Result Form First Suggested Address Radio"
    And I click on "Use Selected Address Button"
    Then "Order Form" must contains values:
      | Shipping Address             | Enter other address |
      | Shipping Address Street      | 801 SCENIC HWY      |
      | Shipping Address City        | HAINES CITY 1       |
      | Shipping Address Postal Code | 33844-8562          |
      | Shipping Address State       | Florida             |

