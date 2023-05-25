<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Proxy;

/**
 * EuropeanCV practice processed
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

    #[ORM\Column(type: 'smallint', nullable: false, options: ['unsigned' => true, 'default' => 0])]
    private int $months = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEuropeanCV(): ?EuropeanCV
    {
        return $this->europeanCV;
    }

    public function setEuropeanCV(?EuropeanCV $europeanCV): self
    {
        $this->europeanCV = $europeanCV;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIscoCode(): ?string
    {
        return $this->iscoCode;
    }

    public function setIscoCode(?string $iscoCode): self
    {
        $this->iscoCode = $iscoCode;

        return $this;
    }

    public function getMonths(): int
    {
        return $this->months;
    }

    public function setMonths(int $months): self
    {
        $this->months = $months;

        return $this;
    }

    public function __clone()
    {
        /*
         * If the entity has an identity, proceed as normal.
         * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/cookbook/implementing-wakeup-or-clone.html
         */
        if ($this->id) {
            if ($this instanceof Proxy && !$this->__isInitialized()) {
                $this->__load();
            }

            $this->id = null;
        }
    }
}
