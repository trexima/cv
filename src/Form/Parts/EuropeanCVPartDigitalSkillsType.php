<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;

/**
 * Digital skills
 */
class EuropeanCVPartDigitalSkillsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('digitalSkills', Select2Type::class, [
                'label' => 'Digitálne zručnosti',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'multiple' => true,
                'choices' => $this->getDigitalSkillsArray(),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCV::class,
            'is_user_logged_in' => false,
            'translation_domain' => 'trexima_european_cv',
            'sex_required' => false,
            'phones_min' => 0,
            'practices_min' => 0,
            'educations_min' => 0,
            'languages_min' => 0,
            'additional_informations_min' => 0,
            'attachments_min' => 0
         ]);

        $resolver->setRequired([
            'photo_upload_route'
        ]);
    }

    private function getDigitalSkillsArray() {
        $digitalSkillList = Yaml::parseFile(__DIR__.'/../../../Resources/listing/digital_skill.yaml');
        $digitalSkills = [];
        $skillCategories = [];
        foreach ($digitalSkillList as $itemId => $item) {
            if ($item['level'] === 1) $skillCategories[$itemId] = $item['label'];
        }

        foreach ($digitalSkillList as $itemId => $item) {
            if ($item['level'] === 2) $digitalSkills[$skillCategories[$item['parent']]][$item['label']] = $itemId;
        }
        
        return $digitalSkills;
    }
}