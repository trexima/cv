<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Trexima\EuropeanCvBundle\Entity\Enum\DrivingLicenseEnum;

/**
 * EuropeanCV practice
 */
#[ORM\Table(name: 'european_cv_driving_license')]
#[ORM\Index(name: 'driving_license_idx', columns: ['driving_license'])]
#[ORM\UniqueConstraint(columns: ['european_cv_id', 'driving_license'])]
#[ORM\Entity]
class EuropeanCVDrivingLicense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: \Trexima\EuropeanCvBundle\Entity\EuropeanCV::class, inversedBy: 'drivingLicenses')]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?\Trexima\EuropeanCvBundle\Entity\EuropeanCV $europeanCV = null;

    #[ORM\Column(type: 'string', length: 4, nullable: true, enumType: DrivingLicenseEnum::class)]
    private ?DrivingLicenseEnum $drivingLicense = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['unsigned' => true, 'comment' => 'Distance traveled in kilometers'])]
    private ?string $distanceTraveled = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $activeDriver = null;

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

    public function getDrivingLicense(): ?DrivingLicenseEnum
    {
        return $this->drivingLicense;
    }

    public function setDrivingLicense(?DrivingLicenseEnum $drivingLicense): void
    {
        $this->drivingLicense = $drivingLicense;
    }

    public function getDistanceTraveled(): ?string
    {
        return $this->distanceTraveled;
    }

    public function setDistanceTraveled(?string $distanceTraveled): void
    {
        $this->distanceTraveled = $distanceTraveled;
    }

    public function getActiveDriver(): ?bool
    {
        return $this->activeDriver;
    }

    public function setActiveDriver(?bool $activeDriver): void
    {
        $this->activeDriver = $activeDriver;
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