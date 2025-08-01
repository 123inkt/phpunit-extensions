<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Constraint;

use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
use DR\PHPUnitExtensions\Trait\ImageTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(IsSameImageConstraint::class)]
#[CoversTrait(ImageTestTrait::class)]
#[RequiresPhpExtension('imagick')]
class IsSameImageConstraintTest extends TestCase
{
    use ImageTestTrait;

    private string $imageA;
    private string $imageB;
    private string $imageMulti;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageA     = dirname(__DIR__, 2) . '/Resources/Constraint/white-a.png';
        $this->imageB     = dirname(__DIR__, 2) . '/Resources/Constraint/white-b.png';
        $this->imageMulti = dirname(__DIR__, 2) . '/Resources/Constraint/multi-image.gif';
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

    public function testAssertWithBadImage(): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Image compare failed due to Imagick error');
        static::assertSameImage('foobar', 'foobar');
    }

    public function testAssertWithBadImageResource(): void
    {
        $handle = fopen($this->imageA, 'rb');
        static::assertNotFalse($handle);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Image compare failed due to Imagick error');
        static::assertSameImage('foobar', $handle);
    }

    public function testAssertIncorrectImageCount(): void
    {
        $fileA = new SplFileInfo($this->imageA);
        $fileB = new SplFileInfo($this->imageMulti);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Expected an image count of 1 but received count of 2');
        static::assertNotSameImage($fileA, $fileB);
    }
}
