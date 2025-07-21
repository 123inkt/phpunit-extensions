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

    public function testAssertSamePdf(): void
    {
        $pdf = new SplFileInfo($this->successPdfFile);
        static::assertSamePdf($pdf, $this->createPdf());
    }

    public function testAssertNotSamePdf(): void
    {
        $pdf = new SplFileInfo($this->failedPdfFile);
        static::assertNotSamePdf($pdf, $this->createPdf());
    }

    public function testAssertOnePdfIsEqualToTheOther(): void
    {
        $pdfA = $this->createPdf();
        $pdfB = $this->createPdf();
        static::assertSamePdf($pdfA, $pdfB);
    }

    public function testAssertOnePdfIsNotSameToTheOther(): void
    {
        $pdfA = $this->createPdf();
        $pdfB = $this->createPdf();
        $pdfB->writeHTML('<b>Changed</b>');
        static::assertNotSamePdf($pdfA, $pdfB);
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
