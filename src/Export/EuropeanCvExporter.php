<?php

namespace Trexima\EuropeanCvBundle\Export;

use Dompdf\Dompdf;
use Mpdf\Mpdf;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Twig\Environment;

class EuropeanCvExporter
{
    final public const TYPE_PDF = 'pdf',
        TYPE_DOC = 'doc';

    public function __construct(private readonly string $uploadUrl, private readonly Environment $twig)
    {
    }

    /**
     * @return Mpdf
     * @throws \Mpdf\MpdfException
     */
    private function createMpdf()
    {
        $mpdf = new Mpdf([
            'format' => 'A4',
            'tempDir' => sys_get_temp_dir().'/mpdf'
        ]);
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->showImageErrors = true;

        return $mpdf;
    }

    /**
     * Generate HTML content for DOC or PDF.
     *
     * @param string $exportType self::TYPE_PDF|self::TYPE_DOC
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generateContent(EuropeanCV $europeanCV, string $exportType)
    {
        return $this->twig->render('@TreximaEuropeanCv/export/european_cv.html.twig', [
            'exportType' => $exportType,
            'european_cv' => $europeanCV,
            'image_upload_url' => ltrim($this->uploadUrl, '\\/').'/images/',
            // PDF require absolute paths because mPDF needs to download images and connecting to foreign hosts(Cloudflare) is disabled
            'img_use_absolute_path' => $exportType === self::TYPE_PDF
        ]);
    }

    /**
     * Generate PDF or DOC content.
     *
     * @param string $exportType self::TYPE_PDF|self::TYPE_DOC
     * @return string
     * @throws \Mpdf\MpdfException
     */
    public function generate(EuropeanCV $europeanCV, string $exportType)
    {
        $html = $this->generateContent($europeanCV, $exportType);
        switch ($exportType) {
            case self::TYPE_PDF:
                // $mpdf = $this->createMpdf();
                // $mpdf->WriteHTML($html);
                // return $mpdf->Output('', 'S');
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);

                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');

                // Render the HTML as PDF
                $dompdf->render();

                // Output the generated PDF to Browser
                return $dompdf->output();
                break;
            case self::TYPE_DOC:
                return $html;
                break;
            default:
                trigger_error('This output type isn\'t supported');
        }
    }
}