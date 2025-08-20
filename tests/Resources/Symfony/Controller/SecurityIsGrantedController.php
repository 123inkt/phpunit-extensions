<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SecurityIsGrantedController extends AbstractController
{
    public function __invoke(): Response
    {
        if ($this->isGranted("ROLE_SECURITY") === false) {
            return new Response("Not granted");
        }

        return new Response("Granted");
    }
}
