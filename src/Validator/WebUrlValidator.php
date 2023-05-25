<?php

namespace Trexima\EuropeanCvBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class WebUrlValidator extends ConstraintValidator
{
    public const PATTERN = '~^
            (?:(%s)://)                                 # protocol
            (
                (?:xn--[a-z0-9-]++\.)*+xn--[a-z0-9-]++            # a domain name using punycode
                    |
                (?:[\pL\pN\pS\pM\-\_]++\.)+[\pL\pN\pM]++          # a multi-level domain name
            )
            (?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%%[0-9A-Fa-f]{2})* )*          # a path
            (?:\? (?:[\pL\pN\-._\~!$&\'\[\]()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?   # a query (optional)
            (?:\# (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?       # a fragment (optional)
        $~ixu';

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof WebUrl) {
            throw new UnexpectedTypeException($constraint, WebUrl::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string)$value;
        if ('' === $value) {
            return;
        }

        if (null !== $constraint->normalizer) {
            $value = ($constraint->normalizer)($value);
        }

        $pattern = $constraint->relativeProtocol ? \str_replace(
            '(?:(%s)://)',
            '(?:(%s)://)?',
            static::PATTERN
        ) : static::PATTERN;

        $pattern = \sprintf($pattern, \implode('|', $constraint->protocols));

        if (
            !\preg_match($pattern, $value)
            || ($constraint->pattern && !\preg_match($constraint->pattern, $value))
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(WebUrl::INVALID_URL_ERROR)
                ->addViolation();
        }
    }
}
