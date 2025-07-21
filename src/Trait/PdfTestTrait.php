<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Trait;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;
use SplFileInfo;
use TCPDF;

trait PdfTestTrait
{
    use ImageTestTrait;

    /**
     * @param string|SplFileInfo|resource|TCPDF $expected binary data string, a file path, a resource handle or a TCPDF instance to compare to
     */
    public function assertSamePDF($expected, TCPDF $actual, string $message = ''): void
    {
        if ($expected instanceof TCPDF) {
            $expected = $expected->Output('', 'S');
        }

        Assert::assertThat($actual->Output('', 'S'), $this->getConstraint($expected), $message);
    }

    /**
     * @param string|SplFileInfo|resource|TCPDF $expected binary data string, a file path, a resource handle or a TCPDF instance to compare to
     */
    public function assertNotSamePDF($expected, TCPDF $actual, string $message = ''): void
    {
        if ($expected instanceof TCPDF) {
            $expected = $expected->Output('', 'S');
        }

        Assert::assertThat($actual->Output('', 'S'), new LogicalNot($this->getConstraint($expected)), $message);
    }
}
