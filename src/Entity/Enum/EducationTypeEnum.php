<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

enum EducationTypeEnum: int
{
    case EDUCATION_ELEMENTARY_SCHOOL = 1;
    case EDUCATION_HIGH_SCHOOL = 2;
    case EDUCATION_UNIVERSITY = 3;
    case EDUCATION_CERTIFICATE = 4;
}