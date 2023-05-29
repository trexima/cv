<?php

namespace Trexima\EuropeanCvBundle\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Twig\Extension\RuntimeExtensionInterface;

class ImageTagRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly string $uploadDir,
        private readonly string $uploadUrl,
        private readonly Packages $assetsPackages,
        private readonly FileLocator $fileLocator,
        private readonly UrlHelper $urlHelper,
    ) {
    }

    public function imageTag(string $imagePath, int $width, bool $useAbsolutePath = false): string
    {
        [$absolutePath, $webPath] = $this->getPaths($imagePath);

        if (!file_exists($absolutePath)) {
            return '';
        }

        [$originalWidth, $originalHeight] = getimagesize($absolutePath);
        $height = round($originalHeight / $originalWidth * $width);
        $src = $useAbsolutePath ? $absolutePath : $webPath;

        return "<img src='$src' width='$width' height='$height' alt=''/>";
    }

    public function imageUrl(string $imagePath, bool $useAbsolutePath = false): string
    {
        [$absolutePath, $webPath] = $this->getPaths($imagePath);

        if (!file_exists($absolutePath)) {
            return '';
        }

        return $useAbsolutePath ? $absolutePath : $webPath;
    }

    /**
     * @param string $imagePath
     * @return array An array with absolute path and web url
     */
    private function getPaths(string $imagePath): array
    {
        if (str_starts_with($imagePath, '@')) {
            $bundlePublicPath = preg_replace('/^@.*?\/assets/', 'bundles/treximaeuropeancv', $imagePath);
            $webPath = $this->urlHelper->getAbsoluteUrl($this->assetsPackages->getUrl($bundlePublicPath));
            $absolutePath = $this->fileLocator->locate($imagePath);
        } else {
            $uploadImagePath = '/images/' . ltrim($imagePath, '\\/');
            $webPath = $this->uploadUrl . $uploadImagePath;
            $absolutePath = $this->uploadDir . $uploadImagePath;
        }

        return [$absolutePath, $webPath];
    }
}
