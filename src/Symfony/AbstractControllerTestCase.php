<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Symfony;

use DR\PHPUnitExtensions\Symfony\Helper\FormAssertion;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\MockObject\InvocationStubber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

use function DR\PHPUnitExtensions\Mock\consecutive;

/**
 * @template T as AbstractController&callable
 */
abstract class AbstractControllerTestCase extends TestCase
{
    /** @var T */
    protected AbstractController $controller;
    protected Container $container;

    /**
     * @return T
     */
    abstract public function getController(): AbstractController;

    public function expectGetUser(?UserInterface $user): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::atLeastOnce())->method('getUser')->willReturn($user);

        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->expects(self::atLeastOnce())->method('getToken')->willReturn($token);

        $this->container->set('security.token_storage', $storage);
    }

    public function expectDenyAccessUnlessGranted(string $attribute, mixed $subject = null, bool $granted = true): void
    {
        $checker = $this->createMock(AuthorizationCheckerInterface::class);
        $checker->expects(self::atLeastOnce())->method('isGranted')->with($attribute, $subject)->willReturn($granted);

        $this->container->set('security.authorization_checker', $checker);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function expectCreateForm(string $type, mixed $data = null, array $options = []): FormAssertion
    {
        $form = $this->createMock(FormInterface::class);

        $factory = $this->createMock(FormFactoryInterface::class);
        $factory->expects(self::once())->method('create')->with($type, $data, $options)->willReturn($form);

        $this->container->set('form.factory', $factory);

        return new FormAssertion($form, $this);
    }

    public function expectAddFlash(string $type, mixed $message): void
    {
        $flashBag = $this->createMock(FlashBagInterface::class);
        $flashBag->method('getName')->willReturn('name');
        $flashBag->method('getStorageKey')->willReturn('storageKey');
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage(), null, $flashBag));
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $this->container->set('request_stack', $requestStack);

        $flashBag->expects(self::once())->method('add')->with($type, $message);
    }

    /**
     * @param array<string, int|string|object|null> $parameters
     */
    public function expectGenerateUrl(
        string $route,
        array $parameters = [],
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): InvocationMocker|InvocationStubber {
        $router = $this->createMock(RouterInterface::class);
        $this->container->set('router', $router);

        return $router->expects(self::once())->method('generate')->with($route, $parameters, $referenceType);
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public function expectGenerateUrlWithConsecutive(array ...$arguments): InvocationMocker|InvocationStubber
    {
        $router = $this->createMock(RouterInterface::class);
        $this->container->set('router', $router);

        return $router->expects(self::atLeastOnce())->method('generate')->with(...consecutive(...$arguments));
    }

    /**
     * @param array<string, int|string|object|null> $parameters
     */
    public function expectRedirectToRoute(string $route, array $parameters = [], string $redirectTo = 'redirect'): InvocationMocker|InvocationStubber
    {
        return $this->expectGenerateUrl($route, $parameters)->willReturn($redirectTo);
    }

    /**
     * @param class-string         $controller
     * @param array<string, mixed> $path
     * @param array<string, mixed> $query
     */
    public function expectForward(string $controller, array $path = [], array $query = []): Response&MockObject
    {
        $path['_controller'] = $controller;

        $request = $this->createMock(Request::class);
        $request->method('duplicate')->with($query, null, $path)->willReturnSelf();

        $requestStack = new RequestStack();
        $requestStack->push($request);
        $this->container->set('request_stack', $requestStack);

        $response = $this->createMock(Response::class);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $kernel->expects(static::atLeastOnce())->method('handle')->with($request, HttpKernelInterface::SUB_REQUEST)->willReturn($response);
        $this->container->set('http_kernel', $kernel);

        return $response;
    }

    /**
     * @param mixed[] $context
     */
    public function expectRender(string $name, array $context = [], string $response = ''): void
    {
        $twig = $this->createMock(Environment::class);
        $this->container->set('twig', $twig);

        $twig->expects(self::once())->method('render')->with($name, $context)->willReturn($response);
    }

    protected function setUp(): void
    {
        $this->controller = $this->getController();
        $this->container  = new Container();
        $this->controller->setContainer($this->container);
    }
}
