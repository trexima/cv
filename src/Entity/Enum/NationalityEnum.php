<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

/**
 * Country codes by ISO 3166-1 Alpha-2 standard.
 */
enum NationalityEnum: string
{
    case NATIONALITY_SK = 'SK';        // SK: slovenská
    case NATIONALITY_CZ = 'CZ';        // CZ: česká
    case NATIONALITY_GB = 'GB';        // GB: anglická
    case NATIONALITY_DE = 'DE';        // DE: nemecká
    case NATIONALITY_HU = 'HU';        // HU: maďarská
    case NATIONALITY_RU = 'RU';        // RU: ruská
    case NATIONALITY_UA = 'UA';        // UA: ukrajinská
    case NATIONALITY_PL = 'PL';        // PL: poľská
    case NATIONALITY_FR = 'FR';        // FR: francúzska
    case NATIONALITY_IT = 'IT';        // IT: talianska
    case NATIONALITY_ES = 'ES';        // ES: španielska
    case NATIONALITY_PT = 'PT';        // PT: portugalská
    case NATIONALITY_NL = 'NL';        // NL: holandská
    case NATIONALITY_DK = 'DK';        // DK: dánska
    case NATIONALITY_FI = 'FI';        // FI: fínska
    case NATIONALITY_SE = 'SE';        // SE: švédska
    case NATIONALITY_NO = 'NO';        // NO: nórska
    case NATIONALITY_GR = 'GR';        // GR: grécka
    case NATIONALITY_IS = 'IS';        // IS: islandská
    case NATIONALITY_IE = 'IE';        // IE: írska
    case NATIONALITY_LV = 'LV';        // LV: lotyšská
    case NATIONALITY_EE = 'EE';        // EE: estónska
    case NATIONALITY_LT = 'LT';        // LT: litovská
    case NATIONALITY_SI = 'SI';        // SI: slovinská
    case NATIONALITY_MT = 'MT';        // MT: maltská
    case NATIONALITY_RO = 'RO';        // RO: rumunská
    case NATIONALITY_BG = 'BG';        // BG: bulharská
    case NATIONALITY_HR = 'HR';        // HR: chorvátska
    case NATIONALITY_RS = 'RS';        // RS: srbská
    case NATIONALITY_CN = 'CN';        // CN: čínska
    case NATIONALITY_VN = 'VN';        // VN: vietnamská
    case NATIONALITY_JP = 'JP';        // JP: japonská
    case NATIONALITY_KR = 'KR';        // KR: kórejská
}
