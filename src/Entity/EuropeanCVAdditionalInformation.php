<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EuropeanCV additional information
 */
#[ORM\Table(name: 'european_cv_additional_information')]
#[ORM\Entity]
class EuropeanCVAdditionalInformation
{
    final public const TYPE_CERTIFICATION = 1,
        TYPE_CITATION = 2,
        TYPE_MEMBERSHIP = 3,
        TYPE_CONFERENCE = 4,
        TYPE_COURSE = 5,
        TYPE_PRESENTATION = 6,
        TYPE_PROJECT = 7,
        TYPE_PUBLICATION = 8,
        TYPE_REFERENCE = 9,
        TYPE_SEMINAR = 10,
        TYPE_AWARDS = 11;

    final public const TYPES = [
        self::TYPE_CERTIFICATION => 'Certifikácia',
        self::TYPE_CITATION => 'Citácie',
        self::TYPE_MEMBERSHIP => 'Členstvá',
        self::TYPE_CONFERENCE => 'Konferencie',
        self::TYPE_COURSE => 'Kurzy',
        self::TYPE_PRESENTATION => 'Prezentácie',
        self::TYPE_PROJECT => 'Projekty',
        self::TYPE_PUBLICATION => 'Publikácie',
        self::TYPE_REFERENCE => 'Referencie',
        self::TYPE_SEMINAR => 'Semináre',
        self::TYPE_AWARDS => 'Vyznamenania a ocenenia'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: \Trexima\EuropeanCvBundle\Entity\EuropeanCV::class, inversedBy: 'additionalInformations')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?\Trexima\EuropeanCvBundle\Entity\EuropeanCV $europeanCV = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    // #[Assert\Choice(callback: 'getTypeList', message: 'Choose a valid additional info type.')]
    private ?int $type = null;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    private ?string $content = null;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
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
        return array_keys(self::TYPES);
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