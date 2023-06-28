<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\FormController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends AbstractControllerTestCase<FormController>
 * @covers \DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase
 * @covers \DR\PHPUnitExtensions\Symfony\Helper\FormAssertion
 */
class FormControllerTest extends AbstractControllerTestCase
{
    public function testInvoke(): void
    {
        $request = new Request();
        $this->expectCreateForm(FormType::class)
            ->handleRequest($request)
            ->isSubmittedWillReturn(true)
            ->isValidWillReturn(true)
            ->getDataWillReturn('FormData');

        $this->expectAddFlash('FlashType', 'FormData');
        static::assertSame('Success', ($this->controller)($request)->getContent());
    }

    public function testInvokeInvalid(): void
    {
        $formView = new FormView();

        $request = new Request();
        $this->expectCreateForm(FormType::class)
            ->handleRequest($request)
            ->isSubmittedWillReturn(true)
            ->isValidWillReturn(false)
            ->createViewWillReturn($formView)
            ->getWillReturn(['name' => 'foobar']);

        $this->expectRender('form.html.twig', ['form' => $formView], 'FormView');

        static::assertSame('FormView', ($this->controller)($request)->getContent());
    }

    public function getController(): FormController
    {
        return new FormController();
    }
}
