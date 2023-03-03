<?php

namespace App\Controller;

use App\Entity\Groupement;
use App\Form\GroupementType;
use App\Repository\GroupementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminGroupementController extends AdminController
{
    private $groupementRepository;

    public function __construct(GroupementRepository $groupementRepository){
        $this->groupementRepository = $groupementRepository;
    }


    /**
     * @Route("/admin/groupement", name="app_admin_groupement")
     */
    public function index(): Response
    {
        $groupement = new Groupement();
        $form = $this->createForm(GroupementType::class, $groupement);

        $groupements = $this->groupementRepository->findAll();

        return $this->render('admin/layout/groupement.html.twig', [
            'form' => $form->createView(),
            'groupements' => $groupements
        ]);
    }

       /**
     * @Route("/admin/groupement/add", name="app_admin_groupement_add")
     */
    public function add(Request $request): Response
    {
        $groupement = new Groupement();
        $form = $this->createForm(GroupementType::class, $groupement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $this->groupementRepository->add($groupement, true);

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
     * @Route("/admin/groupement/{id}/delete", name="app_admin_groupement_delete")
     */
    public function delete(Groupement $groupement): Response
    {
        
        $this->groupementRepository->remove($groupement, true);

        return $this->redirectToRoute('app_admin_groupement');
    }

    /**
     * @Route("/admin/groupement/{id}/edit", name="app_admin_groupement_edit")
     */
    public function edit(Request $request, Groupement $groupement): Response
    {
        $nom = $request->request->get('nom');
        
        $groupement->setNom($nom);
        $this->groupementRepository->add($groupement, true);

        return $this->redirectToRoute('app_admin_groupement');
    }
}
