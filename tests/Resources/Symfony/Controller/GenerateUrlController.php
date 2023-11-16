<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenerateUrlController extends AbstractController
{
    public function singleGenerate(): Response
    {
        return new Response($this->generateUrl("route_name"));
    }

    public function multiGenerate(): JsonResponse
    {
        return new JsonResponse(
            [
                "first" => $this->generateUrl("first_route"),
                "second" => $this->generateUrl("second_route")
            ]
        );
    }
}
