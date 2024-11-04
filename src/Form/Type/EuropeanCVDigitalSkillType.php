<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\DigitalSkillLevelEnum;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVDigitalSkill;

use function Symfony\Component\Translation\t;

class EuropeanCVDigitalSkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pcSkill', ChoiceType::class, [
                'label' => t('trexima_european_cv.form_label.digital_skill_title', [], 'trexima_european_cv'),
                'placeholder' => t('trexima_european_cv.form_placeholder.digital_skill_title', [], 'trexima_european_cv'),
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'choices' => $this->getDigitalSkillsArray(),
            ])
            ->add('level', EnumType::class, [
                'class' => DigitalSkillLevelEnum::class,
                'required' => true,
                'label' => t('trexima_european_cv.form_label.digital_skill_level', [], 'trexima_european_cv'),
                'multiple' => false,
                'choice_label' => fn (DigitalSkillLevelEnum $choice) => match ($choice) {
                    default => t('trexima_european_cv.form_label.digital_skill_level_'.strtolower($choice->value), [], 'trexima_european_cv'),
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCVDigitalSkill::class,
        ]);
    }

    private function getDigitalSkillsArray()
    {
        $digitalSkillList = [];
        $digitalSkills = [];
        $skillCategories = [];
        foreach ($digitalSkillList as $itemId => $item) {
            if (1 === $item['level']) {
                $skillCategories[$itemId] = $item['label'];
            }
        }

        foreach ($digitalSkillList as $itemId => $item) {
            if (2 === $item['level']) {
                $digitalSkills[$skillCategories[$item['parent']]][$item['label']] = $item['label'];
            }
        }

        return $digitalSkills;
    }
}
