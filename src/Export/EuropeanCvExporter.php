<?php

namespace Trexima\EuropeanCvBundle\Export;

use InvalidArgumentException;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Trexima\EuropeanCvBundle\Entity\Enum\StyleEnum;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EuropeanCvExporter
{
    final public const TYPE_PDF = 'pdf';
    final public const TYPE_DOC = 'doc';

    public function __construct(private readonly string $uploadUrl, private readonly Environment $twig)
    {
    }

    /**
     * @throws MpdfException
     */
    private function createMpdf(array $config): Mpdf
    {
        $config = array_merge([
            'format' => 'A4',
            'tempDir' => sys_get_temp_dir() . '/mpdf',
        ], $config);

        $mpdf = new Mpdf($config);
        $mpdf->use_kwt = true;
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->showImageErrors = true;

        return $mpdf;
    }

    /**
     * Generate HTML content for DOC or PDF.
     *
     * @param EuropeanCV $europeanCV
     * @param string $exportType self::TYPE_PDF|self::TYPE_DOC
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidArgumentException When exportType is not supported
     */
    public function generateContent(EuropeanCV $europeanCV, string $exportType): string
    {
        if (!in_array($exportType, [self::TYPE_PDF, self::TYPE_DOC])) {
            throw new InvalidArgumentException('Invalid export type provided');
        }

        $style = ($europeanCV->getStyle() ?? StyleEnum::STYLE_01)->value;

        return $this->twig->render('@TreximaEuropeanCv/export/styles/' . $style . '.html.twig', [
            'exportType' => $exportType,
            'european_cv' => $europeanCV,
            'image_upload_url' => rtrim($this->uploadUrl, '\\/') . '/images/',
            // PDF requires absolute paths because mPDF needs to download images and connecting
            // to foreign hosts (Cloudflare) is disabled
            'img_use_absolute_path' => $exportType === self::TYPE_PDF,
        ]);
    }

    /**
     * Generate PDF or DOC content.
     *
     * @param EuropeanCV $europeanCV
     * @param string $exportType self::TYPE_PDF|self::TYPE_DOC
     * @param array $config Custom mPDF's configuration
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidArgumentException When exportType is not supported
     * @throws MpdfException
     */
    public function generate(EuropeanCV $europeanCV, string $exportType, array $config = []): string
    {
        $html = $this->generateContent($europeanCV, $exportType);

        switch ($exportType) {
            case self::TYPE_PDF:
                $mpdf = $this->createMpdf($config);
                $mpdf->WriteHTML($html);
                return $mpdf->Output('', 'S');
            case self::TYPE_DOC:
                return $html;
            default:
                throw new InvalidArgumentException('Invalid export type provided');
        }
    }
}
