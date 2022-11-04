<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

enum TitleAfterEnum: string
{
    case TITLE_MBA = 'MBA';
    case TITLE_MPH = 'MPH';
    case TITLE_PHD = 'PhD.';
    case TITLE_ARTD = 'ArtD.';
    case TITLE_CSC = 'CSc.';
    case TITLE_DRSC = 'DrSc.';
    case TITLE_DIS_ART = 'DiS.art';
    case TITLE_DIS = 'DiS';
    case TITLE_EUR_ING = 'EUR ING.';
}
