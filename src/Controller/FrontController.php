<?php

namespace App\Controller;

use App\Entity\Region;
use App\Entity\Category;
use App\Entity\Etablissement;
use App\Form\EtablissementType;
use Symfony\Component\Form\Form;
use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use App\Repository\CategoryRepository;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FrontController extends AbstractController
{
    private $categoryRepository;
    private $regionRepository;
    private $etablissementRepository;
    private $userRepository;
    private $slugger;
    private $security;

    public function __construct(CategoryRepository $categoryRepository, RegionRepository $regionRepository, EtablissementRepository $etablissementRepository, UserRepository $userRepository, SluggerInterface $slugger, Security $security){
        $this->categoryRepository = $categoryRepository;
        $this->regionRepository = $regionRepository;
        $this->etablissementRepository = $etablissementRepository;
        $this->userRepository = $userRepository;
        $this->slugger = $slugger;
        $this->security = $security;
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
            'class' => '',
            'class_wrapper' => ''
        ]);
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $user = $this->security->getUser();
        $etablissements = $this->etablissementRepository->findBy([
            'createdBy' => $user
        ], [
            'created_at' => 'desc'
        ]);
        return $this->render('front/dashboard.html.twig', [
            'class' => 'categorie',
            'categories' => $categories,
            'etablissements' => $etablissements,
            'class' => 'bg__purplelight',
            'class_wrapper' => 'categorie'
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
            'class_wrapper' => '',
            'categories' => $categories,
            'etablissements' => $etablissements,
            'title' => $category->getNom()
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
            'class_wrapper' => '',
            'categories' => $categories,
            'etablissements' => $etablissements,
            'title' => $region->getNom()
        ]);
    }

    /**
     * @Route("/etablissement/add", name="app_front_etablissement_add")
     */
    public function etablissementAdd(Request $request): Response
    {
        $categories = $this->categoryRepository->findAll();

        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etablissement->setCreatedBy($this->security->getUser());
            $avatarFile = $form->get('avatarFile')->getData();
            if ($avatarFile) {
                $fileName = $this->upload($avatarFile);
                $etablissement->setAvatar($fileName);
            }

            $this->etablissementRepository->add($etablissement, true);

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('front/form/etablissement.html.twig', [
            'form' => $form->createView(),
            'etablissement' => $etablissement,
            'titre' => 'Ajout',
            'label_btn' => 'Ajouter',
            'categories' => $categories,
            "errors" => $this->getErrorMessages($form),
            'class' => 'bg__purplelight',
            'class_wrapper' => 'categorie'
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
            'class_wrapper' => 'categorie',
            'categories' => $categories,
            'etablissement' => $etablissement
        ]);
    }
    
    private function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $this->slugger->slug($originalFilename) . '-'.uniqid() . '.'.$file->guessExtension();
        $file->move($this->getParameter('upload_etablissement'), $fileName);

        return $fileName;
    }

    // Generate an array contains a key -> value with the errors where the key is the name of the form field
    protected function getErrorMessages(Form $form) 
    {
        if(!$form->isSubmitted()) return;

        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}
