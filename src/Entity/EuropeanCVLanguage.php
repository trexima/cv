<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageLevelEnum;

/**
 * EuropeanCV language
 */
#[ORM\Table(name: 'european_cv_language')]
#[ORM\Index(name: 'language_idx', columns: ['language'])]
#[ORM\Entity]
class EuropeanCVLanguage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: EuropeanCV::class, inversedBy: 'languages')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?EuropeanCV $europeanCV = null;

    #[Assert\When(
        expression: 'this.getLevel() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.level_not_empty')
        ]
    )]
    #[ORM\Column(type: 'string', nullable: true, length: 2, enumType: LanguageEnum::class, options: ['comment' => 'ISO 639-1'])]
    private ?LanguageEnum $language = null;

    #[Assert\When(
        expression: 'this.getLanguage() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.level_not_empty')
        ]
    )]
    #[ORM\Column(type: 'string', length: 2, nullable: true, enumType: LanguageLevelEnum::class)]
    private ?LanguageLevelEnum $level = null;

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

    public function getLanguage(): ?LanguageEnum
    {
        return $this->language;
    }

    public function setLanguage(?LanguageEnum $language): void
    {
        $this->language = $language;
    }

    public function getLevel(): ?LanguageLevelEnum
    {
        return $this->level;
    }

    public function setLevel(?LanguageLevelEnum $level): void
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
