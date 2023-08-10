<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Proxy;
use Symfony\Component\Validator\Constraints as Assert;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\SexEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\StyleEnum;
use Trexima\EuropeanCvBundle\Model\UserInterface;

#[ORM\Table(name: 'european_cv')]
#[ORM\Entity]
class EuropeanCV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?UserInterface $user = null;

    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVPractice::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $practices;

    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVPracticeProcessed::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $practicesProcessed;

    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVWorkBreak::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $workBreaks;

    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVEducation::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $educations;

    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVCertificate::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $certificates;

    #[ORM\OrderBy(['sort' => 'ASC'])]
    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVLanguage::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $languages;

    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVDrivingLicense::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $drivingLicenses;

    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVPhone::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $phones;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $photo = null;

    #[Assert\Length(max: 50, maxMessage: 'trexima_european_cv.max_length_reached')]
    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $firstName = null;

    #[Assert\Length(max: 50, maxMessage: 'trexima_european_cv.max_length_reached')]
    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column]
    private array $titlesBefore = [];

    #[ORM\Column]
    private array $titlesAfter = [];

    #[ORM\Column]
    private array $nationalities = [];

    #[Assert\When(
        expression: 'this.getMonth() !== null || this.getDay() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.select_choice')
        ]
    )]
    #[ORM\Column(type: 'integer', nullable: true, options: ['unsigned' => true])]
    private ?int $year = null;

    #[Assert\When(
        expression: 'this.getYear() !== null || this.getDay() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.select_choice')
        ]
    )]
    #[ORM\Column(type: 'integer', nullable: true, options: ['unsigned' => true])]
    private ?int $month = null;

    #[Assert\When(
        expression: 'this.getYear() !== null || this.getMonth() !== null',
        constraints: [
            new Assert\NotNull(message: 'trexima_european_cv.select_choice')
        ]
    )]
    #[ORM\Column(type: 'integer', nullable: true, options: ['unsigned' => true])]
    private ?int $day = null;

    #[ORM\OneToOne(targetEntity: EuropeanCVAddress::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'id')]
    private ?EuropeanCVAddress $address;

    #[Assert\Email]
    #[ORM\Column(type: 'string', length: 320, nullable: true)]
    private ?string $email = null;

    #[Assert\All([
        new Assert\NotBlank(),
        new Assert\Url(),
    ])]
    #[ORM\Column(type: 'json')]
    private array $personalWebsites = [];

    #[Assert\All([
        new Assert\NotBlank(),
        new Assert\Url(),
    ])]
    #[ORM\Column(type: 'json')]
    private array $socialAccounts = [];

    #[ORM\Column(type: 'integer', nullable: true, enumType: SexEnum::class)]
    private ?SexEnum $sex = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $drivingLicenseOwner = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt;

    #[ORM\Column(type: 'string', nullable: true, enumType: LanguageEnum::class)]
    private ?LanguageEnum $language = null;

    #[ORM\Column(type: 'string', nullable: true, enumType: StyleEnum::class)]
    private ?StyleEnum $style = null;

    #[Assert\Length(max: 300, maxMessage: 'trexima_european_cv.max_length_reached')]
    #[ORM\Column(type: 'text', length: 65535, nullable: true)]
    private ?string $description = null;

    #[Assert\Length(max: 4000, maxMessage: 'trexima_european_cv.max_length_reached')]
    #[ORM\Column(type: 'text', length: 65535, nullable: true)]
    private ?string $hobbies = null;

    #[Assert\Length(max: 4000, maxMessage: 'trexima_european_cv.max_length_reached')]
    #[ORM\Column(type: 'text', length: 65535, nullable: true)]
    private ?string $additionalInformations = null;

    #[ORM\OrderBy(['sort' => 'ASC'])]
    #[ORM\OneToMany(
        mappedBy: 'europeanCV',
        targetEntity: EuropeanCVDigitalSkill::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $digitalSkills;

    #[ORM\Column(nullable: true)]
    private ?array $competences = [];

    public function __construct()
    {
        $this->practices = new ArrayCollection();
        $this->practicesProcessed = new ArrayCollection();
        $this->workBreaks = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->certificates = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->drivingLicenses = new ArrayCollection();
        $this->phones = new ArrayCollection();
        $this->digitalSkills = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVPractice>
     */
    public function getPractices(): Collection
    {
        return $this->practices;
    }

    public function addPractice(EuropeanCVPractice $practice): self
    {
        if (!$this->practices->contains($practice)) {
            $this->practices[] = $practice;
            $practice->setEuropeanCV($this);
        }

        return $this;
    }

    public function removePractice(EuropeanCVPractice $practice): self
    {
        if ($this->practices->removeElement($practice)) {
            if ($practice->getEuropeanCV() === $this) {
                $practice->setEuropeanCV(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVPracticeProcessed>
     */
    public function getPracticesProcessed(): Collection
    {
        return $this->practicesProcessed;
    }

    public function addPracticeProcessed(EuropeanCVPracticeProcessed $practiceProcessed): self
    {
        if (!$this->practicesProcessed->contains($practiceProcessed)) {
            $this->practicesProcessed[] = $practiceProcessed;
            $practiceProcessed->setEuropeanCV($this);
        }

        return $this;
    }

    public function removePracticeProcessed(EuropeanCVPracticeProcessed $practiceProcessed): self
    {
        if ($this->practicesProcessed->removeElement($practiceProcessed)) {
            if ($practiceProcessed->getEuropeanCV() === $this) {
                $practiceProcessed->setEuropeanCV(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVWorkBreak>
     */
    public function getWorkBreaks(): Collection
    {
        return $this->workBreaks;
    }

    public function addWorkBreak(EuropeanCVWorkBreak $workBreak): self
    {
        if (!$this->workBreaks->contains($workBreak)) {
            $this->workBreaks[] = $workBreak;
            $workBreak->setEuropeanCV($this);
        }

        return $this;
    }

    public function removeWorkBreak(EuropeanCVWorkBreak $workBreak): self
    {
        if ($this->workBreaks->removeElement($workBreak)) {
            if ($workBreak->getEuropeanCV() === $this) {
                $workBreak->setEuropeanCV(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVEducation>
     */
    public function getEducations(): Collection
    {
        return $this->educations;
    }

    public function addEducation(EuropeanCVEducation $education): self
    {
        if (!$this->educations->contains($education)) {
            $this->educations[] = $education;
            $education->setEuropeanCV($this);
        }

        return $this;
    }

    public function removeEducation(EuropeanCVEducation $education): self
    {
        if ($this->educations->removeElement($education)) {
            if ($education->getEuropeanCV() === $this) {
                $education->setEuropeanCV(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVCertificate>
     */
    public function getCertificates(): Collection
    {
        return $this->certificates;
    }

    public function addCertificate(EuropeanCVCertificate $certificate): self
    {
        if (!$this->certificates->contains($certificate)) {
            $this->certificates[] = $certificate;
            $certificate->setEuropeanCV($this);
        }

        return $this;
    }

    public function removeCertificate(EuropeanCVCertificate $certificate): self
    {
        if ($this->certificates->removeElement($certificate)) {
            if ($certificate->getEuropeanCV() === $this) {
                $certificate->setEuropeanCV(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVLanguage>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(EuropeanCVLanguage $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
            $language->setEuropeanCV($this);
        }

        return $this;
    }

    public function removeLanguage(EuropeanCVLanguage $language): self
    {
        if ($this->languages->removeElement($language)) {
            if ($language->getEuropeanCV() === $this) {
                $language->setEuropeanCV(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVDrivingLicense>
     */
    public function getDrivingLicenses(): Collection
    {
        return $this->drivingLicenses;
    }

    public function addDrivingLicense(EuropeanCVDrivingLicense $drivingLicense): self
    {
        if (!$this->drivingLicenses->contains($drivingLicense)) {
            $this->drivingLicenses[] = $drivingLicense;
            $drivingLicense->setEuropeanCV($this);
        }

        return $this;
    }

    public function removeDrivingLicense(EuropeanCVDrivingLicense $drivingLicense): self
    {
        if ($this->drivingLicenses->removeElement($drivingLicense)) {
            if ($drivingLicense->getEuropeanCV() === $this) {
                $drivingLicense->setEuropeanCV(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVPhone>
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(EuropeanCVPhone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
            $phone->setEuropeanCV($this);
        }

        return $this;
    }

    public function removePhone(EuropeanCVPhone $phone): self
    {
        if ($this->phones->removeElement($phone)) {
            if ($phone->getEuropeanCV() === $this) {
                $phone->setEuropeanCV(null);
            }
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTitlesBefore(): array
    {
        return $this->titlesBefore;
    }

    public function setTitlesBefore(array $titlesBefore): self
    {
        $this->titlesBefore = $titlesBefore;

        return $this;
    }

    public function getTitlesAfter(): array
    {
        return $this->titlesAfter;
    }

    public function setTitlesAfter(array $titlesAfter): self
    {
        $this->titlesAfter = $titlesAfter;

        return $this;
    }

    public function getNationalities(): array
    {
        return $this->nationalities;
    }

    public function setNationalities(array $nationalities): self
    {
        $this->nationalities = $nationalities;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(?int $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(?int $day): self
    {
        $this->day = $day;

        return $this;
    }

    #[Assert\LessThanOrEqual('today', message: 'trexima_european_cv.birth_date.constraint.less_than_or_equal')]
    public function getBirthDate(): ?\DateTimeImmutable
    {
        if (($year = $this->getYear()) && ($month = $this->getMonth()) && ($day = $this->getDay())) {
            return (new \DateTimeImmutable())->setDate($year, $month, $day)->setTime(0, 0);
        }

        return null;
    }

    public function getAddress(): ?EuropeanCVAddress
    {
        return $this->address;
    }

    public function setAddress(?EuropeanCVAddress $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPersonalWebsites(): array
    {
        return $this->personalWebsites;
    }

    public function setPersonalWebsites(array $personalWebsites): self
    {
        $this->personalWebsites = $personalWebsites;

        return $this;
    }

    public function getSocialAccounts(): array
    {
        return $this->socialAccounts;
    }

    public function setSocialAccounts(array $socialAccounts): self
    {
        $this->socialAccounts = $socialAccounts;

        return $this;
    }

    public function getSex(): ?SexEnum
    {
        return $this->sex;
    }

    public function setSex(?SexEnum $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getDrivingLicenseOwner(): ?bool
    {
        return $this->drivingLicenseOwner;
    }

    public function setDrivingLicenseOwner(?bool $drivingLicenseOwner): self
    {
        $this->drivingLicenseOwner = $drivingLicenseOwner;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLanguage(): ?LanguageEnum
    {
        return $this->language;
    }

    public function setLanguage(?LanguageEnum $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getStyle(): ?StyleEnum
    {
        return $this->style;
    }

    public function setStyle(?StyleEnum $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHobbies(): ?string
    {
        return $this->hobbies;
    }

    public function setHobbies(?string $hobbies): self
    {
        $this->hobbies = $hobbies;

        return $this;
    }

    public function getAdditionalInformations(): ?string
    {
        return $this->additionalInformations;
    }

    public function setAdditionalInformations(?string $additionalInformations): self
    {
        $this->additionalInformations = $additionalInformations;

        return $this;
    }

    /**
     * @return Collection<int, EuropeanCVDigitalSkill>
     */
    public function getDigitalSkills(): Collection
    {
        return $this->digitalSkills;
    }

    public function addDigitalSkill(EuropeanCVDigitalSkill $digitalSkill): self
    {
        if (!$this->digitalSkills->contains($digitalSkill)) {
            $this->digitalSkills[] = $digitalSkill;
            $digitalSkill->setEuropeanCV($this);
        }

        return $this;
    }

    public function removeDigitalSkill(EuropeanCVDigitalSkill $digitalSkill): self
    {
        if ($this->digitalSkills->removeElement($digitalSkill)) {
            if ($digitalSkill->getEuropeanCV() === $this) {
                $digitalSkill->setEuropeanCV(null);
            }
        }

        return $this;
    }


    public function getCompetences(): array
    {
        return $this->competences;
    }

    public function setCompetences(array $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    /**
     * Function returns date of birth in datetime format
     */
    public function getFormattedDateOfBirth(): string
    {
        $date = '';
        if (!empty($this->day) && !empty($this->month)) {
            $date .= $this->day . '.' . $this->month . '. ';
        }

        $date .= $this->year;
        return $date;
    }

    public function getFullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    public function getFullNameWithTitles(): string
    {
        $fullName = '';

        if (!empty($this->titlesBefore)) {
            $fullName .= implode(' ', $this->titlesBefore) . ' ';
        }

        $fullName .= $this->getFullName();

        if (!empty($this->titlesAfter)) {
            $fullName .= ', ' . implode(', ', $this->titlesAfter);
        }

        return $fullName;
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

            $this->practices = clone $this->practices;
            foreach ($this->practices as $key => $practice) {
                $practice = clone $practice;
                $practice->setEuropeanCV($this);
                $this->practices->set($key, $practice);
            }

            $this->practicesProcessed = clone $this->practicesProcessed;
            foreach ($this->practicesProcessed as $key => $practiceProcessed) {
                $practiceProcessed = clone $practiceProcessed;
                $practiceProcessed->setEuropeanCV($this);
                $this->practicesProcessed->set($key, $practiceProcessed);
            }

            $this->workBreaks = clone $this->workBreaks;
            foreach ($this->workBreaks as $key => $workBreak) {
                $workBreak = clone $workBreak;
                $workBreak->setEuropeanCV($this);
                $this->workBreaks->set($key, $workBreak);
            }

            $this->educations = clone $this->educations;
            foreach ($this->educations as $key => $education) {
                $education = clone $education;
                $education->setEuropeanCV($this);
                $this->educations->set($key, $education);
            }

            $this->certificates = clone $this->certificates;
            foreach ($this->certificates as $key => $certificate) {
                $certificate = clone $certificate;
                $certificate->setEuropeanCV($this);
                $this->certificates->set($key, $certificate);
            }

            $this->languages = clone $this->languages;
            foreach ($this->languages as $key => $language) {
                $language = clone $language;
                $language->setEuropeanCV($this);
                $this->languages->set($key, $language);
            }

            $this->drivingLicenses = clone $this->drivingLicenses;
            foreach ($this->drivingLicenses as $key => $drivingLicense) {
                $drivingLicense = clone $drivingLicense;
                $drivingLicense->setEuropeanCV($this);
                $this->drivingLicenses->set($key, $drivingLicense);
            }

            $this->phones = clone $this->phones;
            foreach ($this->phones as $key => $phone) {
                $phone = clone $phone;
                $phone->setEuropeanCV($this);
                $this->phones->set($key, $phone);
            }

            $this->digitalSkills = clone $this->digitalSkills;
            foreach ($this->digitalSkills as $key => $digitalSkill) {
                $digitalSkill = clone $digitalSkill;
                $digitalSkill->setEuropeanCV($this);
                $this->digitalSkills->set($key, $digitalSkill);
            }

            if ($address = $this->address) {
                if ($address instanceof Proxy && !$address->__isInitialized()) {
                    $address->__load();
                }

                $this->address = clone $address;
            }

            if ($createdAt = $this->createdAt) {
                $this->createdAt = clone $createdAt;
            }

            if ($updatedAt = $this->updatedAt) {
                $this->updatedAt = clone $updatedAt;
            }
        }
    }
}
