<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Trexima\EuropeanCvBundle\Entity\Embeddable\DateRange;

/**
 * EuropeanCV practice
 */
#[ORM\Table(name: 'european_cv_practice')]
#[ORM\Entity]
class EuropeanCVPractice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: \Trexima\EuropeanCvBundle\Entity\EuropeanCV::class, inversedBy: 'practices')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?\Trexima\EuropeanCvBundle\Entity\EuropeanCV $europeanCV = null;

    #[ORM\Embedded(class: \Trexima\EuropeanCvBundle\Entity\Embeddable\DateRange::class)]
    private \Trexima\EuropeanCvBundle\Entity\Embeddable\DateRange $dateRange;

    /**
     * @var string|null Max length is 256 because this field can be filled from Harvey where field max length is 256
     */
    #[ORM\Column(type: 'string', length: 256, nullable: true)]
    private ?string $job = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $mainActivities = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $jobAddress = null;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $industry = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?string $position = null;

    public function __construct()
    {
        $this->dateRange = new DateRange();
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

    public function getDateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function setDateRange(DateRange $dateRange): void
    {
        $this->dateRange = $dateRange;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): void
    {
        $this->job = $job;
    }

    public function getMainActivities(): ?string
    {
        return $this->mainActivities;
    }

    public function setMainActivities(?string $mainActivities): void
    {
        $this->mainActivities = $mainActivities;
    }

    public function getJobAddress(): ?string
    {
        return $this->jobAddress;
    }

    public function setJobAddress(?string $jobAddress): void
    {
        $this->jobAddress = $jobAddress;
    }

    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    public function setIndustry(?string $industry): void
    {
        $this->industry = $industry;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function __clone()
    {
        /*
         * If the entity has an identity, proceed as normal.
         * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/cookbook/implementing-wakeup-or-clone.html
         */
        if ($this->id) {
            $this->setId(null);
        }
    }
}