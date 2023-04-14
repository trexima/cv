<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

enum DrivingLicenseEnum: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';
    case E = 'E';
    case T = 'T';

    public function getTitle(): string
    {
        return $this->value;
    }

    public function getIconClass(): ?string
    {
        return match ($this->value) {
            'A' => 'fal fa-motorcycle',
            'B' => 'fal fa-car-side',
            'C' => 'fal fa-truck',
            'D' => 'fal fa-bus',
            'E' => 'fal fa-truck-fast',
            'T' => 'fal fa-tractor',
            default => null,
        };
    }
}