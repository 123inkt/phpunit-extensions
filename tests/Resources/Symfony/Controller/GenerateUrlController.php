<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GenerateUrlController extends AbstractController
{
    public function __invoke(bool $multi = false): JsonResponse
    {
        if ($multi) {
            return new JsonResponse(
                ["first" => $this->generateUrl("first_route"), "second" => $this->generateUrl("second_route")]
            );
        }

        return new JsonResponse(["url" => $this->generateUrl("first_route")]);
    }
}
