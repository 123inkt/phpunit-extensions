<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Symfony\Helper\FormAssertion;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\RedirectController;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends AbstractControllerTestCase<RedirectController>
 */
#[CoversClass(AbstractControllerTestCase::class)]
class RedirectControllerTest extends AbstractControllerTestCase
{
    public function testInvoke(): void
    {
        $this->expectRedirectToRoute("route_name", [], "redirectUrl");
        static::assertSame("redirectUrl", ($this->controller)()->getTargetUrl());
    }

    public function getController(): RedirectController
    {
        return new RedirectController();
    }
}
