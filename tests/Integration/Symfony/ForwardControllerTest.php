<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\FormController;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\ForwardController;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends AbstractControllerTestCase<ForwardController>
 */
#[CoversClass(AbstractControllerTestCase::class)]
class ForwardControllerTest extends AbstractControllerTestCase
{
    public function getController(): ForwardController
    {
        return new ForwardController();
    }

    public function testInvoke(): void
    {
        $expectedResponse = $this->expectForward(FormController::class, ['foo' => 'bar'], ['query' => 'param']);

        static::assertSame($expectedResponse, ($this->controller)());
    }
}
