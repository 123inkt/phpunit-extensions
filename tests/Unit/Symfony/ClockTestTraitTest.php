<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Unit\Symfony;

use DateTimeImmutable;
use DR\PHPUnitExtensions\Symfony\ClockTestTrait;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;

#[CoversTrait(ClockTestTrait::class)]
class ClockTestTraitTest extends TestCase
{
    use ClockTestTrait;

    public function testTime(): void
    {
        self::assertSame(1634050575, self::time());
    }

    public function testNow(): void
    {
        self::assertSame(1634050575, self::now()->getTimestamp());
    }

    public function testSleep(): void
    {
        self::sleep(123.456);
        self::assertSame(1634050698, self::time());
    }

    public function testAssertNow(): void
    {
        self::assertNow((new DateTimeImmutable())->setTimestamp(1634050575));
    }

    public function testAssertTime(): void
    {
        self::assertSameTime(1634050575, self::now());
    }
}
