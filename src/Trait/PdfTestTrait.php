<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Trait;

use Com\Tecnick\Pdf\Tcpdf as TcLibTcpdf;
use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
use DR\PHPUnitExtensions\Renderer\ImageDiffRenderer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;
use SplFileInfo;
use TCPDF as LegacyTcpdf;

trait PdfTestTrait
{
    /**
     * @param string|SplFileInfo|resource|LegacyTcpdf|TcLibTcpdf $expected binary data string, a file path as SplFileInfo,
     *                                                                     a resource handle or a PDF instance to compare to
     */
    final public function assertSamePdf($expected, LegacyTcpdf|TcLibTcpdf $actual, string $message = ''): void
    {
        if ($expected instanceof LegacyTcpdf) {
            $expected = (string)$expected->Output('', 'S');
        }
        if ($expected instanceof TcLibTcpdf) {
            $expected = $expected->getOutPDFString();
        }
        if ($actual instanceof LegacyTcpdf) {
            $actual = (string)$actual->Output('', 'S');
        }
        if ($actual instanceof TcLibTcpdf) {
            $actual = $actual->getOutPDFString();
        }

        Assert::assertThat($actual, new IsSameImageConstraint($expected, new ImageDiffRenderer()), $message);
    }

    /**
     * @param string|SplFileInfo|resource|LegacyTcpdf|TcLibTcpdf $expected binary data string, a file path as SplFileInfo,
     *                                                                     a resource handle or a PDF instance to compare to
     */
    final public function assertNotSamePdf($expected, LegacyTcpdf|TcLibTcpdf $actual, string $message = ''): void
    {
        if ($expected instanceof LegacyTcpdf) {
            $expected = (string)$expected->Output('', 'S');
        }
        if ($expected instanceof TcLibTcpdf) {
            $expected = $expected->getOutPDFString();
        }
        if ($actual instanceof LegacyTcpdf) {
            $actual = (string)$actual->Output('', 'S');
        }
        if ($actual instanceof TcLibTcpdf) {
            $actual = $actual->getOutPDFString();
        }

        Assert::assertThat($actual, new LogicalNot(new IsSameImageConstraint($expected, new ImageDiffRenderer())), $message);
    }
}
