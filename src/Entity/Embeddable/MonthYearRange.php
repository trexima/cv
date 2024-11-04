<?php

namespace Trexima\EuropeanCvBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Embeddable]
class MonthYearRange
{
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 12)]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginMonth = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginYear = null;

    #[Assert\Range(min: 1, max: 12)]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endMonth = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endYear = null;

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

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        $endYear = $this->getEndYear();
        $endMonth = $this->getEndMonth();

        if (null === $endYear && null === $endMonth) {
            return;
        }

        if (null === $endYear || null === $endMonth) {
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('endMonth')
                ->addViolation();
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('endYear')
                ->addViolation();

            return;
        }

        $from = $this->getBeginYear().\sprintf('%02d', $this->getBeginMonth());
        $to = $endYear.\sprintf('%02d', $endMonth);

        if ($from > $to) {
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('beginMonth')
                ->addViolation();
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('beginYear')
                ->addViolation();
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('endMonth')
                ->addViolation();
            $context->buildViolation('trexima_european_cv.range_not_valid')
                ->atPath('endYear')
                ->addViolation();
        }
    }
}
