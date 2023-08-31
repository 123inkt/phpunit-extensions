<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TestConstraintValidator extends ConstraintValidator
{
    public const VALUE_ADD_VIOLATION              = 'invalidAddViolation';
    public const VALUE_BUILD_VIOLATION            = 'invalidBuildViolation';
    public const VALUE_BUILD_VIOLATION_AT_PATH    = 'invalidBuildViolationAtPath';
    public const VALUE_BUILD_VIOLATION_PARAMETERS = 'invalidBuildViolationParameters';
    public const VALUE_BUILD_VIOLATION_COMPLETE   = 'invalidBuildViolationComplete';

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (($constraint instanceof TestConstraint) === false) {
            throw new UnexpectedTypeException($constraint, TestConstraint::class);
        }
        if ($value === self::VALUE_ADD_VIOLATION) {
            $this->context->addViolation($constraint->message);
        } elseif ($value === self::VALUE_BUILD_VIOLATION) {
            $this->context->buildViolation($constraint->message)->addViolation();
        } elseif ($value === self::VALUE_BUILD_VIOLATION_AT_PATH) {
            $this->context->buildViolation($constraint->message)->atPath('foo')->setInvalidValue('bar')->addViolation();
        } elseif ($value === self::VALUE_BUILD_VIOLATION_PARAMETERS) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('parameter1', 'foo')
                ->setParameter('parameter2', 'bar')
                ->addViolation();
        } elseif ($value === self::VALUE_BUILD_VIOLATION_COMPLETE) {
            $this->context->buildViolation($constraint->message, ['param' => 'eter'])
                ->setCode('code')
                ->setPlural(2)
                ->setCause('cause')
                ->setTranslationDomain('domain')
                ->setParameter('set', 'parameter')
                ->setParameters(['parameter2' => 'two'])
                ->atPath('foo')
                ->setInvalidValue('bar')
                ->addViolation();
        }
    }
}
