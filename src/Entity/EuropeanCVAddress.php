<?php

namespace Trexima\EuropeanCvBundle\Entity;
    
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Trexima\EuropeanCvBundle\Entity\AbstractGoogleAddress;

#[ORM\Entity]
#[ORM\Table(name: 'european_cv_address')]
class EuropeanCVAddress extends AbstractGoogleAddress
{
    #[ORM\Column(type: Types::STRING, length: 512)]
    protected ?string $googlePlaceId = null;
}
