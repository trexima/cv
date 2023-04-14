<?php

namespace Trexima\EuropeanCvBundle\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ArrayToJsonTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): string
    {
        if (null !== $value && !\is_array($value)) {
            throw new TransformationFailedException('Failed to encode data into json. Expected value of type array.');
        }

        $encoded = \json_encode($value);
        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new TransformationFailedException(
                \sprintf('Failed to encode data into json: %s', \json_last_error_msg()),
            );
        }

        return $encoded;
    }

    public function reverseTransform(mixed $value): ?array
    {
        if (null === $value) {
            return null;
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new TransformationFailedException('Failed to decode json data into array. Not a string.');
        }

        $value = (string)$value;

        $decoded = \json_decode($value, true);
        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new TransformationFailedException(
                \sprintf('Failed to decode json data into array: %s', \json_last_error_msg()),
            );
        }

        return $decoded;
    }
}
