<?php

namespace DR\PHPUnitExtensions\Symfony\Helper;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

use function DR\PHPUnitExtensions\Mock\consecutive;
use function PHPUnit\Framework\atLeastOnce;

class ConstraintViolationBuilderAssertion
{
    /**
     * @internal Instance should not be made directly, use AbstractConstraintValidatorTestCase::expectBuildViolation
     * @see      AbstractConstraintValidatorTestCase::expectBuildViolation
     */
    public function __construct(public readonly ConstraintViolationBuilderInterface&MockObject $violationBuilder)
    {
    }

    public function expectSetInvalidValue(mixed $value): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('setInvalidValue')->with($value)->willReturnSelf();

        return $this;
    }

    public function expectSetPlural(int $number): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('setPlural')->with($number)->willReturnSelf();

        return $this;
    }

    public function expectSetCause(mixed $cause): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('setCause')->with($cause)->willReturnSelf();

        return $this;
    }

    public function expectSetTranslationDomain(string $translationDomain): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('setTranslationDomain')->with($translationDomain)->willReturnSelf();

        return $this;
    }

    /**
     * @param array<int|string, mixed> $parameters
     */
    public function expectSetParameters(array $parameters): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('setParameters')->with($parameters)->willReturnSelf();

        return $this;
    }

    public function expectSetParameter(string $key, mixed $value): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('setParameter')->with($key, $value)->willReturnSelf();

        return $this;
    }

    /**
     * @param array<int, array<string, mixed>> $parameters
     */
    public function expectSetParameterWithConsecutive(array $parameters): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('setParameter')->with(...consecutive(...$parameters))->willReturnSelf();

        return $this;
    }

    public function expectAtPath(string $path): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('atPath')->with($path)->willReturnSelf();

        return $this;
    }

    public function expectAddViolation(): self
    {
        $this->violationBuilder->expects(atLeastOnce())->method('addViolation')->willReturnSelf();

        return $this;
    }
}
