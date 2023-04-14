<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EuropeanCV practice
 */
#[ORM\Table(name: 'european_cv_practice_processed')]
#[ORM\Entity]
class EuropeanCVPracticeProcessed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: EuropeanCV::class, inversedBy: 'practicesProcessed')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?EuropeanCV $europeanCV = null;

    /**
     * Max length is 256 because this field can be filled from Harvey where field max length is 256
     */
    #[ORM\Column(type: 'string', length: 256, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'string', length: 7, nullable: true)]
    private ?string $iscoCode = null;

    #[ORM\Column(type: 'smallint', options: ['unsigned' => true, 'default' => 0], nullable: false)]
    private int $months = 0;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEuropeanCV(): EuropeanCV
    {
        return $this->europeanCV;
    }

    public function setEuropeanCV(EuropeanCV $europeanCV): void
    {
        $this->europeanCV = $europeanCV;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getIscoCode(): ?string
    {
        return $this->iscoCode;
    }

    public function setIscoCode(?string $iscoCode): void
    {
        $this->iscoCode = $iscoCode;
    }

    public function getMonths(): int
    {
        return $this->months;
    }

    public function setMonths(int $months): void
    {
        $this->months = $months;
    }
}