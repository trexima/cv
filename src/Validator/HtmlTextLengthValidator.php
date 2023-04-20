<?php

namespace Trexima\EuropeanCvBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\LengthValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates length of a text stripped from any HTML tags
 */
class HtmlTextLengthValidator extends LengthValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof HtmlTextLength) {
            throw new UnexpectedTypeException($constraint, HtmlTextLength::class);
        }

        if (null === $value) {
            return;
        }

        $value = \html_entity_decode(\preg_replace('/<[^<]*>/', '', $value));

        parent::validate($value, $constraint);
    }
}
