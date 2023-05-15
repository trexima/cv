<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Proxy;

#[ORM\Index(fields: ['administrativeAreaLevel1'])]
#[ORM\Index(fields: ['locality'])]
#[ORM\Index(fields: ['sublocalityLevel1'])]
#[ORM\Index(fields: ['route'])]
#[ORM\MappedSuperclass]
class AbstractGoogleAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'string', length: 512)]
    protected ?string $googlePlaceId = null;

    #[ORM\Column(type: 'string', length: 6)]
    protected ?string $languageCode = null;

    #[ORM\Column(type: 'string', length: 255)]
    protected ?string $formattedAddress = null;

    #[ORM\Column(type: 'string', length: 64)]
    protected ?string $country = null;

    #[ORM\Column(type: 'string', length: 2)]
    protected ?string $countryIso2Code = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $administrativeAreaLevel1 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $administrativeAreaLevel2 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $administrativeAreaLevel3 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $locality = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $sublocalityLevel1 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $sublocalityLevel2 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $sublocalityLevel3 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $route = null;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    protected ?string $premise = null;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    protected ?string $subpremise = null;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    protected ?string $streetNumber = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $postalTown = null;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    protected ?string $postalCode = null;

    #[ORM\Column(type: 'float', nullable: true)]
    protected ?float $latitude = null;

    #[ORM\Column(type: 'float', nullable: true)]
    protected ?float $longitude = null;

    #[ORM\Column(type: 'datetime_immutable')]
    protected \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGooglePlaceId(): ?string
    {
        return $this->googlePlaceId;
    }

    public function setGooglePlaceId(string $googlePlaceId): self
    {
        $this->googlePlaceId = $googlePlaceId;

        return $this;
    }

    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function setLanguageCode(string $languageCode): self
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    public function getFormattedAddress(): ?string
    {
        return $this->formattedAddress;
    }

    public function setFormattedAddress(string $formattedAddress): self
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryIso2Code(): ?string
    {
        return $this->countryIso2Code;
    }

    public function setCountryIso2Code(string $countryIso2Code): self
    {
        $this->countryIso2Code = $countryIso2Code;

        return $this;
    }

    public function getAdministrativeAreaLevel1(): ?string
    {
        return $this->administrativeAreaLevel1;
    }

    public function setAdministrativeAreaLevel1(?string $administrativeAreaLevel1): self
    {
        $this->administrativeAreaLevel1 = $administrativeAreaLevel1;

        return $this;
    }

    public function getAdministrativeAreaLevel2(): ?string
    {
        return $this->administrativeAreaLevel2;
    }

    public function setAdministrativeAreaLevel2(?string $administrativeAreaLevel2): self
    {
        $this->administrativeAreaLevel2 = $administrativeAreaLevel2;

        return $this;
    }

    public function getAdministrativeAreaLevel3(): ?string
    {
        return $this->administrativeAreaLevel3;
    }

    public function setAdministrativeAreaLevel3(?string $administrativeAreaLevel3): self
    {
        $this->administrativeAreaLevel3 = $administrativeAreaLevel3;

        return $this;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(?string $locality): self
    {
        $this->locality = $locality;

        return $this;
    }

    public function getSublocalityLevel1(): ?string
    {
        return $this->sublocalityLevel1;
    }

    public function setSublocalityLevel1(?string $sublocalityLevel1): self
    {
        $this->sublocalityLevel1 = $sublocalityLevel1;

        return $this;
    }

    public function getSublocalityLevel2(): ?string
    {
        return $this->sublocalityLevel2;
    }

    public function setSublocalityLevel2(?string $sublocalityLevel2): self
    {
        $this->sublocalityLevel2 = $sublocalityLevel2;

        return $this;
    }

    public function getSublocalityLevel3(): ?string
    {
        return $this->sublocalityLevel3;
    }

    public function setSublocalityLevel3(?string $sublocalityLevel3): self
    {
        $this->sublocalityLevel3 = $sublocalityLevel3;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getPremise(): ?string
    {
        return $this->premise;
    }

    public function setPremise(?string $premise): self
    {
        $this->premise = $premise;

        return $this;
    }

    public function getSubpremise(): ?string
    {
        return $this->subpremise;
    }

    public function setSubpremise(?string $subpremise): self
    {
        $this->subpremise = $subpremise;

        return $this;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getPostalTown(): ?string
    {
        return $this->postalTown;
    }

    public function setPostalTown(?string $postalTown): self
    {
        $this->postalTown = $postalTown;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Helper function to copy data from Google address to this one
     */
    public function copyFrom(AbstractGoogleAddress $to): self
    {
        $to->copyTo($this);

        return $this;
    }

    /**
     * Helper function to copy data from this Google address to another one
     */
    public function copyTo(AbstractGoogleAddress $to): void
    {
        $to->setGooglePlaceId($this->getGooglePlaceId());
        $to->setLanguageCode($this->getLanguageCode());
        $to->setFormattedAddress($this->getFormattedAddress());
        $to->setCountry($this->getCountry());
        $to->setCountryIso2Code($this->getCountryIso2Code());
        $to->setAdministrativeAreaLevel1($this->getAdministrativeAreaLevel1());
        $to->setAdministrativeAreaLevel2($this->getAdministrativeAreaLevel2());
        $to->setAdministrativeAreaLevel3($this->getAdministrativeAreaLevel3());
        $to->setLocality($this->getLocality());
        $to->setSublocalityLevel1($this->getSublocalityLevel1());
        $to->setSublocalityLevel2($this->getSublocalityLevel2());
        $to->setSublocalityLevel3($this->getSublocalityLevel3());
        $to->setRoute($this->getRoute());
        $to->setPremise($this->getPremise());
        $to->setSubpremise($this->getSubpremise());
        $to->setStreetNumber($this->getStreetNumber());
        $to->setPostalTown($this->getPostalTown());
        $to->setPostalCode($this->getPostalCode());
        $to->setLatitude($this->getLatitude());
        $to->setLongitude($this->getLongitude());
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

    public function __toString(): string
    {
        return $this->getFormattedAddress();
    }
}
