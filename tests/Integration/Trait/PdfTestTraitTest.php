<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Trait;

use Com\Tecnick\Pdf\Tcpdf as TcLibTcpdf;
use DR\PHPUnitExtensions\Trait\PdfTestTrait;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use TCPDF as LegacyTcpdf;

#[CoversTrait(PdfTestTrait::class)]
#[RequiresPhpExtension('imagick')]
class PdfTestTraitTest extends TestCase
{
    use PdfTestTrait;

    private string $successPdfFile = __DIR__ . '/../../Resources/Trait/test-success.pdf';
    private string $failedPdfFile = __DIR__ . '/../../Resources/Trait/test-failed.pdf';

    public function testAssertSamePdf(): void
    {
        $pdf = new SplFileInfo($this->successPdfFile);
        static::assertSamePdf($pdf, $this->createLegacyPdf());
    }

    public function testAssertNotSamePdf(): void
    {
        $pdf = new SplFileInfo($this->failedPdfFile);
        static::assertNotSamePdf($pdf, $this->createLegacyPdf());
    }

    public function testAssertOnePdfIsEqualToTheOther(): void
    {
        $pdfA = $this->createLegacyPdf();
        $pdfB = $this->createLegacyPdf();
        static::assertSamePdf($pdfA, $pdfB);
    }

    public function testAssertOnePdfIsNotSameToTheOther(): void
    {
        $pdfA = $this->createLegacyPdf();
        $pdfB = $this->createLegacyPdf();
        $pdfB->writeHTML('<b>Changed</b>');
        static::assertNotSamePdf($pdfA, $pdfB);
    }

    public function testAssertOneTcLibPdfIsEqualToTheOther(): void
    {
        $pdfA = $this->createTcLibPdf();
        $pdfB = $this->createTcLibPdf();
        static::assertSamePdf($pdfA, $pdfB);
    }

    public function testAssertOneTcLibPdfIsNotSameToTheOther(): void
    {
        $pdfA = $this->createTcLibPdf();
        $pdfB = $this->createTcLibPdf(true);
        static::assertNotSamePdf($pdfA, $pdfB);
    }

    private function createLegacyPdf(): LegacyTcpdf
    {
        $pdf = new LegacyTcpdf('P', 'mm', [72, 100]);
        $pdf->setAuthor('Author');
        $pdf->setCreator('Creator');
        $pdf->setPDFVersion('1.6');
        $pdf->setLeftMargin(0);
        $pdf->setRightMargin(0);
        $pdf->setTopMargin(0);
        $pdf->AddPage();
        $pdf->writeHTML('<b>Foobar</b>');

        return $pdf;
    }

    private function createTcLibPdf(bool $withRectangle = false): TcLibTcpdf
    {
        $pdf = new TcLibTcpdf();
        $pdf->addPage();
        if ($withRectangle) {
            $pdf->page->addContent("0 0 100 100 re f\n");
        }

        return $pdf;
    }
}
