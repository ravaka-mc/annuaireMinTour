<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminActiviteController extends AdminController
{
    private $activiteRepository;

    public function __construct(ActiviteRepository $activiteRepository){
        $this->activiteRepository = $activiteRepository;
    }


    /**
     * @Route("/admin/activite", name="app_admin_activite")
     */
    public function index(): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);

        $activites = $this->activiteRepository->findAll();

        return $this->render('admin/layout/activite.html.twig', [
            'form' => $form->createView(),
            'activites' => $activites
        ]);
    }

       /**
     * @Route("/admin/activite/add", name="app_admin_activite_add")
     */
    public function add(Request $request): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $this->activiteRepository->add($activite, true);

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
     * @Route("/admin/activite/{id}/delete", name="app_admin_activite_delete")
     */
    public function delete(Activite $activite): Response
    {
        
        $this->activiteRepository->remove($activite, true);

        return $this->redirectToRoute('app_admin_activite');
    }

    /**
     * @Route("/admin/activite/{id}/edit", name="app_admin_activite_edit")
     */
    public function edit(Request $request, Activite $activite): Response
    {
        $nom = $request->request->get('nom');
        
        $activite->setNom($nom);
        $this->activiteRepository->add($activite, true);

        return $this->redirectToRoute('app_admin_activite');
    }
}
