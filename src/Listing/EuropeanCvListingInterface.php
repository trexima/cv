<?php

namespace Trexima\EuropeanCvBundle\Listing;


interface EuropeanCvListingInterface
{
    public function getDrivingLicenseList(): array;
    public function getDigitalSkillList(): array;
}