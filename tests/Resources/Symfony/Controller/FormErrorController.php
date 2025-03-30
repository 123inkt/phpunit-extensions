<?php

declare(strict_types=1);

namespace DR\PHPUnitExtensions\Tests\Resources\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Traversable;

class FormErrorController extends AbstractController
{
    /**
     * @return array{
     *     name: string,
     *     errors: \Symfony\Component\Form\FormErrorIterator<\Symfony\Component\Form\FormError>,
     *     config: FormConfigInterface<null>,
     *     all: FormInterface<null>[]
     * }
     */
    public function __invoke(): array
    {
        $form = $this->createForm(FormType::class);

        return [
            'name'   => $form->getName(),
            'errors' => $form->getErrors(),
            'config' => $form->getConfig(),
            'all'    => $form->all()
        ];
    }
}
