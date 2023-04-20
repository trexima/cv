<?php

namespace Trexima\EuropeanCvBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Embeddable]
class MonthYearRange
{
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1,
        max: 12,
    )]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginMonth = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginYear = null;

    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1,
        max: 12,
    )]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endMonth = null;

    #[Assert\NotBlank]
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

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $from = $this->getBeginYear() . sprintf('%02d', $this->getBeginMonth());
        $to = $this->getEndYear() . sprintf('%02d', $this->getEndMonth());

        if ($from > $to) {
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('beginMonth')
                ->addViolation();
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('beginYear')
                ->addViolation();
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('endYear')
                ->addViolation();
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('endMonth')
                ->addViolation();
        }
    }
}