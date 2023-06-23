<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectController extends AbstractController
{
    public function __invoke(): RedirectResponse
    {
        return $this->redirectToRoute("route_name");
    }
}
