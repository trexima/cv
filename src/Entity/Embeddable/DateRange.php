<?php

namespace Trexima\EuropeanCvBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class DateRange
{
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginDay = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginMonth = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginYear = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endDay = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endMonth = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endYear = null;

    public function getBeginDay(): ?int
    {
        return $this->beginDay;
    }

    public function setBeginDay(?int $beginDay): void
    {
        $this->beginDay = $beginDay;
    }

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

    public function getEndDay(): ?int
    {
        return $this->endDay;
    }

    public function setEndDay(?int $endDay): void
    {
        $this->endDay = $endDay;
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