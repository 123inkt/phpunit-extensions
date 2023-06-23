<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\RedirectController;

/**
 * @extends AbstractControllerTestCase<RedirectController>
 * @covers \DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase
 */
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
