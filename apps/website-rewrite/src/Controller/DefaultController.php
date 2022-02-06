<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route(path: '/', name: 'homepage')]
class DefaultController extends AbstractController
{
    public function __construct(private Environment $twig)
    {
    }

    public function __invoke(): ?Response
    {
        return new Response($this->twig->render('default/index.html.twig'));
    }
}
