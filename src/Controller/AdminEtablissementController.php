<?php

namespace App\Controller;

use App\Entity\User;
use Twig\Environment;
use App\Entity\Refuse;
use App\Entity\Etablissement;
use App\Form\EtablissementType;
use Symfony\Component\Mime\Email;
use App\Repository\RefuseRepository;
use App\Repository\RegionRepository;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminEtablissementController extends AdminController
{
    private $etablissementRepository;
    private $regionRepository;
    private $refuseRepository;
    private $slugger;
    private $security;
    private $twig;
    private $mailer;

    public function __construct(RegionRepository $regionRepository, MailerInterface $mailer, Environment $twig, EtablissementRepository $etablissementRepository, SluggerInterface $slugger, Security $security, RefuseRepository $refuseRepository){
        $this->etablissementRepository = $etablissementRepository;
        $this->regionRepository = $regionRepository;
        $this->refuseRepository = $refuseRepository;
        $this->slugger = $slugger;
        $this->security = $security;
        $this->twig = $twig;
        $this->mailer = $mailer;
    }


   /**
     * @Route("/admin/etablissement", name="app_admin_etablissement")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();

        $etablissements = $this->etablissementRepository->findBy([], ['created_at' => 'desc']);
        
        return $this->render('admin/layout/etablissements.html.twig', [
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
        return $this->save($request, $etablissement, 'Modifie', 'Modifier', true);
    }

    /**
     * @Route("/admin/etablissement/{id}/delete", name="app_admin_etablissement_delete")
     */
    public function delete(Etablissement $etablissement): Response
    {
        $this->etablissementRepository->remove($etablissement, true);

        $nom = $etablissement->getNom();
        $html = $this->twig->render('front/email/etablissement-supprime.html.twig', [
            'nom' => $nom
        ]);

        $message = (new Email())
        ->from('annuaire@tourisme.gov.mg')
        ->to($etablissement->getCreatedBy()->getEmail())
        ->subject('Votre établissement : ' . $nom . ' a été supprimé')
        ->html($html);

        $this->mailer->send($message);

        return $this->redirectToRoute('app_admin_etablissement');
    }

    /**
     * @Route("/admin/etablissement/generate/slug", name="app_admin_etablissement_generate_slug")
     */
    public function generateSlug(): Response
    {
        $etablissements = $this->etablissementRepository->findBy([], ['created_at' => 'desc']);
        foreach($etablissements as $etablissement){
            $etablissement->setSlug($etablissement->getNom());
            $this->etablissementRepository->add($etablissement, true);
        }

        return $this->redirectToRoute('app_admin_etablissement');
    }

    /**
     * @Route("/admin/etablissement/generate/date-validation", name="app_admin_etablissement_generate_date_validation")
     */
    public function dateValidation(): Response
    {
        $etablissements = $this->etablissementRepository->findBy([], ['created_at' => 'desc']);
        foreach($etablissements as $etablissement){
            $etablissement->setDateValidation($etablissement->getCreatedAt());
            $this->etablissementRepository->add($etablissement, true);
        }

        return $this->redirectToRoute('app_admin_etablissement');
    }

    /**
     * @Route("/admin/etablissement/{id}/valide", name="app_admin_etablissement_valide")
     */
    public function valide(Etablissement $etablissement): Response
    {
        $date_validation =  new \DateTime();
        $etablissement->setStatut('valide');
        $etablissement->setDateValidation($date_validation);
        $this->etablissementRepository->add($etablissement, true);
        
        $nom = $etablissement->getNom();
        $html = $this->twig->render('front/email/etablissement-valide.html.twig', [
            'nom' => $nom
        ]);

        $message = (new Email())
        ->from('annuaire@tourisme.gov.mg')
        ->to($etablissement->getCreatedBy()->getEmail())
        ->subject('Votre établissement : ' . $nom . ' a été validé')
        ->html($html);

        $this->mailer->send($message);

        return $this->redirectToRoute('app_admin_etablissement');
    }


    /**
     * @Route("/admin/etablissement/{id}/refuse", name="app_admin_etablissement_refuse")
     */
    public function refuse(Request $request, Etablissement $etablissement): Response
    {
        $raison = $request->request->get('raison');
        $refuse = new Refuse();
        $refuse->setRaison($raison);
        $refuse->setEtablissement($etablissement);
        $this->refuseRepository->add($refuse, true);

        $etablissement->setStatut('refuse');
        $this->etablissementRepository->add($etablissement, true);

        $nom = $etablissement->getNom();
        $html = $this->twig->render('front/email/etablissement-refuse.html.twig', [
            'nom' => $nom,
            'raison' => $raison
        ]);

        
        $message = (new Email())
        ->from('annuaire@tourisme.gov.mg')
        ->to($etablissement->getCreatedBy()->getEmail())
        ->subject('Votre établissement : ' .  $nom . ' a été refusé')
        ->html($html);

        $this->mailer->send($message);

        return $this->redirectToRoute('app_admin_etablissement');
    }

    private function save(Request $request, Etablissement $etablissement, $titre, $label_btn, $is_edit = false){
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->has('avatarFile')){
                $avatarFile = $form->get('avatarFile')->getData();
                if ($avatarFile) {
                    $fileName = $this->upload($avatarFile);
                    $etablissement->setAvatar($fileName);
                }
            }

            if($form->has('pieceJustificationFile')){
                $pieceJustificationFile = $form->get('pieceJustificationFile')->getData();
                if ($pieceJustificationFile) {
                    $fileName = $this->upload($pieceJustificationFile);
                    $etablissement->setPieceJustification($fileName);
                }
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
            'is_edit' => $is_edit,
        ]);
    }

    private function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $this->slugger->slug($originalFilename) . '-'.uniqid() . '.'.$file->guessExtension();
        $file->move($this->getParameter('upload_etablissement'), $fileName);

        return $fileName;
    }

    /**
     *  @Route("/etablissement/exist", name="app_etablissement_exist")
     */
    public function etablissementExist(Request $request){
        $etablissement_nom = $request->query->get('etablissement_nom');
        $region_id = $request->query->get('etablissement_region');
        
        if($region_id != ''){
            $region = $this->regionRepository->findOneBy([
                'id' => (int) $region_id,
            ]);
            
            $etablissement = $this->etablissementRepository->findOneBy([
                'nom' => $etablissement_nom,
                'region' => $region
            ]);
        }
            
        return new JsonResponse([
            "exist" => ($etablissement) ? true : false
        ]);
    }
    
}
