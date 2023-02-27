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
use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
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
     * @Route("/admin/", name="app_admin")
     */
    public function dashboard(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/layout/dashboard.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @Route("/admin/user", name="app_admin_user")
     */
    public function user(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $users = $this->userRepository->findAll();

        return $this->render('admin/layout/user.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/category", name="app_admin_category")
     */
    public function category(Request $request, EntityManagerInterface $entityManager): Response
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
     * @Route("/admin/region", name="app_admin_region")
     */
    public function region(Request $request, EntityManagerInterface $entityManager): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);

        $regions = $this->categoryRepository->findAll();

        return $this->render('admin/layout/region.html.twig', [
            'form' => $form->createView(),
            'regions' => $regions
        ]);
    }

    /**
     * @Route("/admin/etablissement", name="app_admin_etablissement")
     */
    public function etablissement(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/layout/etablissement.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


   /**
     * @Route("/admin/category/add", name="app_admin_category_add")
     */
    public function categoryAdd(Request $request, EntityManagerInterface $entityManager): Response
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
     * @Route("/admin/region/add", name="app_admin_region_add")
     */
    public function regionAdd(Request $request, EntityManagerInterface $entityManager): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->regionRepository->add($region, true);

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
     * @Route("/admin/etablissement/add", name="app_admin_etablissement_add")
     */
    public function etablissementAdd(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etablissement);
            $entityManager->flush();
            
            $this->userRepository->add($user, true);

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
     * @Route("/admin/user/add", name="app_admin_user_add")
     */
    public function userAdd(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(["ROLE_ETABLISSEMENT"]);

            $this->userRepository->add($user, true);

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
