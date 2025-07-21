<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Trait;

use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
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
        Assert::assertThat($actual, static::getConstraint($expected), $message);
    }

    /**
     * @param string|SplFileInfo|resource $expected Pass either binary data string, a file path or a resource handle
     * @param string|SplFileInfo|resource $actual   Pass either binary data string, a file path or a resource handle
     *
     * @throws Exception
     */
    final public static function assertNotSameImage($expected, $actual, string $message = ''): void
    {
        Assert::assertThat($actual, new LogicalNot(static::getConstraint($expected)), $message);
    }

    /**
     * Override this method to provide a custom constraint for image comparison.
     *
     * @param string|SplFileInfo|resource $expectedHandle
     */
    protected static function getConstraint($expectedHandle): IsSameImageConstraint
    {
        return new IsSameImageConstraint($expectedHandle);
    }
}
