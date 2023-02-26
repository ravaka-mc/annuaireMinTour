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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
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
    public function user(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
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
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('admin/layout/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category", name="app_admin_category")
     */
    public function category(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/layout/category.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/region", name="app_admin_region")
     */
    public function region(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/layout/region.html.twig', [
            'controller_name' => 'AdminController',
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
            $entityManager->persist($category)->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
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
            $entityManager->persist($category)->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
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
            
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/form/etablissement.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/user/add", name="app_admin_user_add")
     */
    public function userAdd(Request $request, EntityManagerInterface $entityManager): Response
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
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('admin/layout/etablissement.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
