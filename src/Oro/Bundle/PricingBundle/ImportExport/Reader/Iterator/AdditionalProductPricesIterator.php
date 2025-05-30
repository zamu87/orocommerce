<?php

namespace Oro\Bundle\PricingBundle\ImportExport\Reader\Iterator;

use Oro\Bundle\CurrencyBundle\Entity\Price;
use Oro\Bundle\PricingBundle\Entity\PriceList;
use Oro\Bundle\PricingBundle\Entity\ProductPrice;
use Oro\Bundle\ProductBundle\Entity\Product;

/**
 * Will return Product Prices for each of available currencies in each unit supported by product
 */
class AdditionalProductPricesIterator implements \Iterator
{
    /**
     * @var PriceList
     */
    protected $priceList;

    /**
     * @var \Iterator
     */
    protected $productIterator;

    /**
     * @var \Iterator|null
     */
    protected $additionalItemsIterator;

    /**
     * @var ProductPrice|null
     */
    protected $current;

    /**
     * @var mixed
     */
    protected $offset = -1;

    public function __construct(\Iterator $productIterator, PriceList $priceList)
    {
        $this->priceList = $priceList;
        $this->productIterator = $productIterator;
    }

    #[\Override]
    public function current(): mixed
    {
        return $this->current;
    }

    #[\Override]
    public function next(): void
    {
        $this->current = $this->read();
        if ($this->valid()) {
            ++$this->offset;
        }
    }

    #[\Override]
    public function key(): mixed
    {
        return $this->offset;
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->current !== null;
    }

    #[\Override]
    public function rewind(): void
    {
        $this->offset = -1;
        $this->current = null;
        $this->additionalItemsIterator = null;
        $this->productIterator->rewind();

        $this->next();
    }

    /**
     * Read next element from subordinate iterator, iterates to next element of main iterator when done.
     *
     * @return array|null
     */
    protected function read()
    {
        if (!$this->productIterator->valid()) {
            return null;
        }

        if (!$this->additionalItemsIterator) {
            $product = $this->productIterator->current();
            $this->additionalItemsIterator = $this->createAdditionalItemsIterator($product);
            if (!$this->additionalItemsIterator->valid()) {
                // Iterator could be already rewound
                $this->additionalItemsIterator->rewind();
            }
        }

        if (!$this->additionalItemsIterator->valid()) {
            // Read for next List
            $this->additionalItemsIterator = null;
            $this->productIterator->next();
            return $this->read();
        }

        $result = $this->additionalItemsIterator->current();
        $this->additionalItemsIterator->next();
        return $result;
    }

    /**
     * Creates additional items iterator.
     *
     * Will return Product Prices for each of available currencies in each unit supported by product
     *
     * @param Product $product
     * @return \Iterator
     */
    protected function createAdditionalItemsIterator(Product $product)
    {
        $data = [];
        foreach ($product->getUnitPrecisions() as $unitPrecision) {
            foreach ($this->priceList->getCurrencies() as $currency) {
                $data[] = (new ProductPrice())
                    ->setPriceList($this->priceList)
                    ->setProduct($product)
                    ->setUnit($unitPrecision->getUnit())
                    ->setPrice(Price::create(null, $currency));
            }
        }

        return new \ArrayIterator($data);
    }
}
