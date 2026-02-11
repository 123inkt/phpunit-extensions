<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Symfony;

use DR\PHPUnitExtensions\Symfony\Helper\ConstraintViolationBuilderAssertion;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\DataMapper\DataMapper;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * @template V of ConstraintValidator
 * @template C of Constraint
 */
abstract class AbstractConstraintValidatorTestCase extends TestCase
{
    protected const IGNORE_INVALID_VALUE = 'UT_IGNORE_INVALID_VALUE';

    /** @var V */
    protected ConstraintValidator $validator;

    /** @var C */
    protected Constraint $constraint;

    protected ExecutionContextInterface&MockObject $executionContext;
    protected ConstraintViolationBuilder&MockObject $violationBuilder;
    protected Form $form;

    /**
     * @return V
     */
    abstract protected function getValidator(): ConstraintValidator;

    /**
     * @return C
     */
    abstract protected function getConstraint(): Constraint;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->violationBuilder = $this->createMock(ConstraintViolationBuilder::class);
        $this->executionContext = $this->createMock(ExecutionContextInterface::class);
        $this->executionContext->method('getRoot')->willReturn($this->initRootForm());

        $this->validator  = $this->getValidator();
        $this->constraint = $this->getConstraint();

        $this->validator->initialize($this->executionContext);
    }

    protected function initRootForm(): Form
    {
        $formBuilder = new FormBuilder('foobar', null, new EventDispatcher(), (new FormFactoryBuilder())->getFormFactory());
        $formBuilder->setCompound(true);
        $formBuilder->setDataMapper(new DataMapper());
        $this->form = new Form($formBuilder);

        return $this->form;
    }

    /**
     * @throws Exception
     */
    protected function assertHandlesIncorrectConstraintType(mixed $value = null): void
    {
        $this->executionContext->expects(self::never())->method(self::anything());
        $this->violationBuilder->expects(self::never())->method(self::anything());

        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate($value, $this->createMock(Constraint::class));
    }

    protected function expectNoViolations(): void
    {
        $this->executionContext->expects(self::never())->method('buildViolation');
        $this->executionContext->expects(self::never())->method('addViolation');

        $this->violationBuilder->expects(self::never())->method(self::anything());
    }

    /**
     * Expect a violation to be added using addViolation method.
     * e.g. $this->context->addViolation($constraint->message);
     *
     * @param array<int|string, mixed> $parameters
     */
    protected function expectViolation(string $message, array $parameters = []): void
    {
        $this->executionContext->expects(self::once())->method('addViolation')->with($message, $parameters);

        $this->violationBuilder->expects(self::never())->method(self::anything());
    }

    /**
     * Expect a violation to be created using the violation builder.
     * e.g. $this->context->buildViolation($constraint->message)->atPath('price')->setInvalidValue($price)->addViolation();
     *
     * @param array<int|string, mixed> $parameters
     *
     * @deprecated use ::expectBuildViolation
     */
    protected function expectViolationViaBuilder(
        string $message,
        array $parameters = [],
        ?string $atPath = null,
        mixed $invalidValue = self::IGNORE_INVALID_VALUE
    ): void {
        $this->executionContext->expects(self::once())->method('buildViolation')->with($message, $parameters)->willReturn($this->violationBuilder);
        if ($atPath !== null) {
            $this->violationBuilder->expects(self::once())->method('atPath')->with($atPath)->willReturnSelf();
        }
        if ($invalidValue !== self::IGNORE_INVALID_VALUE) {
            $this->violationBuilder->expects(self::once())->method('setInvalidValue')->with($invalidValue)->willReturnSelf();
        }
        $this->violationBuilder->expects(self::once())->method('addViolation');
    }

    /**
     * Expect a violation to be created using the violation builder.
     * Example:
     * <code>
     *     $this->context
     *          ->buildViolation($constraint->message)
     *          ->atPath('price')
     *          ->setInvalidValue(5.0)
     *          ->addViolation();
     * </code>
     * Usage:
     * <code>
     *     $this->expectBuildViolation('message')
     *          ->expectAtPath('price')
     *          ->expectSetInvalidValue(5.0)
     *          ->expectAddViolation();
     * </code>
     *
     * @param array<int|string, mixed> $parameters
     */
    protected function expectBuildViolation(string $message, array $parameters = []): ConstraintViolationBuilderAssertion
    {
        $this->executionContext->expects(self::once())->method('buildViolation')->with($message, $parameters)->willReturn($this->violationBuilder);

        return new ConstraintViolationBuilderAssertion($this->violationBuilder);
    }
}
