<?php

namespace Trexima\EuropeanCvBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class YearRange
{
    #[Assert\When(
        expression: 'this.getEndYear() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.select_choice')
        ]
    )]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $beginYear = null;

    #[Assert\When(
        expression: 'this.getBeginYear() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.select_choice'),
            new Assert\When(
                expression: 'value >= 0',
                constraints: [
                    new Assert\GreaterThanOrEqual(propertyPath: 'beginYear')
                ]
            )
        ]
    )]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $endYear = null;

    public function getBeginYear(): ?int
    {
        return $this->beginYear;
    }

    public function setBeginYear(?int $beginYear): self
    {
        $this->beginYear = $beginYear;

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
