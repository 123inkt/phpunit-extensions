<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Integration\Symfony\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller\GenerateUrlController;

/**
 * @extends AbstractControllerTestCase<GenerateUrlController>
 * @covers \DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase
 */
class GenerateUrlControllerTest extends AbstractControllerTestCase
{
    public function testSingleGenerate(): void
    {
        $this->expectGenerateUrl("first_route")->willReturn('first_url');
        static::assertSame('{"url":"first_url"}', ($this->controller)()->getContent());
    }

    public function testMultiGenerate(): void
    {
        $this->expectGenerateUrlWithConsecutive(["first_route"], ["second_route"])->willReturnOnConsecutiveCalls('first_url', 'second_url');
        static::assertSame('{"first":"first_url","second":"second_url"}', ($this->controller)(true)->getContent());
    }

    public function getController(): GenerateUrlController
    {
        return new GenerateUrlController();
    }
}
