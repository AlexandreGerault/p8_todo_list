<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', name: 'homepage')]
class DefaultController extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
