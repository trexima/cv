<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

enum EducationTypeEnum: int
{
    case EDUCATION_ELEMENTARY_SCHOOL = 1;
    case EDUCATION_HIGH_SCHOOL = 2;
    case EDUCATION_UNIVERSITY = 3;
    case EDUCATION_CERTIFICATE = 4;

    public function title(): string
    {
        return match ($this) {
            static::EDUCATION_ELEMENTARY_SCHOOL => 'trexima_european_cv.education_type_enum.education_elementary_school',
            static::EDUCATION_HIGH_SCHOOL => 'trexima_european_cv.education_type_enum.education_high_school',
            static::EDUCATION_UNIVERSITY => 'trexima_european_cv.education_type_enum.education_university',
            static::EDUCATION_CERTIFICATE => 'trexima_european_cv.education_type_enum.education_certificate',
        };
    }
}
