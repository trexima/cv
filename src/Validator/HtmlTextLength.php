<?php

namespace Trexima\EuropeanCvBundle\Validator;

use Symfony\Component\Validator\Constraints\Length;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class HtmlTextLength extends Length
{
}
