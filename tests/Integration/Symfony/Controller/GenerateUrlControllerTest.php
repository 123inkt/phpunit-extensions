<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Symfony\Helper\FormAssertion;
use DR\PHPUnitExtensions\Symfony\ResponseAssertions;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\GenerateUrlController;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends AbstractControllerTestCase<GenerateUrlController>
 */
#[CoversClass(AbstractControllerTestCase::class)]
class GenerateUrlControllerTest extends AbstractControllerTestCase
{
    use ResponseAssertions;

    public function testSingleGenerate(): void
    {
        $this->expectGenerateUrl("first_route")->willReturn('first_url');
        static::assertJsonResponse(["url" => "first_url"], ($this->controller)());
    }

    public function testMultiGenerate(): void
    {
        $this->expectGenerateUrlWithConsecutive(["first_route"], ["second_route"])->willReturnOnConsecutiveCalls('first_url', 'second_url');
        static::assertJsonResponse(["first" => "first_url", "second" => "second_url"], ($this->controller)(true));
    }

    public function getController(): GenerateUrlController
    {
        return new GenerateUrlController();
    }
}
