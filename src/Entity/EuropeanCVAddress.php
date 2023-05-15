<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'european_cv_address')]
class EuropeanCVAddress extends AbstractGoogleAddress
{
}
