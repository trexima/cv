<?php

namespace Trexima\EuropeanCvBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class MonthYearRange
{
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginMonth = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginYear = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endMonth = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endYear = null;

    public function getBeginMonth(): ?int
    {
        return $this->beginMonth;
    }

    public function setBeginMonth(?int $beginMonth): void
    {
        $this->beginMonth = $beginMonth;
    }

    public function getBeginYear(): ?int
    {
        return $this->beginYear;
    }

    public function setBeginYear(?int $beginYear): void
    {
        $this->beginYear = $beginYear;
    }

    public function getEndMonth(): ?int
    {
        return $this->endMonth;
    }

    public function setEndMonth(?int $endMonth): void
    {
        $this->endMonth = $endMonth;
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