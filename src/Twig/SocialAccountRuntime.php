<?php

namespace Trexima\EuropeanCvBundle\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class SocialAccountRuntime implements RuntimeExtensionInterface
{
    public const PATTERN_FACEBOOK = '/https?:\/\/(?:www\.)?facebook\.com\/([^\/?&]+)/i';
    public const PATTERN_INSTAGRAM = '/https?:\/\/(?:www\.)?instagram\.com\/([^\/?&]+)/i';
    public const PATTERN_LINKEDIN = '/https?:\/\/(?:www\.)?linkedin\.com\/in\/([^\/?&]+)/i';

    /**
     * Return a 'nick' from a social account.
     */
    public function socialAccount(string $url): string
    {
        if (preg_match(self::PATTERN_FACEBOOK, $url, $matches)) {
            $nick = urldecode($matches[1]);
        } elseif (preg_match(self::PATTERN_INSTAGRAM, $url, $matches)) {
            $nick = urldecode($matches[1]);
        } elseif (preg_match(self::PATTERN_LINKEDIN, $url, $matches)) {
            $nick = urldecode($matches[1]);
        } else {
            $nick = preg_replace('/^https?:\/\//i', '', $url);
        }

        return $nick;
    }
}
