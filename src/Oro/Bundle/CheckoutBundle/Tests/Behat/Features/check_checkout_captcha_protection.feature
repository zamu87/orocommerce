@regression
@behat-test-env
@ticket-BB-25175
@fixture-OroFlatRateShippingBundle:FlatRateIntegration.yml
@fixture-OroPaymentTermBundle:PaymentTermIntegration.yml
@fixture-OroCheckoutBundle:Checkout.yml

Feature: Check checkout captcha protection

  Scenario: Feature Background
    Given sessions active:
      | Admin | first_session  |
      | Buyer | second_session |

  Scenario: Open Shopping List to check that button without loaded without verification form is protected
    Given I proceed as the Buyer
    And I signed in as AmandaRCole@example.org on the store frontend
    And I open page with shopping list List 1

  Scenario: Enable CAPTCHA protection
    Given I proceed as the Admin
    And I login as administrator
    When I go to System / Configuration
    And I follow "System Configuration/Integrations/CAPTCHA Settings" on configuration sidebar

    And uncheck "Use default" for "Enable CAPTCHA protection" field
    And I check "Enable CAPTCHA protection"

    And uncheck "Use default" for "CAPTCHA service" field
    And I fill in "CAPTCHA service" with "Dummy"

    And uncheck "Use default" for "Protect Forms" field
    And I check "Start Checkout Form"

    And I submit form
    Then I should see "Configuration saved" flash message

  Scenario: Check CAPTCHA protection Shopping List Checkout for pre-opened page
    Given I proceed as the Buyer
    When I click "Create Order"
    Then I should see "Verification failed: Your submission did not pass the anti-bot check. Please try again or contact support if you continue to experience issues." flash message

  Scenario: Check CAPTCHA protection Shopping List
    When I open page with shopping list List 1
    And I click "Create Order"
    Then I should see "Additional Verification"
    And I should see "Please complete the CAPTCHA challenge to confirm that you are not a robot. This simple step helps ensure an enjoyable browsing experience for genuine users."
    When I click "Continue"
    Then I should see "Verification failed: Your submission did not pass the anti-bot check. Please try again or contact support if you continue to experience issues."

    When I fill in "Checkout Captcha Field" with "valid"
    And I click "Continue"
    Then Page title equals to "Billing Information - Checkout"
