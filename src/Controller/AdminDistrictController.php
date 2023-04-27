<?php

namespace App\Controller;

use App\Entity\District;
use App\Form\DistrictType;
use App\Repository\RegionRepository;
use App\Repository\DistrictRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminDistrictController extends AdminController
{
    private $districtRepository;
    private $regionRepository;

    public function __construct(DistrictRepository $districtRepository, RegionRepository $regionRepository){
        $this->districtRepository = $districtRepository;
        $this->regionRepository = $regionRepository;
    }


    /**
     * @Route("/admin/district", name="app_admin_district")
     */
    public function index(): Response
    {
        $district = new District();
        $form = $this->createForm(DistrictType::class, $district);

        $districts = $this->districtRepository->findAll();
        $regions = $this->regionRepository->findAll();

        return $this->render('admin/layout/district.html.twig', [
            'form' => $form->createView(),
            'districts' => $districts,
            'regions' => $regions
        ]);
    }

    /**
     * @Route("/admin/district/add", name="app_admin_district_add")
     */
    public function add(Request $request): Response
    {
        $district = new District();
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->districtRepository->add($district, true);

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
     * @Route("/admin/district/{id}/delete", name="app_admin_district_delete")
     */
    public function delete(Request $request, District $district): Response
    {
        
        $this->districtRepository->remove($district, true);

        return $this->redirectToRoute('app_admin_district');
    }

    /**
     * @Route("/admin/district/{id}/edit", name="app_admin_district_edit")
     */
    public function edit(Request $request, District $district): Response
    {
        $nom = $request->request->get('nom');
        $region = $request->request->get('region', '');
        if($region != ''){
            $region = $this->regionRepository->findOneBy([
                'id' => $region
            ]);
            $district->setRegion($region);
        }
        $district->setNom($nom);
        
        $this->districtRepository->add($district, true);

        return $this->redirectToRoute('app_admin_district');
    }
}
