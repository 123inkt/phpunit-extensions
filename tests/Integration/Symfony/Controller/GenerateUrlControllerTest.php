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
        $this->expectGenerateUrl("route_name");
        $this->controller->singleGenerate();
    }

    public function testMultiGenerate(): void
    {
        $this->expectGenerateUrlWithConsecutive(["first_route"], ["second_route"]);
        $this->controller->multiGenerate();
    }

    public function getController(): GenerateUrlController
    {
        return new GenerateUrlController();
    }
}
