<?php

namespace App\Controller;

use App\Entity\Region;
use App\Entity\Category;
use App\Entity\Etablissement;
use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use App\Repository\CategoryRepository;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        $etablissements = $this->etablissementRepository->findBy([], [
            'created_at' => 'desc'
        ],6);

        return $this->render('front/home.html.twig', [
            'categories' => $categories,
            'regions' => $regions,
            'etablissements' => $etablissements,
            'class' => ''
        ]);
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('front/dashboard.html.twig', [
            'class' => 'categorie',
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/{slug}", name="app_category")
     */
    public function category(Request $request, Category $category): Response
    {
        $categories = $this->categoryRepository->findAll();

        $etablissements = $category->getEtablissements();

        return $this->render('front/etablissements.html.twig', [
            'class' => 'categorie',
            'categories' => $categories,
            'etablissements' => $etablissements
        ]);
    }

    /**
     * @Route("/region/{slug}", name="app_region")
     */
    public function region(Request $request, Region $region): Response
    {
        $categories = $this->categoryRepository->findAll();

        $etablissements = $region->getEtablissements();

        return $this->render('front/etablissements.html.twig', [
            'class' => 'categorie',
            'categories' => $categories,
            'etablissements' => $etablissements
        ]);
    }

    /**
     * @Route("/{category_slug}/{etablissement_slug}", name="app_etablissement")
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     * @ParamConverter("etablissement", options={"mapping": {"etablissement_slug": "slug"}})
     */
    public function etablissement(Request $request, Category $category, Etablissement $etablissement): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('front/etablissement.html.twig', [
            'class' => 'categorie',
            'categories' => $categories,
            'etablissement' => $etablissement
        ]);
    }
}
