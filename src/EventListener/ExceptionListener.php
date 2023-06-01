<?php

namespace App\EventListener;

use Twig\Environment;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionListener implements EventSubscriberInterface
{
    private $categoryRepository;
    private $twig;

    public function __construct(Environment $twig, CategoryRepository $categoryRepository){
        $this->categoryRepository = $categoryRepository;
        $this->twig = $twig;
    }
    
    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        if (!$exception instanceof NotFoundHttpException && !$exception instanceof AccessDeniedHttpException) {
            return;
        }

        $categories = $this->categoryRepository->findAll();

        $statusCode = $exception->getStatusCode();
        $content = $this->twig->render('error/' . $statusCode . '.html.twig', [
            'categories' => $categories,
            'class' => '',
            'active' => '',
            'class_wrapper' => ''
        ]);

        $response = new Response();
        $response->setContent($content);

        $event->setResponse($response);
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

}