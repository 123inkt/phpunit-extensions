<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\SecurityController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends AbstractControllerTestCase<SecurityController>
 * @covers \DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase
 */
class SecurityControllerTest extends AbstractControllerTestCase
{
    public function getController(): SecurityController
    {
        return new SecurityController();
    }

    public function testInvoke(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getUserIdentifier')->willReturn("FooBar");

        $this->expectDenyAccessUnlessGranted("ROLE_SECURITY");
        $this->expectGetUser($user);
        static::assertSame("FooBar", ($this->controller)()->getContent());
    }

    public function testInvokeNoAccess(): void
    {
        $this->expectDenyAccessUnlessGranted("ROLE_SECURITY", null, false);
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Access Denied');
        ($this->controller)();
    }
}
