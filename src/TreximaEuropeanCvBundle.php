<?php

namespace Trexima\EuropeanCvBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TreximaEuropeanCvBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
