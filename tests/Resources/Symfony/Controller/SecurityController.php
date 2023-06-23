<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted("ROLE_SECURITY");
        $user = $this->getUser();

        return new Response($user?->getUserIdentifier());
    }
}
