<?php

namespace Trexima\EuropeanCvBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class YearRange
{
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginYear = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endYear = null;

    public function getBeginYear(): ?int
    {
        return $this->beginYear;
    }

    public function setBeginYear(?int $beginYear): void
    {
        $this->beginYear = $beginYear;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): void
    {
        $this->endYear = $endYear;
    }
}