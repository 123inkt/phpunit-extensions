<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Constraint;

use DR\PHPUnitExtensions\Constraint\IsSameImageConstraint;
use DR\PHPUnitExtensions\Renderer\ImageDiffRendererInterface;
use DR\PHPUnitExtensions\Trait\ImageTestTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(IsSameImageConstraint::class)]
#[CoversTrait(ImageTestTrait::class)]
#[RequiresPhpExtension('imagick')]
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

    public function testAssertWithRenderer(): void
    {
        $fileA = new SplFileInfo($this->imageA);
        $fileB = new SplFileInfo($this->imageB);

        $renderer = $this->createMock(ImageDiffRendererInterface::class);
        $renderer->expects(static::once())->method('render')->willReturn('rendered');

        Assert::assertThat($fileB, new LogicalNot(new IsSameImageConstraint($fileA, $renderer)));
    }
}
