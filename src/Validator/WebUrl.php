<?php

namespace Trexima\EuropeanCvBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class WebUrl extends Constraint
{
    public const INVALID_URL_ERROR = '57c2f299-1154-4870-89bb-ef3b1f5ad229';

    protected const ERROR_NAMES = [
        self::INVALID_URL_ERROR => 'INVALID_URL_ERROR',
    ];

    public string $message = 'This value is not a valid URL.';
    public ?string $pattern = null;
    public array $protocols = ['http', 'https'];
    public bool $relativeProtocol = false;
    public $normalizer;

    public function __construct(
        ?string $pattern = null,
        string $message = null,
        array $protocols = null,
        bool $relativeProtocol = null,
        callable $normalizer = null,
        array $groups = null,
        mixed $payload = null,
        array $options = [],
    ) {
        parent::__construct($options, $groups, $payload);

        $this->pattern = $pattern;
        $this->message = $message ?? $this->message;
        $this->protocols = $protocols ?? $this->protocols;
        $this->relativeProtocol = $relativeProtocol ?? $this->relativeProtocol;
        $this->normalizer = $normalizer ?? $this->normalizer;

        if (null !== $this->normalizer && !\is_callable($this->normalizer)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The "normalizer" option must be a valid callable ("%s" given).',
                    get_debug_type($this->normalizer)
                )
            );
        }
    }
}
