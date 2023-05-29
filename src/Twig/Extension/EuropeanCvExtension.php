<?php

namespace Trexima\EuropeanCvBundle\Twig\Extension;

use Trexima\EuropeanCvBundle\Twig\AtomicDateRuntime;
use Trexima\EuropeanCvBundle\Twig\EmogrifierRuntime;
use Trexima\EuropeanCvBundle\Twig\ImageTagRuntime;
use Trexima\EuropeanCvBundle\Twig\SocialAccountRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EuropeanCvExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('european_cv_image_tag', [ImageTagRuntime::class, 'imageTag'], ['is_safe' => ['html']]),
            new TwigFunction('european_cv_image_url', [ImageTagRuntime::class, 'imageUrl']),
            new TwigFunction('european_cv_emogrify', [EmogrifierRuntime::class, 'emogrify'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('european_cv_atomic_date', [AtomicDateRuntime::class, 'format']),
            new TwigFilter('european_cv_social_account', [SocialAccountRuntime::class, 'socialAccount']),
        ];
    }
}
