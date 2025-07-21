<?php

namespace DR\PHPUnitExtensions\Tests\Integration\Trait;

use DR\PHPUnitExtensions\Trait\PdfTestTrait;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use TCPDF;

#[CoversTrait(PdfTestTrait::class)]
class PdfTestTraitTest extends TestCase
{
    use PdfTestTrait;

    private string $successPdfFile = __DIR__ . '/../../Resources/Trait/test-success.pdf';
    private string $failedPdfFile = __DIR__ . '/../../Resources/Trait/test-failed.pdf';

    public function testAssertSamePDF(): void
    {
        $pdfA = new SplFileInfo($this->successPdfFile);
        $pdfB = new SplFileInfo($this->successPdfFile);

        static::assertSamePdf($pdfA, $this->createPdf());
        static::assertNotSamePdf($pdfB, $this->createPdf());
    }

    private function createPdf(): TCPDF
    {
        $pdf = new TCPDF('P', 'mm', [72, 100]);
        $pdf->setAuthor("Author");
        $pdf->setCreator("Creator");
        $pdf->setPDFVersion("1.6");
        $pdf->setLeftMargin(0);
        $pdf->setRightMargin(0);
        $pdf->setTopMargin(0);
        $pdf->AddPage();
        $pdf->writeHTML('<b>Foobar</b>');

        return $pdf;
    }
}
