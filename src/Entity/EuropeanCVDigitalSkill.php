<?php

namespace Trexima\EuropeanCvBundle\Entity;

use App\Entity\Job\PcSkill;                     //todo
use App\Enum\Job\JobPcSkillEntityLevelEnum;     //todo
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EuropeanCV language
 */
#[ORM\Table(name: 'european_cv_digital_skill')]
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

    #[Assert\When(
        expression: 'this.getLevel() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.pc_skill_not_empty')
        ],
    )]
    #[ORM\ManyToOne(targetEntity: PcSkill::class)]
    #[ORM\JoinColumn(name: 'cv_pc_skill_id', nullable: false)]
    private ?PcSkill $pcSkill = null;

    #[Assert\When(
        expression: 'this.getPcSkill() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.level_not_empty')
        ]
    )]
    #[ORM\Column(type: 'string', length: 16, nullable: false, enumType: JobPcSkillEntityLevelEnum::class)]
    private ?JobPcSkillEntityLevelEnum $level = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEuropeanCV(): ?EuropeanCV
    {
        return $this->europeanCV;
    }

    public function setEuropeanCV(EuropeanCV $europeanCV): void
    {
        $this->europeanCV = $europeanCV;
    }

    public function getPcSkill(): ?PcSkill
    {
        return $this->pcSkill;
    }

    public function setPcSkill(?PcSkill $pcSkill): self
    {
        $this->pcSkill = $pcSkill;

        return $this;
    }

    public function getLevel(): ?JobPcSkillEntityLevelEnum
    {
        return $this->level;
    }

    public function setLevel(?JobPcSkillEntityLevelEnum $level): self
    {
        $this->level = $level;

        return $this;
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
