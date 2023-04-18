<?php

namespace Trexima\EuropeanCvBundle\Validator;

use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class NotNullUnless extends NotNull
{
    public string $propertyPath;
    public mixed $propertyValue;
    public bool $strict;

    public function __construct(
        string $propertyPath,
        mixed $propertyValue,
        bool $strict = false,
        string $message = null,
        array $groups = null,
        mixed $payload = null,
        array $options = null,
    ) {
        parent::__construct($options, $message, $groups, $payload);

        $this->propertyPath = $propertyPath;
        $this->propertyValue = $propertyValue;
        $this->strict = $strict;
    }
}
