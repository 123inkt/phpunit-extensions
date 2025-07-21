<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Constraint;

use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
use DR\PHPUnitExtensions\Trait\ImageTestTrait;
use Imagick;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(IsSameImageConstraint::class)]
#[CoversTrait(ImageTestTrait::class)]
class IsSameImageConstraintCallbackTest extends TestCase
{
    use ImageTestTrait;

    private string $imageA;
    private string $imageB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageA = dirname(__DIR__, 2) . '/Resources/Constraint/white-a.png';
        $this->imageB = dirname(__DIR__, 2) . '/Resources/Constraint/white-b.png';
    }

    public function testAssertWithCallback(): void
    {
        $fileA           = new SplFileInfo($this->imageA);
        $fileB           = new SplFileInfo($this->imageB);
        $callbackInvoked = false;

        $callback = function ($diff, $expected, $actual) use (&$callbackInvoked): void {
            static::assertInstanceOf(Imagick::class, $diff);
            static::assertInstanceOf(Imagick::class, $expected);
            static::assertInstanceOf(Imagick::class, $actual);
            $callbackInvoked = true;
        };

        Assert::assertThat($fileB, new LogicalNot(new IsSameImageConstraint($fileA, $callback)));
        static::assertTrue($callbackInvoked);
    }
}
