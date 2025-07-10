<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

class FormWithSubmitController extends AbstractController
{
    public function __invoke(Request $request): void
    {
        $form = $this->createForm(FormType::class);
        $form->submit($request->query->all(), false);
    }
}
