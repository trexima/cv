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

    public function setBeginDay(?int $beginDay): self
    {
        $this->beginDay = $beginDay;

        return $this;
    }

    public function getBeginMonth(): ?int
    {
        return $this->beginMonth;
    }

    public function setBeginMonth(?int $beginMonth): self
    {
        $this->beginMonth = $beginMonth;

        return $this;
    }

    public function getBeginYear(): ?int
    {
        return $this->beginYear;
    }

    public function setBeginYear(?int $beginYear): self
    {
        $this->beginYear = $beginYear;

        return $this;
    }

    public function getEndDay(): ?int
    {
        return $this->endDay;
    }

    public function setEndDay(?int $endDay): self
    {
        $this->endDay = $endDay;

        return $this;
    }

    public function getEndMonth(): ?int
    {
        return $this->endMonth;
    }

    public function setEndMonth(?int $endMonth): self
    {
        $this->endMonth = $endMonth;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }
}
