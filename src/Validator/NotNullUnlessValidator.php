<?php

namespace Trexima\EuropeanCvBundle\Validator;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotNullValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NotNullUnlessValidator extends NotNullValidator
{
    private ?PropertyAccessorInterface $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NotNullUnless) {
            throw new UnexpectedTypeException($constraint, NotNullUnless::class);
        }

        $object = $this->context->getObject();
        if (null === $object) {
            return;
        }

        $propertyValue = $this->getPropertyAccessor()->getValue($object, $constraint->propertyPath);

        $shouldNotValidate = ($constraint->strict && $constraint->propertyValue === $propertyValue)
            || (!$constraint->strict && $constraint->propertyValue == $propertyValue);

        if ($shouldNotValidate) {
            return;
        }

        parent::validate($value, $constraint);
    }

    public function getPropertyAccessor(): PropertyAccessorInterface
    {
        if (null === $this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }
}
