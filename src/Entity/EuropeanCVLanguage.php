<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EuropeanCV language
 */
#[ORM\Table(name: 'european_cv_language')]
#[ORM\Index(name: 'language_idx', columns: ['language'])]
#[ORM\Entity]
class EuropeanCVLanguage
{
    final public const LANGUAGE_LEVEL_A1 = 1,
        LANGUAGE_LEVEL_A2 = 2,
        LANGUAGE_LEVEL_B1 = 3,
        LANGUAGE_LEVEL_B2 = 4,
        LANGUAGE_LEVEL_C1 = 5,
        LANGUAGE_LEVEL_C2 = 6;

    final public const LANGUAGE_LEVEL_LIST = [
        self::LANGUAGE_LEVEL_A1 => 'A1 Používateľ základov jazyka ',
        self::LANGUAGE_LEVEL_A2 => 'A2 Používateľ základov jazyka',
        self::LANGUAGE_LEVEL_B1 => 'B1 Samostatný používateľ',
        self::LANGUAGE_LEVEL_B2 => 'B2 Samostatný používateľ',
        self::LANGUAGE_LEVEL_C1 => 'C1 Skúsený používateľ',
        self::LANGUAGE_LEVEL_C2 => 'C2 Skúsený používateľ'
    ];

    final public const LANGUAGE_LEVEL_TO_CODE = [
        self::LANGUAGE_LEVEL_A1 => 'A1',
        self::LANGUAGE_LEVEL_A2 => 'A2',
        self::LANGUAGE_LEVEL_B1 => 'B1',
        self::LANGUAGE_LEVEL_B2 => 'B2',
        self::LANGUAGE_LEVEL_C1 => 'C1',
        self::LANGUAGE_LEVEL_C2 => 'C2'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: \Trexima\EuropeanCvBundle\Entity\EuropeanCV::class, inversedBy: 'languages')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?\Trexima\EuropeanCvBundle\Entity\EuropeanCV $europeanCV = null;

    #[ORM\Column(type: 'string', nullable: true, length: 5, options: ['comment' => 'ISO 639-1'])]
    private ?string $language = null;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['unsigned' => true])]
    // #[Assert\Choice(callback: 'getLanguageLevelList', message: 'Choose a valid language level.')]
    private ?int $listeningLevel = null;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['unsigned' => true])]
    // #[Assert\Choice(callback: 'getLanguageLevelList', message: 'Choose a valid language level.')]
    private ?int $readingLevel = null;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['unsigned' => true])]
    // #[Assert\Choice(callback: 'getLanguageLevelList', message: 'Choose a valid language level.')]
    private ?int $talkingLevel = null;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['unsigned' => true])]
    // #[Assert\Choice(callback: 'getLanguageLevelList', message: 'Choose a valid language level.')]
    private ?int $oralSpeechLevel = null;

    #[ORM\Column(type: 'smallint', nullable: true, options: ['unsigned' => true])]
    // #[Assert\Choice(callback: 'getLanguageLevelList', message: 'Choose a valid language level.')]
    private ?int $writingLevel = null;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $certificate = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $position = null;

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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    public function getListeningLevel(): ?int
    {
        return $this->listeningLevel;
    }

    public function setListeningLevel(?int $listeningLevel): void
    {
        $this->listeningLevel = $listeningLevel;
    }

    public function getReadingLevel(): ?int
    {
        return $this->readingLevel;
    }

    public function setReadingLevel(?int $readingLevel): void
    {
        $this->readingLevel = $readingLevel;
    }

    public function getTalkingLevel(): ?int
    {
        return $this->talkingLevel;
    }

    public function setTalkingLevel(?int $talkingLevel): void
    {
        $this->talkingLevel = $talkingLevel;
    }

    public function getOralSpeechLevel(): ?int
    {
        return $this->oralSpeechLevel;
    }

    public function setOralSpeechLevel(?int $oralSpeechLevel): void
    {
        $this->oralSpeechLevel = $oralSpeechLevel;
    }

    public function getWritingLevel(): ?int
    {
        return $this->writingLevel;
    }

    public function setWritingLevel(?int $writingLevel): void
    {
        $this->writingLevel = $writingLevel;
    }

    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    public function setCertificate(?string $certificate): void
    {
        $this->certificate = $certificate;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public static function getLanguageLevelList()
    {
        return array_keys(self::LANGUAGE_LEVEL_LIST );
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
