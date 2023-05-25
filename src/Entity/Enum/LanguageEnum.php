<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

/**
 * Language codes by ISO 639-1 standard: https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
 */
enum LanguageEnum: string
{
    case LANGUAGE_SK = 'sk';        // sk: slovenský
    case LANGUAGE_CS = 'cs';        // cs: český
    case LANGUAGE_EN = 'en';        // en: anglický
    case LANGUAGE_DE = 'de';        // de: nemecký
    case LANGUAGE_HU = 'hu';        // hu: maďarský
    case LANGUAGE_RU = 'ru';        // ru: ruský
    case LANGUAGE_UK = 'uk';        // uk: ukrajinský
    case LANGUAGE_PL = 'pl';        // pl: poľský
    case LANGUAGE_FR = 'fr';        // fr: francúzsky
    case LANGUAGE_IT = 'it';        // it: taliansky
    case LANGUAGE_ES = 'es';        // es: španielsky
    case LANGUAGE_PT = 'pt';        // pt: portugalský
    case LANGUAGE_NL = 'nl';        // nl: holandský
    case LANGUAGE_DA = 'da';        // da: dánsky
    case LANGUAGE_FI = 'fi';        // fi: fínsky
    case LANGUAGE_SV = 'sv';        // sv: švédsky
    case LANGUAGE_NO = 'no';        // no: nórsky
    case LANGUAGE_EL = 'el';        // el: grécky
    case LANGUAGE_IS = 'is';        // is: islandský
    case LANGUAGE_GA = 'ga';        // ga: írsky
    case LANGUAGE_LV = 'lv';        // lv: lotyšský
    case LANGUAGE_ET = 'et';        // et: estónsky
    case LANGUAGE_LT = 'lt';        // lt: litovský
    case LANGUAGE_SL = 'sl';        // sl: slovinský
    case LANGUAGE_MT = 'mt';        // mt: maltský
    case LANGUAGE_RO = 'ro';        // ro: rumunský
    case LANGUAGE_BG = 'bg';        // bg: bulharský
    case LANGUAGE_HR = 'hr';        // hr: chorvátsky
    case LANGUAGE_SR = 'sr';        // sr: srbský
    case LANGUAGE_LA = 'la';        // la: latinský
    case LANGUAGE_ZH = 'zh';        // zh: čínsky (mandarínčina)
    case LANGUAGE_VI = 'vi';        // vi: vietnamský
    case LANGUAGE_JA = 'ja';        // ja: japonský
    case LANGUAGE_KO = 'ko';        // ko: kórejský
}
