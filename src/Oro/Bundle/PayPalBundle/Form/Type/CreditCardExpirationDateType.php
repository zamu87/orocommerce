<?php

namespace Oro\Bundle\PayPalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditCardExpirationDateType extends AbstractType
{
    const NAME = 'oro_paypal_credit_card_expiration_date';
    const YEAR_PERIOD = 10;

    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('day');
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver)
    {
        $years = range(date('y'), date('y') + self::YEAR_PERIOD);

        $months = array_map(function ($value) {
            return sprintf('%02d', $value);
        }, range(1, 12));

        $resolver->setDefaults(
            [
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'format' => 'dMy',
                'input' => 'array',
                'years' => $years,
                'months' => $months
            ]
        );

        $resolver->setAllowedValues('input', ['array']);
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return self::NAME;
    }

    #[\Override]
    public function getParent(): ?string
    {
        return DateType::class;
    }
}
