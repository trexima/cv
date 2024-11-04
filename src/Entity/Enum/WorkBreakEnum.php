<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

/**
 * Enum for Work break types.
 */
enum WorkBreakEnum: int
{
    case MATERNITY_LEAVE = 1;
    case LABOR_OFFICE = 2;
    case HEALTH_REASONS = 3;
    case LOSS_OF_A_CLOSE_PERSON = 4;
    case CARE_FOR_OTHER_PERSON = 5;
    case SABBATICAL = 6;
    case TRAVEL = 7;
    case RELOCATION = 8;
    case EDUCATION = 9;
}
