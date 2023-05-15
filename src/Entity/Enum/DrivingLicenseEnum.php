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
        return match ($this) {
            self::A => 'fal fa-motorcycle',
            self::B => 'fal fa-car-side',
            self::C => 'fal fa-truck',
            self::D => 'fal fa-bus',
            self::E => 'fal fa-truck-fast',
            self::T => 'fal fa-tractor',
            default => null,
        };
    }
}
