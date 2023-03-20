<?php

namespace App\Controller;

use App\Repository\SignalerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSignalerController extends AdminController
{
    private $signalerRepository;

    public function __construct(SignalerRepository $signalerRepository){
        $this->signalerRepository = $signalerRepository;
    }


    /**
     * @Route("/admin/signaler", name="app_admin_signaler")
     */
    public function index(): Response
    {

        $signalers = $this->signalerRepository->findAll();

        return $this->render('admin/layout/signaler.html.twig', [
            'signalers' => $signalers
        ]);
    }
}
