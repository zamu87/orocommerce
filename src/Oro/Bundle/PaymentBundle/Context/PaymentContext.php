<?php

namespace Oro\Bundle\PaymentBundle\Context;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Container of values that represents Payment Context
 */
class PaymentContext extends ParameterBag implements PaymentContextInterface
{
    const FIELD_CUSTOMER = 'customer';
    const FIELD_CUSTOMER_USER = 'customer_user';
    const FIELD_LINE_ITEMS = 'line_items';
    const FIELD_BILLING_ADDRESS = 'billing_address';
    const FIELD_SHIPPING_ADDRESS = 'shipping_address';
    const FIELD_SHIPPING_ORIGIN = 'shipping_origin';
    const FIELD_SHIPPING_METHOD = 'shipping_method';
    const FIELD_CURRENCY = 'currency';
    const FIELD_SUBTOTAL = 'subtotal';
    const FIELD_SOURCE_ENTITY = 'source_entity';
    const FIELD_SOURCE_ENTITY_ID = 'source_entity_id';
    const FIELD_WEBSITE = 'website';
    const FIELD_TOTAL = 'total';

    public function __construct(array $params)
    {
        parent::__construct($params);
    }

    #[\Override]
    public function getCustomer()
    {
        return $this->get(self::FIELD_CUSTOMER);
    }

    #[\Override]
    public function getCustomerUser()
    {
        return $this->get(self::FIELD_CUSTOMER_USER);
    }

    #[\Override]
    public function getLineItems()
    {
        return $this->get(self::FIELD_LINE_ITEMS);
    }

    #[\Override]
    public function getBillingAddress()
    {
        return $this->get(self::FIELD_BILLING_ADDRESS);
    }

    #[\Override]
    public function getShippingAddress()
    {
        return $this->get(self::FIELD_SHIPPING_ADDRESS);
    }

    #[\Override]
    public function getShippingOrigin()
    {
        return $this->get(self::FIELD_SHIPPING_ORIGIN);
    }

    #[\Override]
    public function getShippingMethod()
    {
        return $this->get(self::FIELD_SHIPPING_METHOD);
    }

    #[\Override]
    public function getCurrency()
    {
        return $this->get(self::FIELD_CURRENCY);
    }

    #[\Override]
    public function getSubtotal()
    {
        return $this->get(self::FIELD_SUBTOTAL);
    }

    #[\Override]
    public function getSourceEntity()
    {
        return $this->get(self::FIELD_SOURCE_ENTITY);
    }

    #[\Override]
    public function getSourceEntityIdentifier()
    {
        return $this->get(self::FIELD_SOURCE_ENTITY_ID);
    }

    #[\Override]
    public function getWebsite()
    {
        return $this->get(self::FIELD_WEBSITE);
    }

    #[\Override]
    public function getTotal()
    {
        return $this->get(self::FIELD_TOTAL);
    }
}
