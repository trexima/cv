<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

enum EducationEnum: string
{
    case EDUCATION_9 = 'Základné vzdelanie';
    case EDUCATION_1 = 'Nižšie stredné odborné vzdelanie';
    case EDUCATION_2 = 'Stredné odborné vzdelanie';
    case EDUCATION_3 = 'Úplné stredné odborné vzdelanie';
    case EDUCATION_4 = 'Úplné stredné všeobecné vzdelanie (gymnázium)';
    case EDUCATION_5 = 'Vyššie odborné vzdelanie';
    case EDUCATION_6 = 'Vysokoškolské vzdelanie prvého stupňa';
    case EDUCATION_7 = 'Vysokoškolské vzdelanie druhého stupňa';
    case EDUCATION_8 = 'Vysokoškolské vzdelanie tretieho stupňa';
}
