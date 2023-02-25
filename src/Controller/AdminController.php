<?php

namespace App\Controller;

use App\Entity\Region;
use App\Entity\Category;
use App\Form\RegionType;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/", name="app_admin")
     */
    public function adminDashboard(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @Route("/admin/user", name="app_admin_user")
     */
    public function adminUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/user.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/category", name="app_admin_category")
     */
    public function adminCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/category.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/region", name="app_admin_region")
     */
    public function adminRegion(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/region.html.twig', [
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
}
