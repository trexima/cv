<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\SexEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\StyleEnum;
use Trexima\EuropeanCvBundle\Model\UserInterface;

/**
 * WARNING: Don't forget to prepare newly created entity relations for clonning in magic __clone method.
 */
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

    /**
     * @var EuropeanCVPractice[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVPractice::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $practices;

    /**
     * @var EuropeanCVPracticeProcessed[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVPracticeProcessed::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $practicesProcessed;

    /**
     * @var EuropeanCVWorkBreak[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVWorkBreak::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $workBreaks;

    /**
     * @var EuropeanCVEducation[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVEducation::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $educations;

    /**
     * @var EuropeanCVCertificate[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVCertificate::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $certificates;

    /**
     * @var EuropeanCVLanguage[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVLanguage::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $languages;

    /**
     * @var EuropeanCVDrivingLicense[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVDrivingLicense::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $drivingLicenses;

    /**
     * @var EuropeanCVPhone[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVPhone::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $phones;

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

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $year = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $month = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $day = null;

    #[ORM\OneToOne(targetEntity: EuropeanCVAddress::class, cascade: ['all'])]
    #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'id')]
    private ?EuropeanCVAddress $address;

    #[Assert\Email]
    #[ORM\Column(type: 'string', length: 320, nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $personalWebsites = [];

    #[ORM\Column(type: 'integer', nullable: true, enumType: SexEnum::class)]
    private ?SexEnum $sex = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $drivingLicenseOwner = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt = null;

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

    /**
     * @var EuropeanCVDigitalSkill[]|Collection
     */
    #[ORM\OneToMany(targetEntity: EuropeanCVDigitalSkill::class, mappedBy: 'europeanCV', orphanRemoval: true, cascade: ['all'])]
    private $digitalSkills;

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

    /**
     * @return EuropeanCVPractice[]|Collection
     */
    public function getPractices(): array|Collection
    {
        return $this->practices;
    }

    public function addPractice(EuropeanCVPractice $practice)
    {
        $this->practices[] = $practice;
        $practice->setEuropeanCV($this);
    }

    public function removePractice(EuropeanCVPractice $practice)
    {
        $this->practices->removeElement($practice);
    }

    /**
     * @return EuropeanCVPracticeProcessed[]|Collection
     */
    public function getPracticesProcessed(): array|Collection
    {
        return $this->practicesProcessed;
    }

    public function addPracticeProcessed(EuropeanCVPracticeProcessed $practiceProcessed)
    {
        $this->practicesProcessed[] = $practiceProcessed;
        $practiceProcessed->setEuropeanCV($this);
    }

    public function removePracticeProcessed(EuropeanCVPracticeProcessed $practiceProcessed)
    {
        $this->practicesProcessed->removeElement($practiceProcessed);
    }

    /**
     * @return EuropeanCVWorkBreak[]|Collection
     */
    public function getWorkBreaks(): array|Collection
    {
        return $this->workBreaks;
    }

    public function addWorkBreak(EuropeanCVWorkBreak $workBreak)
    {
        $this->workBreaks[] = $workBreak;
        $workBreak->setEuropeanCV($this);
    }

    public function removeWorkBreak(EuropeanCVWorkBreak $workBreak)
    {
        $this->workBreaks->removeElement($workBreak);
    }

    /**
     * @return EuropeanCVEducation[]|Collection
     */
    public function getEducations(): array|Collection
    {
        return $this->educations;
    }

    public function addEducation(EuropeanCVEducation $education)
    {
        $this->educations[] = $education;
        $education->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVEducation $practice
     */
    public function removeEducation(EuropeanCVEducation $education)
    {
        $this->educations->removeElement($education);
    }

    /**
     * @return EuropeanCVCertificate[]|Collection
     */
    public function getCertificates(): array|Collection
    {
        return $this->certificates;
    }

    public function addCertificate(EuropeanCVCertificate $certificate)
    {
        $this->certificates[] = $certificate;
        $certificate->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVCertificate $certificate
     */
    public function removeCertificate(EuropeanCVCertificate $certificate)
    {
        $this->certificates->removeElement($certificate);
    }

    /**
     * @return EuropeanCVLanguage[]|Collection
     */
    public function getLanguages(): array|Collection
    {
        return $this->languages;
    }

    /**
     * @param EuropeanCVEducation $language
     */
    public function addLanguage(EuropeanCVLanguage $language)
    {
        $this->languages[] = $language;
        $language->setEuropeanCV($this);
    }

    public function removeLanguage(EuropeanCVLanguage $language)
    {
        $this->languages->removeElement($language);
    }

    /**
     * @return EuropeanCVDrivingLicense[]|Collection
     */
    public function getDrivingLicenses(): array|Collection
    {
        return $this->drivingLicenses;
    }

    public function addDrivingLicense(EuropeanCVDrivingLicense $drivingLicense)
    {
        $this->drivingLicenses[] = $drivingLicense;
        $drivingLicense->setEuropeanCV($this);
    }

    public function removeDrivingLicense(EuropeanCVDrivingLicense $drivingLicense)
    {
        $this->drivingLicenses->removeElement($drivingLicense);
    }

    /**
     * @return EuropeanCVPhone[]|Collection
     */
    public function getPhones(): array|Collection
    {
        return $this->phones;
    }

    public function addPhone(EuropeanCVPhone $phone)
    {
        $this->phones[] = $phone;
        $phone->setEuropeanCV($this);
    }

    public function removePhone(EuropeanCVPhone $phone)
    {
        $this->phones->removeElement($phone);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
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

    public function setYear(?int $year): void
    {
        $this->year = $year;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(?int $month): void
    {
        $this->month = $month;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(?int $day): void
    {
        $this->day = $day;
    }

    /**
     * @return EuropeanCVAddress|null
     */
    public function getAddress(): ?EuropeanCVAddress
    {
        return $this->address;
    }

    /**
     * @param EuropeanCVAddress|null $address
     */
    public function setAddress(?EuropeanCVAddress $address): void
    {
        $this->address = $address;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
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

    public function getSex(): ?SexEnum
    {
        return $this->sex;
    }

    public function setSex(?SexEnum $sex): void
    {
        $this->sex = $sex;
    }

    public function getDrivingLicenseOwner(): ?bool
    {
        return $this->drivingLicenseOwner;
    }

    public function setDrivingLicenseOwner(?bool $drivingLicenseOwner): void
    {
        $this->drivingLicenseOwner = $drivingLicenseOwner;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getLanguage(): ?LanguageEnum
    {
        return $this->language;
    }

    public function setLanguage(?LanguageEnum $language): void
    {
        $this->language = $language;
    }

    public function getStyle(): ?StyleEnum
    {
        return $this->style;
    }

    public function setStyle(?StyleEnum $style): void
    {
        $this->style = $style;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getHobbies(): ?string
    {
        return $this->hobbies;
    }

    public function setHobbies(?string $hobbies): void
    {
        $this->hobbies = $hobbies;
    }

    public function getAdditionalInformations(): ?string
    {
        return $this->additionalInformations;
    }

    public function setAdditionalInformations(?string $additionalInformations): void
    {
        $this->additionalInformations = $additionalInformations;
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
     * @return EuropeanCVDigitalSkill[]|Collection
     */
    public function getDigitalSkills(): array|Collection
    {
        return $this->digitalSkills;
    }

    /**
     * @param EuropeanCVDigitalSkill $digitalSkill
     */
    public function addDigitalSkill(EuropeanCVDigitalSkill $digitalSkill)
    {
        $this->digitalSkills[] = $digitalSkill;
        $digitalSkill->setEuropeanCV($this);
    }

    public function removeDigitalSkill(EuropeanCVDigitalSkill $digitalSkill)
    {
        $this->digitalSkills->removeElement($digitalSkill);
    }

    /**
     * Function returns dat of birth in datetime format
     */
    public function getFormattedDateOfBirth() {
        $date = '';
        if (!empty($this->day) && !empty($this->month)) $date .= $this->day . '.' . $this->month . '. ';
        $date .= $this->year;
        return $date;
    }

    public function __clone()
    {
        /*
         * If the entity has an identity, proceed as normal.
         * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/cookbook/implementing-wakeup-or-clone.html
         */
    }
}