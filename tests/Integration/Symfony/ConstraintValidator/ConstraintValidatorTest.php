<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\ConstraintValidator;

use DR\PHPUnitExtensions\Symfony\AbstractConstraintValidatorTestCase;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Constraint\TestConstraint;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Constraint\TestConstraintValidator;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @extends AbstractConstraintValidatorTestCase<TestConstraintValidator, TestConstraint>
 * @covers \DR\PHPUnitExtensions\Symfony\AbstractConstraintValidatorTestCase
 */
class ConstraintValidatorTest extends AbstractConstraintValidatorTestCase
{
    protected function getValidator(): ConstraintValidator
    {
        return new TestConstraintValidator();
    }

    protected function getConstraint(): Constraint
    {
        return new TestConstraint();
    }

    public function testInitRootForm(): void
    {
        $form = $this->initRootForm();
        static::assertSame('foobar', $form->getName());
    }

    /**
     * @throws Exception
     */
    public function testValidateWrongConstraintType(): void
    {
        $this->assertHandlesIncorrectConstraintType();
    }

    public function testValid(): void
    {
        $this->expectNoViolations();
        $this->validator->validate('foo', $this->constraint);
    }

    public function testAddViolation(): void
    {
        $this->expectViolation($this->constraint->message);
        $this->validator->validate(TestConstraintValidator::VALUE_ADD_VIOLATION, $this->constraint);
    }

    public function testBuildViolation(): void
    {
        $this->expectViolationViaBuilder($this->constraint->message);
        $this->validator->validate(TestConstraintValidator::VALUE_BUILD_VIOLATION, $this->constraint);
    }

    public function testBuildViolationAtPath(): void
    {
        $this->expectViolationViaBuilder($this->constraint->message, atPath: 'foo', invalidValue: 'bar');
        $this->validator->validate(TestConstraintValidator::VALUE_BUILD_VIOLATION_AT_PATH, $this->constraint);
    }
}
