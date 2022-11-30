<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Trexima\EuropeanCvBundle\Entity\Embeddable\MonthYearRange;
use Trexima\EuropeanCvBundle\Entity\Enum\WorkBreakEnum;

/**
 * EuropeanCV work break
 */
#[ORM\Table(name: 'european_cv_work_break')]
#[ORM\Entity]
class EuropeanCVWorkBreak
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: EuropeanCV::class, inversedBy: 'practices')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?EuropeanCV $europeanCV = null;

    #[ORM\Embedded(class: MonthYearRange::class)]
    private MonthYearRange $dateRange;

    #[ORM\Column(type: 'string', length: 256, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'smallint', nullable: true, enumType: WorkBreakEnum::class)]
    private ?WorkBreakEnum $type = null;

    #[ORM\Column(type: 'text', length: 65535, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->dateRange = new MonthYearRange();
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

    public function getDateRange(): MonthYearRange
    {
        return $this->dateRange;
    }

    public function setDateRange(MonthYearRange $dateRange): void
    {
        $this->dateRange = $dateRange;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getType(): ?WorkBreakEnum
    {
        return $this->type;
    }

    public function setType(?WorkBreakEnum $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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