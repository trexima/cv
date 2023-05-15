<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

enum SexEnum: int
{
    case MALE = 1;
    case FEMALE = 2;
    case DO_NOT_STATE = 0;
}
