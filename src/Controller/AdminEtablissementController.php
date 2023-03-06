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

class AdminEtablissementController extends AdminController
{
    private $etablissementRepository;

    public function __construct(EtablissementRepository $etablissementRepository){
        $this->etablissementRepository = $etablissementRepository;
    }


   /**
     * @Route("/admin/etablissement", name="app_admin_etablissement")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etablissements = $this->etablissementRepository->findAll();

        return $this->render('admin/layout/etablissement.html.twig', [
            'etablissements' => $etablissements,
        ]);
    }

    /**
     * @Route("/admin/etablissement/add", name="app_admin_etablissement_add")
     */
    public function add(Request $request): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->etablissementRepository->add($etablissement, true);

            return $this->redirectToRoute('app_admin_etablissement');
        }
        
        return $this->render('admin/form/etablissement.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Ajout',
            'label_btn' => 'Ajouter',
            "errors" => $this->getErrorMessages($form),
        ]);
    }


    /**
     * @Route("/admin/etablissement/{id}/edit", name="app_admin_etablissement_edit")
     */
    public function edit(Request $request, Etablissement $etablissement): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->etablissementRepository->add($etablissement, true);

            return $this->redirectToRoute('app_admin_etablissement');
        }
        
        return $this->render('admin/form/etablissement.html.twig', [
            'form' => $form->createView(),
            'etablissement' => $etablissement,
            'titre' => 'Modifier',
            'label_btn' => 'Enregistrer',
            "errors" => $this->getErrorMessages($form),
        ]);
    }
}
