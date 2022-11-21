<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Trexima\EuropeanCvBundle\Entity\Enum\DigitalSkillLevelEnum;

/**
 * EuropeanCV language
 */
#[ORM\Table(name: 'european_cv_digital_skill')]
#[ORM\Index(name: 'title_idx', columns: ['title'])]
#[ORM\Entity]
class EuropeanCVDigitalSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: EuropeanCV::class, inversedBy: 'digitalSkills')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?EuropeanCV $europeanCV = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'smallint', nullable: true, enumType: DigitalSkillLevelEnum::class)]
    private ?DigitalSkillLevelEnum $level = null;

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

    public function getLevel(): ?DigitalSkillLevelEnum
    {
        return $this->level;
    }

    public function setLevel(?DigitalSkillLevelEnum $level): void
    {
        $this->level = $level;
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
