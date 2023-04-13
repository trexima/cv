<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\DrivingLicenseEnum;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVDrivingLicense;

/**
 * Specialized checkboxes for selecting driving licenses.
 */
class DrivingLicenseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $drivingLicenses = DrivingLicenseEnum::cases();
        /**
         * Prefill form with all available driving licenses
         */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($drivingLicenses, $options) {
            $event->stopPropagation(); // Prevent CollectionType default behaviour

            $form = $event->getForm();
            $data = $event->getData();

            if (null === $data) {
                $data = [];
            }

            if (!is_array($data) && !($data instanceof Collection)) {
                throw new UnexpectedTypeException($data, 'Collection');
            }

            // First remove all rows
            foreach ($form as $name => $child) {
                $form->remove($name);
            }

            // Then add all rows again in the correct order
            foreach ($drivingLicenses as $drivingLicense) {
                $actualDrivingLicenseData = null;
                foreach ($data as $drivingLicenseData) {
                    if ($drivingLicenseData->getDrivingLicense() === $drivingLicense) {
                        $actualDrivingLicenseData = $drivingLicenseData;
                        break;
                    }
                }

                $name = 'driving_license_'.$drivingLicense->value;
                $form->add(
                    $name,
                    $options['entry_type'],
                    array_replace([
                        'label' => $drivingLicense->getTitle(),
                        'property_path' => '['.$name.']',
                        'driving_license' => $drivingLicense,
                        'data' => $actualDrivingLicenseData,
                        'help' => '',
                        'attr' => [
                            'class' => 'form-inline'
                        ],
                        'existing_licenses' => $options['existing_licenses']
                    ], $options['entry_options'])
                );
            }
        }, 1); // We need greater priority than in CollectionType

        /**
         * Delete empty collections. Option 'delete_empty' with callback is allowed from Symfony 3.
         */
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
            $data = $event->getData();

            $toDelete = [];
            /**
             * @var string $name
             * @var EuropeanCVDrivingLicense $child
             */
            foreach ($data as $name => $child) {
                /**
                 * Field driving_license_id is primary key. Rows without primary keys aren't allowed.
                 * And we don't want row without checked driving license!
                 */
                if (!$child->getDrivingLicense()) {
                    $toDelete[] = $name;
                }
            }

            foreach ($toDelete as $name) {
                unset($data[$name]);
            }

            $event->setData($data);
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['driving_licenses'] = DrivingLicenseEnum::cases();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'allow_add' => false,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_options' => [
                'required' => false,
            ],
            'existing_licenses' => []
        ]);
    }

    public function getParent(): ?string
    {
        return CollectionType::class;
    }
}