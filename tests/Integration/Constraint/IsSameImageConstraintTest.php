<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Constraint;

use Closure;
use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
use DR\PHPUnitExtensions\Trait\ImageTestTrait;
use Imagick;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(IsSameImageConstraint::class)]
#[CoversClass(ImageTestTrait::class)]
class IsSameImageConstraintTest extends TestCase
{
    use ImageTestTrait;

    private string $imageA;
    private string $imageB;
    private ?Closure $callback = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->callback = null;
        $this->imageA   = dirname(__DIR__, 2) . '/Resources/Constraint/white-a.png';
        $this->imageB   = dirname(__DIR__, 2) . '/Resources/Constraint/white-b.png';
    }

    public function testAssertSplFileInfo(): void
    {
        $fileA1 = new SplFileInfo($this->imageA);
        $fileA2 = new SplFileInfo($this->imageA);
        $fileB  = new SplFileInfo($this->imageB);
        static::assertSameImage($fileA1, $fileA2);
        static::assertNotSameImage($fileA1, $fileB);
    }

    public function testAssertBinaryData(): void
    {
        $dataA1 = file_get_contents($this->imageA);
        $dataA2 = file_get_contents($this->imageA);
        $dataB  = file_get_contents($this->imageB);
        static::assertNotFalse($dataA1);
        static::assertNotFalse($dataA2);
        static::assertNotFalse($dataB);

        static::assertSameImage($dataA1, $dataA2);
        static::assertNotSameImage($dataA1, $dataB);
    }

    public function testAssertResource(): void
    {
        $dataA1 = fopen($this->imageA, 'rb');
        $dataA2 = fopen($this->imageA, 'rb');
        $dataB  = fopen($this->imageB, 'rb');
        static::assertNotFalse($dataA1);
        static::assertNotFalse($dataA2);
        static::assertNotFalse($dataB);

        static::assertSameImage($dataA1, $dataA2);
        static::assertNotSameImage($dataA1, $dataB);
    }

    public function testAssertWithCallback(): void
    {
        $fileA           = new SplFileInfo($this->imageA);
        $fileB           = new SplFileInfo($this->imageB);
        $callbackInvoked = false;

        $this->callback = function ($diff, $expected, $actual) use (&$callbackInvoked) : void {
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
    protected function getConstraint($expectedHandle): IsSameImageConstraint
    {
        return new IsSameImageConstraint($expectedHandle, $this->callback);
    }
}
