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
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminRegionController extends AdminController
{
    private $regionRepository;

    public function __construct(RegionRepository $regionRepository){
        $this->regionRepository = $regionRepository;
    }


    /**
     * @Route("/admin/region", name="app_admin_region")
     */
    public function index(Request $request): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);

        $regions = $this->regionRepository->findAll();

        return $this->render('admin/layout/region.html.twig', [
            'form' => $form->createView(),
            'regions' => $regions
        ]);
    }

    /**
     * @Route("/admin/region/add", name="app_admin_region_add")
     */
    public function add(Request $request): Response
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
     * @Route("/admin/region/{id}/delete", name="app_admin_region_delete")
     */
    public function delete(Request $request, Region $region): Response
    {
        
        $this->regionRepository->remove($region, true);

        return $this->redirectToRoute('app_admin_region');
    }

    /**
     * @Route("/admin/region/{id}/edit", name="app_admin_region_edit")
     */
    public function edit(Request $request, Region $region): Response
    {
        $nom = $request->request->get('nom');
        
        $region->setNom($nom);
        $this->regionRepository->add($region, true);

        return $this->redirectToRoute('app_admin_region');
    }
}
