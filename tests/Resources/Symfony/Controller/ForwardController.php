<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ForwardController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->forward(FormController::class, ['foo' => 'bar'], ['query' => 'param']);
    }
}
