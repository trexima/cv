<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Proxy;
use Trexima\EuropeanCvBundle\Entity\Enum\DrivingLicenseEnum;

/**
 * EuropeanCV driving license.
 */
#[ORM\Table(name: 'european_cv_driving_license')]
#[ORM\Index(columns: ['driving_license'], name: 'driving_license_idx')]
#[ORM\UniqueConstraint(columns: ['european_cv_id', 'driving_license'])]
#[ORM\Entity]
class EuropeanCVDrivingLicense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: EuropeanCV::class, inversedBy: 'drivingLicenses')]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?EuropeanCV $europeanCV = null;

    #[ORM\Column(type: 'string', length: 4, nullable: true, enumType: DrivingLicenseEnum::class)]
    private ?DrivingLicenseEnum $drivingLicense = null;

    #[ORM\Column(
        type: 'integer',
        nullable: true,
        options: [
            'unsigned' => true,
            'comment' => 'Distance traveled in kilometers',
        ],
    )]
    private ?int $distanceTraveled = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $activeDriver = null;

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

    public function getDrivingLicense(): ?DrivingLicenseEnum
    {
        return $this->drivingLicense;
    }

    public function setDrivingLicense(?DrivingLicenseEnum $drivingLicense): self
    {
        $this->drivingLicense = $drivingLicense;

        return $this;
    }

    public function getDistanceTraveled(): ?int
    {
        return $this->distanceTraveled;
    }

    public function setDistanceTraveled(?int $distanceTraveled): self
    {
        $this->distanceTraveled = $distanceTraveled;

        return $this;
    }

    public function getActiveDriver(): ?bool
    {
        return $this->activeDriver;
    }

    public function setActiveDriver(?bool $activeDriver): self
    {
        $this->activeDriver = $activeDriver;

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
