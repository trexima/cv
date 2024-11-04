<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;

/**
 * This choice loader allow submitted values to be used in choice type.
 * WARNING: This can be security issue. Perform manual check of submitted values.
 */
class Select2ChoiceLoader implements ChoiceLoaderInterface
{
    /**
     * Loads a list of choices.
     *
     * Optionally, a callable can be passed for generating the choice values.
     * The callable receives the choice as only argument.
     * Null may be passed when the choice list contains the empty value.
     *
     * @param callable|null $value The callable which generates the values
     *                             from choices
     *
     * @return ChoiceListInterface The loaded choice list
     */
    public function loadChoiceList($value = null): ChoiceListInterface
    {
        return new ArrayChoiceList([], $value);
    }

    /**
     * Loads the choices corresponding to the given values.
     *
     * The choices are returned with the same keys and in the same order as the
     * corresponding values in the given array.
     *
     * Optionally, a callable can be passed for generating the choice values.
     * The callable receives the choice as only argument.
     * Null may be passed when the choice list contains the empty value.
     *
     * @param string[] $values An array of choice values. Non-existing
     *                              values in this array are ignored
     * @param callable|null $value The callable generating the choice values
     *
     * @return array An array of choices
     */
    public function loadChoicesForValues(array $values, $value = null): array
    {
        // Ignore placeholder and allow Symfony to handle empty_data
        return array_filter($values, function ($value) {
            return $value !== '';
        });
    }

    /**
     * Loads the values corresponding to the given choices.
     *
     * The values are returned with the same keys and in the same order as the
     * corresponding choices in the given array.
     *
     * Optionally, a callable can be passed for generating the choice values.
     * The callable receives the choice as only argument.
     * Null may be passed when the choice list contains the empty value.
     *
     * @param array $choices An array of choices. Non-existing choices in
     *                               this array are ignored
     * @param callable|null $value The callable generating the choice values
     *
     * @return string[] An array of choice values
     */
    public function loadValuesForChoices(array $choices, $value = null): array
    {
        return $choices;
    }
}
