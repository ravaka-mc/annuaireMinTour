<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorController extends AbstractController
{
    private $categoryRepository;
    
    public function __construct(CategoryRepository $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/error", name="app_error")
     */
    public function show(HttpExceptionInterface $exception): Response
    {
        $statusCode = $exception->getStatusCode();

        $categories = $this->categoryRepository->findAll();

        return $this->render('error/' . $statusCode . '.html.twig', [
            'categories' => $categories,
            'class' => '',
            'active' => '',
            'class_wrapper' => ''
        ]);
    }
}
