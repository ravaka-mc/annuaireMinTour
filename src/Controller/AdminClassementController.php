<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Classement;
use App\Form\ClassementType;
use App\Repository\ClassementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminClassementController extends AdminController
{
    private $classementRepository;
    private $twig;

    public function __construct(ClassementRepository $classementRepository, Environment $twig){
        $this->classementRepository = $classementRepository;
        $this->twig = $twig;
    }


    /**
     * @Route("/admin/classement", name="app_admin_classement")
     */
    public function index(): Response
    {
        $classement = new Classement();
        $form = $this->createForm(ClassementType::class, $classement);

        $classements = $this->classementRepository->findAll();

        return $this->render('admin/layout/classement.html.twig', [
            'form' => $form->createView(),
            'classements' => $classements
        ]);
    }

       /**
     * @Route("/admin/classement/add", name="app_admin_classement_add")
     */
    public function add(Request $request): Response
    {
        $classement = new Classement();
        $form = $this->createForm(ClassementType::class, $classement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $this->classementRepository->add($classement, true);

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
     * @Route("/admin/classement/{id}/delete", name="app_admin_classement_delete")
     */
    public function delete(Classement $classement): Response
    {
        
        $this->classementRepository->remove($classement, true);

        return $this->redirectToRoute('app_admin_classement');
    }

    /**
     * @Route("/admin/classement/{id}/edit", name="app_admin_classement_edit")
     */
    public function edit(Request $request, Classement $classement): Response
    {
        $nom = $request->request->get('nom');
        
        $classement->setNom($nom);
        $this->classementRepository->add($classement, true);

        return $this->redirectToRoute('app_admin_classement');
    }

    /**
     *  @Route("/classements", name="app_classements")
     */
    public function getClassement(){
        
        $classements =  $this->classementRepository->findAll();
        
        $html = $this->twig->render('admin/block/classements.html.twig', [
            'classements' => $classements,
        ]);
        
        return new JsonResponse([
            "html" => $html
        ]);
    }
}
