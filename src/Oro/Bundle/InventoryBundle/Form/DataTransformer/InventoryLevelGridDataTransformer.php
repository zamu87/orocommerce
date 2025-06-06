<?php

namespace Oro\Bundle\InventoryBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Entity\ProductUnitPrecision;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class InventoryLevelGridDataTransformer implements DataTransformerInterface
{
    const PRECISION_KEY = 'precision';

    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * @var Product
     */
    protected $product;

    public function __construct(DoctrineHelper $doctrineHelper, Product $product)
    {
        $this->doctrineHelper = $doctrineHelper;
        $this->product = $product;
    }

    #[\Override]
    public function transform($value)
    {
        return $value;
    }

    #[\Override]
    public function reverseTransform($value)
    {
        if (!$value) {
            return new ArrayCollection();
        }

        if (!is_array($value) && !$value instanceof \Traversable) {
            throw new UnexpectedTypeException($value, 'array');
        }

        foreach ($value as $precisionId => $changeSetRow) {
            $precision = $this->getPrecision((int)$precisionId);

            if (!$precision) {
                continue;
            }

            $value[$precisionId] = array_merge(
                $changeSetRow,
                [self::PRECISION_KEY => $precision]
            );
        }

        return $value;
    }

    /**
     * @param int|string $id
     * @return ProductUnitPrecision|null
     */
    protected function getPrecision($id)
    {
        foreach ($this->product->getUnitPrecisions() as $precision) {
            if ($precision->getId() == $id) {
                return $precision;
            }
        }

        return null;
    }
}
