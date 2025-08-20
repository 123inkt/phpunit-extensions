<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\SecurityIsGrantedController;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends AbstractControllerTestCase<SecurityIsGrantedController>
 */
#[CoversClass(AbstractControllerTestCase::class)]
class SecurityIsGrantedControllerTest extends AbstractControllerTestCase
{
    public function getController(): SecurityIsGrantedController
    {
        return new SecurityIsGrantedController();
    }

    public function testInvoke(): void
    {
        $this->expectIsGranted("ROLE_SECURITY");
        static::assertSame("Granted", ($this->controller)()->getContent());
    }

    public function testInvokeNoAccess(): void
    {
        $this->expectIsGranted("ROLE_SECURITY", null, false);
        static::assertSame("Not granted", ($this->controller)()->getContent());
    }
}
