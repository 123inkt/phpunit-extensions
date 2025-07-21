<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Trait;

use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;
use SplFileInfo;
use TCPDF;

trait PdfTestTrait
{
    /**
     * @param string|SplFileInfo|resource|TCPDF $expected binary data string, a file path, a resource handle or a TCPDF instance to compare to
     */
    final public function assertSamePdf($expected, TCPDF $actual, string $message = ''): void
    {
        if ($expected instanceof TCPDF) {
            $expected = $expected->Output('', 'S');
        }

        Assert::assertThat($actual->Output('', 'S'), new IsSameImageConstraint($expected), $message);
    }

    /**
     * @param string|SplFileInfo|resource|TCPDF $expected binary data string, a file path, a resource handle or a TCPDF instance to compare to
     */
    final public function assertNotSamePdf($expected, TCPDF $actual, string $message = ''): void
    {
        if ($expected instanceof TCPDF) {
            $expected = $expected->Output('', 'S');
        }

        Assert::assertThat($actual->Output('', 'S'), new LogicalNot(new IsSameImageConstraint($expected)), $message);
    }
}
