<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Select2 with description in options.
 */
class Select2Type extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['ajax_route_path']) {
            $values = $view->vars['value'];
            if (!$options['multiple']) {
                // Ignore empty value
                $values = '' === $values ? [] : [$values];
            }

            $choices = [];
            $choiceLabel = $options['choice_label'];
            foreach ($values as $key => $entry) {
                // Data isn't used anywhere, it is only required to exists ChoiceView with value that will be rendered in HTML
                $choice = new ChoiceView($entry, $entry, $choiceLabel($entry));
                $choices[] = $choice;
            }
            $view->vars['choices'] = $choices;

            $view->vars['attr']['data-ajax--url'] = $options['ajax_route_path'];
        }

        if ($options['choices_description_callback']) {
            $view->vars['choices_description'] = $options['choices_description_callback']($form->getName());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'expanded' => false,
            'choices_description_callback' => null,
            'ajax_route_path' => null,
            'ajax_route_params' => [],
        ]);
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
