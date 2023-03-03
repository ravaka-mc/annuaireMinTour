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
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminCategorieController extends AdminController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @Route("/admin/category", name="app_admin_category")
     */
    public function index(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $categories = $this->categoryRepository->findAll();

        return $this->render('admin/layout/category.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

       /**
     * @Route("/admin/category/add", name="app_admin_category_add")
     */
    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $this->categoryRepository->add($category, true);

            return new JsonResponse([
                "msg_error" => '',
                "code_status" => 200
            ]);
        }

        return new JsonResponse([
            "msg_error" => $this->getErrorMessages($form),
            "code_status" => 503
        ]);
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
        $nom = $request->request->get('nom');
        
        $category->setNom($nom);
        $this->categoryRepository->add($category, true);

        return $this->redirectToRoute('app_admin_category');
    }
}
