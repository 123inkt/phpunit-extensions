<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Symfony\Helper\FormAssertion;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\FormWithSubmitController;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends AbstractControllerTestCase<FormWithSubmitController>
 */
#[CoversClass(AbstractControllerTestCase::class)]
#[CoversClass(FormAssertion::class)]
class FormWithSubmitControllerTest extends AbstractControllerTestCase
{
    public function testInvoke(): void
    {
        $request = new Request(['foo' => 'bar']);
        $this->expectCreateForm(FormType::class)
            ->expectSubmit(['foo' => 'bar'], false);

        ($this->controller)($request);
    }

    public function getController(): FormWithSubmitController
    {
        return new FormWithSubmitController();
    }
}
