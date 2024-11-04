<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * A modification of {@link AbstractAutocompleteType} that uses custom mapping via 'choice_property_mapper' option,
 * which maps data between objects and data retrieved by different means (e.g. Harvey) using
 * {@link AbstractMappedAutocompleteType::retrieveDataForValue()} method and finally uses that result as choices
 */
abstract class AbstractMappedAutocompleteType extends AbstractAutocompleteType implements DataMapperInterface
{
    protected PropertyAccessorInterface $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                // Keys represent a property path of an object and
                // value is a property path of a remote resource
                // Used when creating a new choice when copying data from remote resource
                'choice_property_mapper' => [],
            ])
            ->setAllowedTypes('choice_property_mapper', ['array']);
    }

    protected function createChoices(array $actualData, array $submitData, array $options): array
    {
        $externalData = \array_map(fn ($it) => $this->retrieveDataForValue($it), $submitData);
        $externalData = \array_filter($externalData);

        // Map data to desired type, either create a new instance or update an existing old value
        $maxIndex = \max(\count($externalData), \count($actualData)) - 1;
        for ($i = $maxIndex; $i >= 0; $i--) {
            $old = $actualData[$i] ?? null;
            $externalValue = $externalData[$i] ?? null;

            if (null === $externalValue) {
                \array_splice($actualData, $i, 1);
            } else {
                $this->createChoice($old, $externalValue, $options);
                $actualData[$i] = $old;
            }
        }

        return $actualData;
    }


    /**
     * Method is called whenever a choice needs to be created.
     * By default uses specified option 'choice_property_mapper'
     *
     * @param mixed $data Can also be nullable
     * @param mixed $retrievedData Contains data retrieved for a concrete selected option, non-null
     * @param array $options Current form options
     * @return void
     */
    protected function createChoice(mixed &$data, mixed $retrievedData, array $options): void
    {
        $cls = $options['class'];

        if (null === $data) {
            $data = new $cls();
        }

        foreach ($options['choice_property_mapper'] as $k => $v) {
            if ($this->propertyAccessor->isWritable($data, $k)) {
                $this->propertyAccessor->setValue($data, $k, $retrievedData[$v]);
            }
        }
    }

    protected function preAddAutocomplete(array &$options): void
    {
        parent::preAddAutocomplete($options);
        unset($options['choice_property_mapper']);
    }

    /**
     * Method that should retrieve all data needed for currently selected option
     * and used within {@link createChoice()} method
     */
    abstract protected function retrieveDataForValue(?string $value): mixed;
}
