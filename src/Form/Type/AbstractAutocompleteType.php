<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * Type rendering a ChoiceType with dynamically created choices appropriate for autocomplete.
 * Must be extended to implement {@link AbstractMappedAutocompleteType::createChoices()} method
 * that should dynamically create choices based on currently selected values
 */
abstract class AbstractAutocompleteType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setDataMapper($this)
            ->addEventListener(FormEvents::POST_SET_DATA, $this->postSetData(...))
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->preSubmit(...));
    }

    public function getBlockPrefix(): string
    {
        return 'autocomplete';
    }

    /**
     * Create autocomplete ChoiceType with options from current data
     */
    private function postSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $form->getData() ?? [];

        if (!$data instanceof \Traversable && !is_array($data)) {
            $data = [$data];
        }

        $this->addAutocomplete($form, $data);
    }

    /**
     * Create autocomplete ChoiceType with options from current submitted data
     */
    private function preSubmit(FormEvent $event): void
    {
        $form = $event->getForm();

        $submitData = $event->getData()['autocomplete'] ?? [];
        if (!\is_array($submitData)) {
            $submitData = [$submitData];
        }

        // Map data to native array
        $actualData = $form->getData() ?? [];
        if ($actualData instanceof Collection) {
            $actualData = \array_values($actualData->getValues());
        } elseif ($actualData instanceof \Traversable) {
            $mapped = [];
            foreach ($actualData as $actual) {
                $mapped[] = $actual;
            }

            $actualData = $mapped;
        } elseif (!\is_array($actualData)) {
            $actualData = [$actualData];
        }

        $options = $form->getConfig()->getOptions();
        $choices = $this->createChoices($actualData, $submitData, $options);
        $this->addAutocomplete($form, $choices);
    }

    private function addAutocomplete(FormInterface $form, iterable $choices): void
    {
        $parentOptions = $form->getConfig()->getOptions();

        $options = \array_merge($parentOptions, [
            'choices' => $choices,
            'error_bubbling' => true,
            'compound' => false,
            'auto_initialize' => false,
        ]);

        $this->preAddAutocomplete($options);

        $form->add('autocomplete', ChoiceType::class, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $emptyData = fn(Options $options) => $options['multiple'] ? [] : null;

        $placeholderDefault = function (Options $options) {
            return $options['required'] ? null : '';
        };

        $placeholderNormalizer = function (Options $options, $placeholder) {
            if ($options['multiple'] || false === $placeholder) {
                return null;
            }

            return $placeholder;
        };

        $choiceTranslationDomainNormalizer = function (Options $options, $choiceTranslationDomain) {
            if (true === $choiceTranslationDomain) {
                return $options['translation_domain'];
            }

            return $choiceTranslationDomain;
        };

        $classNormalizer = function (Options $options, $value) {
            if (!\class_exists($value)) {
                throw new \RuntimeException('Invalid class provided');
            }

            return $value;
        };

        $resolver
            ->setRequired('class')
            ->setDefaults([
                'multiple' => false,
                'error_bubbling' => false,
                'empty_data' => $emptyData,
                'choice_translation_domain' => false,
                'choice_label' => null,
                'choice_value' => null,
                'choice_attr' => null,
                'choice_name' => null,
                'placeholder' => $placeholderDefault,

                // Select2 options
                'select2' => false,
                'select2_theme' => 'worki',
                'select2_placeholder' => null,
                'select2_minimum_results_for_search' => 0,
                'select2_minimum_input_length' => 0,
                'select2_allow_clear' => false,
                'select2_no_results_message' => null,

                // Filters out already selected values from results when select2 is in multiple mode
                'select2_filter_selected_results' => true,
                'select2_autocomplete_url' => null,
            ])
            ->setAllowedTypes('multiple', 'bool')
            ->setAllowedTypes('choice_translation_domain', ['null', 'bool', 'string'])
            ->setAllowedTypes('choice_label', ['null', 'bool', 'callable', 'string', PropertyPath::class])
            ->setAllowedTypes('choice_value', ['null', 'callable', 'string', PropertyPath::class])
            ->setAllowedTypes('choice_attr', ['null', 'array', 'callable', 'string', PropertyPath::class])
            ->setAllowedTypes('choice_name', ['null', 'callable', 'string', PropertyPath::class])
            ->setAllowedTypes('select2', 'bool')
            ->setAllowedTypes('select2_theme', 'string')
            ->setAllowedTypes('select2_placeholder', ['string', 'null', TranslatableMessage::class])
            ->setAllowedTypes('select2_minimum_results_for_search', 'int')
            ->setAllowedTypes('select2_minimum_input_length', 'int')
            ->setAllowedTypes('select2_allow_clear', ['bool', 'string'])
            ->setAllowedTypes('select2_no_results_message', ['string', 'null', TranslatableMessage::class])
            ->setAllowedTypes('select2_filter_selected_results', ['bool', 'string'])
            ->setAllowedTypes('select2_autocomplete_url', ['string', 'null'])
            ->setAllowedTypes('class', 'string')
            ->setNormalizer('placeholder', $placeholderNormalizer)
            ->setNormalizer('choice_translation_domain', $choiceTranslationDomainNormalizer)
            ->setNormalizer('class', $classNormalizer);
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        // Must be overridden during rendering for a template to render correctly
        // This type is actually not rendered, but only its child - autocomplete
        $view->vars['compound'] = false;
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        $forms = \iterator_to_array($forms);

        if (null === $viewData) {
            $forms['autocomplete']->setData(null);
            return;
        }

        // Make sure it's an array and not a collection
        if ($viewData instanceof Collection) {
            $viewData = $viewData->toArray();
        }

        $forms['autocomplete']->setData($viewData);
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        $forms = \iterator_to_array($forms);
        $viewData = $forms['autocomplete']->getData();
    }

    /**
     * A method that can be used to further update options assigned to ChoiceType,
     * e.g. as a cleanup to remove custom options
     */
    protected function preAddAutocomplete(array &$options): void
    {
        unset($options['class'], $options['data_class']);
    }

    /**
     * Method that should retrieve all data needed for currently
     * selected options and use them as choices
     */
    abstract protected function createChoices(array $actualData, array $submitData, array $options): array;
}
