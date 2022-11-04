<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EuropeanCV phone
 */
#[ORM\Table(name: 'european_cv_phone')]
#[ORM\Entity]
class EuropeanCVPhone
{
    final public const TYPE_MOBILE = 1,
        TYPE_HOME = 2,
        TYPE_WORK = 3;

    final public const TYPES = [
        self::TYPE_MOBILE => 'Mobil',
        self::TYPE_HOME => 'Doma',
        self::TYPE_WORK => 'Práca'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: \Trexima\EuropeanCvBundle\Entity\EuropeanCV::class, inversedBy: 'phones')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?\Trexima\EuropeanCvBundle\Entity\EuropeanCV $europeanCV = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    // #[Assert\Choice(callback: 'getTypeList', message: 'Choose a valid phone type.')]
    private ?int $type = null;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $number = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?string $position = null;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): void
    {
        $this->type = $type;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): void
    {
        $this->number = $number;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public static function getTypeList()
    {
        return array_flip(self::TYPES);
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