<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\FormErrorController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

/**
 * @extends AbstractControllerTestCase<FormErrorController>
 * @covers \DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase
 * @covers \DR\PHPUnitExtensions\Symfony\Helper\FormAssertion
 */
class FormErrorControllerTest extends AbstractControllerTestCase
{
    public function testInvoke(): void
    {
        $errors   = [new FormError('error')];
        $children = [$this->createMock(FormInterface::class)];
        $config   = $this->createMock(FormConfigInterface::class);

        $this->expectCreateForm(FormType::class)
            ->getNameWillReturn('name')
            ->getConfigWillReturn($config)
            ->getErrorsWillReturn($errors)
            ->allWillReturn($children);

        $result = ($this->controller)();

        static::assertSame('name', $result['name']);
        static::assertSame($errors, iterator_to_array($result['errors']));
        static::assertSame($config, $result['config']);
        static::assertSame($children, $result['all']);
    }

    public function getController(): FormErrorController
    {
        return new FormErrorController();
    }
}
