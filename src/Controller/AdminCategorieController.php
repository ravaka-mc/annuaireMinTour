<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Region;
use App\Form\UserType;
use App\Entity\Category;
use App\Form\RegionType;
use App\Form\CategoryType;
use App\Entity\Etablissement;
use App\Form\EtablissementType;
use Symfony\Component\Form\Form;
use App\Repository\ActiviteRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminCategorieController extends AdminController
{
    private $categoryRepository;
    private $activiteRepository;
    private $slugger;

    public function __construct(CategoryRepository $categoryRepository, ActiviteRepository $activiteRepository, SluggerInterface $slugger){
        $this->categoryRepository = $categoryRepository;
        $this->activiteRepository = $activiteRepository;
        $this->slugger = $slugger;
    }


    /**
     * @Route("/admin/category", name="app_admin_category")
     */
    public function index(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        return $this->render('admin/layout/category.html.twig', [
            'form' => $form->createView(),
            'categories' => $this->categoryRepository->findAll(),
            'activites' => $this->activiteRepository->findBy([], [
                'nom' => 'asc'
            ]),
            'viewTypes' => [
                'TYPE_1' => 'Type d\'affichage 1',
                'TYPE_2' => 'Type d\'affichage 2',
                'TYPE_3' => 'Type d\'affichage 3',
                'TYPE_4' => 'Type d\'affichage 4',
                'TYPE_5' => 'Type d\'affichage 5',
            ]
        ]);
    }

       /**
     * @Route("/admin/category/add", name="app_admin_category_add")
     */
    public function add(Request $request): Response
    {
        $category = new Category();

        return $this->save($request, $category, 'Ajout', 'Ajouter');
    }

     /**
     * @Route("/admin/category/{id}/delete", name="app_admin_category_delete")
     */
    public function delete(Category $category): Response
    {
        
        $this->categoryRepository->remove($category, true);

        return $this->redirectToRoute('app_admin_category');
    }

    /**
     * @Route("/admin/category/{id}/edit", name="app_admin_category_edit")
     */
    public function edit(Request $request, Category $category): Response
    {
        return $this->save($request, $category, 'Modifie', 'Modifier');
    }

    private function save(Request $request, Category $category, $titre, $label_btn){
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $iconFile = $form->get('iconFile')->getData();
            if ($iconFile) {
                $fileName = $this->upload($iconFile, 'upload_category');
                $category->setIcon($fileName);
            }
            $this->categoryRepository->add($category, true);

           return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('admin/form/category.html.twig', [
            'form' => $form->createView(),
            'titre' =>  $titre,
            'label_btn' => $label_btn,
            'errors' => $this->getErrorMessages($form),
            'category' => $category
        ]);
    }


    private function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $this->slugger->slug($originalFilename) . '-'.uniqid() . '.'.$file->guessExtension();
        $file->move($this->getParameter('upload_category'), $fileName);

        return $fileName;
    }
}
