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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminEtablissementController extends AdminController
{
    private $etablissementRepository;
    private $slugger;

    public function __construct(EtablissementRepository $etablissementRepository, SluggerInterface $slugger){
        $this->etablissementRepository = $etablissementRepository;
        $this->slugger = $slugger;
    }


   /**
     * @Route("/admin/etablissement", name="app_admin_etablissement")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etablissements = $this->etablissementRepository->findBy([], ['created_at' => 'desc']);

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
        return $this->save($request, $etablissement, 'Ajout', 'Ajouter');
    }


    /**
     * @Route("/admin/etablissement/{id}/edit", name="app_admin_etablissement_edit")
     */
    public function edit(Request $request, Etablissement $etablissement): Response
    {
        return $this->save($request, $etablissement, 'Modifie', 'Modifier');
    }

    /**
     * @Route("/admin/etablissement/generate/slug", name="app_admin_etablissement_generate_slug")
     */
    public function generateslug(Request $request): Response
    {
        $etablissements = $this->etablissementRepository->findBy([], ['created_at' => 'desc']);
        foreach($etablissements as $etablissement){
            $etablissement->setSlug($etablissement->getNom());
            $this->etablissementRepository->add($etablissement, true);
        }

        return $this->redirectToRoute('app_admin_etablissement');
    }

    private function save(Request $request, Etablissement $etablissement, $titre, $label_btn){
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatarFile')->getData();
            if ($avatarFile) {
                $fileName = $this->upload($avatarFile);
                $etablissement->setAvatar($fileName);
            }

            $this->etablissementRepository->add($etablissement, true);

            return $this->redirectToRoute('app_admin_etablissement');
        }
        
        return $this->render('admin/form/etablissement.html.twig', [
            'form' => $form->createView(),
            'etablissement' => $etablissement,
            'titre' => $titre,
            'label_btn' => $label_btn,
            "errors" => $this->getErrorMessages($form),
        ]);
    }

    private function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $this->slugger->slug($originalFilename) . '-'.uniqid() . '.'.$file->guessExtension();
        $file->move($this->getParameter('upload_etablissement'), $fileName);

        return $fileName;
    }
}
