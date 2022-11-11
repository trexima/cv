<?php

namespace Trexima\EuropeanCvBundle\Listing;

use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class EuropeanCvListing implements EuropeanCvListingInterface
{
    /**
     * @var array
     */
    private $drivingLicenseList;

    /**
     * @var array
     */
    private $digitalSkillList;

    public function __construct(private readonly FileLocator $fileLocator)
    {
    }

    public function getDrivingLicenseList(): array
    {
        if (isset($this->drivingLicenseList)) {
            return $this->drivingLicenseList;
        }

        return $this->drivingLicenseList = Yaml::parseFile($this->fileLocator->locate('@TreximaEuropeanCvBundle/Resources/listing/driving_license.yaml'));
    }

    public function getDigitalSkillList(): array
    {
        if (isset($this->digitalSkillList)) {
            return $this->digitalSkillList;
        }

        return $this->digitalSkillList = Yaml::parseFile($this->fileLocator->locate('@TreximaEuropeanCvBundle/Resources/listing/digital_skill.yaml'));
    }
}