<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Symfony\Helper;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

use function PHPUnit\Framework\atLeastOnce;

/**
 * @internal
 */
class FormAssertion
{
    public function __construct(public readonly FormInterface&MockObject $form, private readonly TestCase $testCase)
    {
    }

    public function handleRequest(Request $request): self
    {
        $this->form->expects(atLeastOnce())->method('handleRequest')->with($request)->willReturnSelf();

        return $this;
    }

    /**
     * @param array<string, int|string|float|null> $keyValueData
     */
    public function getWillReturn(array $keyValueData): self
    {
        $this->form->expects(atLeastOnce())
            ->method('get')
            ->willReturnCallback(
                function ($key) use ($keyValueData) {
                    if (array_key_exists($key, $keyValueData) === false) {
                        // @codeCoverageIgnoreStart
                        throw new RuntimeException('Missing key in data: ' . $key);
                        // @codeCoverageIgnoreEnd
                    }

                    $mock = (new MockBuilder($this->testCase, FormInterface::class))->getMock();
                    $mock->method('getData')->willReturn($keyValueData[$key]);

                    return $mock;
                }
            );

        return $this;
    }

    public function isValidWillReturn(bool $value): self
    {
        $this->form->expects(atLeastOnce())->method('isValid')->willReturn($value);

        return $this;
    }

    public function isSubmittedWillReturn(bool $value): self
    {
        $this->form->expects(atLeastOnce())->method('isSubmitted')->willReturn($value);

        return $this;
    }

    public function getDataWillReturn(mixed $data): self
    {
        $this->form->expects(atLeastOnce())->method('getData')->willReturn($data);

        return $this;
    }

    public function getNameWillReturn(string $name): self
    {
        $this->form->expects(atLeastOnce())->method('getName')->willReturn($name);

        return $this;
    }

    /**
     * @param FormError[] $errors
     */
    public function getErrorsWillReturn(array $errors): self
    {
        $this->form->expects(atLeastOnce())->method('getErrors')->willReturn(new FormErrorIterator($this->form, $errors));

        return $this;
    }

    public function getConfigWillReturn(FormConfigInterface $config): self
    {
        $this->form->expects(atLeastOnce())->method('getConfig')->willReturn($config);

        return $this;
    }

    /**
     * @param FormInterface[] $forms
     */
    public function allWillReturn(array $forms): self
    {
        $this->form->expects(atLeastOnce())->method('all')->willReturn($forms);

        return $this;
    }

    public function createViewWillReturn(FormView $formView): self
    {
        $this->form->expects(atLeastOnce())->method('createView')->willReturn($formView);

        return $this;
    }
}
