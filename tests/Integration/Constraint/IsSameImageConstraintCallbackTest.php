<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Constraint;

use Closure;
use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
use DR\PHPUnitExtensions\Trait\ImageTestTrait;
use Imagick;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(IsSameImageConstraint::class)]
#[CoversTrait(ImageTestTrait::class)]
class IsSameImageConstraintCallbackTest extends TestCase
{
    use ImageTestTrait;

    private string $imageA;
    private string $imageB;
    private static ?Closure $callback = null;

    protected function setUp(): void
    {
        parent::setUp();
        self::$callback = null;
        $this->imageA   = dirname(__DIR__, 2) . '/Resources/Constraint/white-a.png';
        $this->imageB   = dirname(__DIR__, 2) . '/Resources/Constraint/white-b.png';
    }

    public function testAssertWithCallback(): void
    {
        $fileA           = new SplFileInfo($this->imageA);
        $fileB           = new SplFileInfo($this->imageB);
        $callbackInvoked = false;

        self::$callback = function ($diff, $expected, $actual) use (&$callbackInvoked): void {
            static::assertInstanceOf(Imagick::class, $diff);
            static::assertInstanceOf(Imagick::class, $expected);
            static::assertInstanceOf(Imagick::class, $actual);
            $callbackInvoked = true;
        };

        static::assertNotSameImage($fileA, $fileB);
        static::assertTrue($callbackInvoked);
    }

    /**
     * @param string|SplFileInfo|resource $expectedHandle
     */
    protected static function getConstraint($expectedHandle): IsSameImageConstraint
    {
        return new IsSameImageConstraint($expectedHandle, self::$callback);
    }
}
