<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Trait;

use Closure;
use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
use DR\PHPUnitExtensions\Renderer\ImageDiffRenderer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Exception;
use SplFileInfo;

trait ImageTestTrait
{
    /**
     * @param string|SplFileInfo|resource $expected Pass either binary data string, a file path or a resource handle
     * @param string|SplFileInfo|resource $actual   Pass either binary data string, a file path or a resource handle
     *
     * @throws Exception
     */
    final public static function assertSameImage($expected, $actual, string $message = ''): void
    {
        Assert::assertThat($actual, new IsSameImageConstraint($expected, new ImageDiffRenderer()), $message);
    }

    /**
     * @param string|SplFileInfo|resource $expected Pass either binary data string, a file path or a resource handle
     * @param string|SplFileInfo|resource $actual   Pass either binary data string, a file path or a resource handle
     *
     * @throws Exception
     */
    final public static function assertNotSameImage($expected, $actual, string $message = ''): void
    {
        Assert::assertThat($actual, new LogicalNot(new IsSameImageConstraint($expected, new ImageDiffRenderer())), $message);
    }
}
