<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use App\Repository\CategoryRepository;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    private $categoryRepository;
    private $regionRepository;
    private $etablissementRepository;
    private $userRepository;

    public function __construct(CategoryRepository $categoryRepository, RegionRepository $regionRepository, EtablissementRepository $etablissementRepository, UserRepository $userRepository){
        $this->categoryRepository = $categoryRepository;
        $this->regionRepository = $regionRepository;
        $this->etablissementRepository = $etablissementRepository;
        $this->userRepository = $userRepository;
    }
    
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $regions = $this->regionRepository->findAll();
        return $this->render('front/home.html.twig', [
            'categories' => $categories,
            'regions' => $regions,
            'class' => ''
        ]);
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('front/dashboard.html.twig', [
            'class' => 'categorie'
        ]);
    }
}
