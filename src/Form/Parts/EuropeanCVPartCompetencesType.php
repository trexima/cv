<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Trexima\EuropeanCvBundle\Entity\Enum\CompetenceEnum;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;

/**
 * Competences
 */
class EuropeanCVPartCompetencesType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competences', Select2Type::class, [
                'label' => 'Všeobecné spôsobilosti a predpoklady',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'multiple' => true,
                'choices' => $this->getCompetencesArray(),
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

    private function getCompetencesArray() {
        $competences = [];
        foreach (CompetenceEnum::cases() as $competence) {
            $competences[$competence->value] = $competence->value;
        }

        return $competences;
    }
}