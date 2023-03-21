<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Region;
use App\Form\UserType;
use App\Entity\Category;
use App\Form\ContactType;
use App\Form\SignalerType;
use App\Entity\Etablissement;
use App\Entity\Signaler;
use App\Form\EtablissementType;
use Symfony\Component\Form\Form;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use App\Repository\ActiviteRepository;
use App\Repository\CategoryRepository;
use App\Repository\SignalerRepository;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FrontController extends AbstractController
{
    private $categoryRepository;
    private $regionRepository;
    private $etablissementRepository;
    private $userRepository;
    private $slugger;
    private $security;
    private $activiteRepository;
    private $signalerRepository;

    public function __construct(SignalerRepository $signalerRepository, CategoryRepository $categoryRepository, RegionRepository $regionRepository, EtablissementRepository $etablissementRepository, UserRepository $userRepository, SluggerInterface $slugger, Security $security, ActiviteRepository $activiteRepository){
        $this->categoryRepository = $categoryRepository;
        $this->regionRepository = $regionRepository;
        $this->etablissementRepository = $etablissementRepository;
        $this->userRepository = $userRepository;
        $this->slugger = $slugger;
        $this->security = $security;
        $this->activiteRepository = $activiteRepository;
        $this->signalerRepository = $signalerRepository;
    }
    
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $regions = $this->regionRepository->findAll();
        $etablissements = $this->etablissementRepository->findBy([
            'valide' => 1
        ], 
        [
            'created_at' => 'desc'
        ],6);

        return $this->render('front/home.html.twig', [
            'categories' => $categories,
            'regions' => $regions,
            'etablissements' => $etablissements,
            'class' => '',
            'class_wrapper' => ''
        ]);
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(): Response
    {
        $user = $this->security->getUser();

        if(in_array('ROLE_ADMIN', $user->getRoles()))
            return $this->redirectToRoute('app_admin');

        $categories = $this->categoryRepository->findAll();
        
        $etablissements = $this->etablissementRepository->findBy(
            [
                'createdBy' => $user
            ], 
            [
                'created_at' => 'desc'
            ]
        );

        return $this->render('front/dashboard.html.twig', [
            'class' => 'categorie',
            'categories' => $categories,
            'etablissements' => $etablissements,
            'class' => 'bg__purplelight',
            'class_wrapper' => 'categorie'
        ]);
    }


    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $categories = $this->categoryRepository->findAll();
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();
            
            $message = (new Email())
            ->from('ramanantsoafitiavana@gmail.com')
            ->to('testannuaire@yopmail.com')
            ->subject('Formulaire de contact')
            ->html('<p>' . $contactFormData['message'] . '</p>');

            $mailer->send($message);

            $this->addFlash('success', 'Your message has been sent');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('front/form/contact.html.twig', [
            'categories' => $categories,
            'class' => '',
            'class_wrapper' => '',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/search", name="app_search")
     */
    public function search(Request $request): Response
    {
        $categories = $this->categoryRepository->findAll();
        $regions = $this->regionRepository->findAll();
        $activites = $this->activiteRepository->findAll();

        $search = $request->query->get('s','');
        $region_id = $request->query->get('region','');
        $activite_id = $request->query->get('activite','');

        $etablissements = $this->etablissementRepository->search($search, $region_id, $activite_id);

        return $this->render('front/etablissements.html.twig', [
            'categories' => $categories,
            'class' => '',
            'etablissements' => $etablissements,
            'class_wrapper' => '',
            'regions' => $regions,
            'activites' => $activites,
            'search' => $search,
            'region_id' => $region_id,
            'activite_id' => $activite_id,
            'title' => 'Recherche'
        ]);
    }


    /**
     * @Route("/profil", name="app_profil")
     */
    public function profil(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if($request->getMethod() === 'POST') {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
    
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setEmail($email);

            if($pwd = $request->request->get('password'))
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $pwd
                )
            );

            $this->userRepository->add($user, true);
        }
        
        $categories = $this->categoryRepository->findAll();
        
        return $this->render('front/form/profil.html.twig', [
            'categories' => $categories,
            'user' => $user,
            'class' => 'bg__purplelight',
            'class_wrapper' => ''
        ]);
    }

    /**
     * @Route("/region/{slug}", name="app_region")
     */
    public function region(Request $request, Region $region): Response
    {
        $categories = $this->categoryRepository->findAll();

        $regions = $this->regionRepository->findAll();
        $activites = $this->activiteRepository->findAll();

        $search = $request->query->get('s','');
        $region_id = $request->query->get('region','');
        $activite_id = $request->query->get('activite','');

        $etablissements = $region->getEtablissements();

        return $this->render('front/etablissements.html.twig', [
            'class' => 'categorie',
            'class_wrapper' => '',
            'categories' => $categories,
            'etablissements' => $etablissements,
            'regions' => $regions,
            'activites' => $activites,
            'search' => $search,
            'region_id' => $region_id,
            'activite_id' => $activite_id,
            'title' => $region->getNom()
        ]);
    }

    /**
     * @Route("/dashboard/etablissement/add", name="app_front_etablissement_add")
     */
    public function etablissementAdd(Request $request): Response
    {
        $etablissement = new Etablissement();
        return $this->save($request, $etablissement, 'Ajout', 'Ajouter');
    }

    /**
     * @Route("/dashboard/etablissement/{slug}/edit", name="app_front_etablissement_edit")
     */
    public function etablissementEdit(Request $request, Etablissement $etablissement): Response
    {
        return $this->save($request, $etablissement, 'Modifie', 'Modifier', true);
    }

    /**
     * @Route("/dashboard/etablissement/{slug}/delete", name="app_front_etablissement_delete")
     */
    public function etablissementDelete(Etablissement $etablissement): Response
    {
        $user = $this->security->getUser();
        if($user != $etablissement->getCreatedBy())
            return $this->redirectToRoute('app_dashboard');

        $this->etablissementRepository->remove($etablissement, true);

        return $this->redirectToRoute('app_dashboard');
    }

    private function save(Request $request, Etablissement $etablissement, $titre, $label_btn, $is_edit = false){
        $user = $this->security->getUser();

        if($is_edit && $user != $etablissement->getCreatedBy())
            return $this->redirectToRoute('app_dashboard');
            //return $this->denyAccessUnlessGranted('view', null, 'accès non autorisé');
        

        $categories = $this->categoryRepository->findAll();

        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etablissement->setCreatedBy($user);
            $avatarFile = $form->get('avatarFile')->getData();
            if ($avatarFile) {
                $fileName = $this->upload($avatarFile);
                $etablissement->setAvatar($fileName);
            }

            $this->etablissementRepository->add($etablissement, true);

            return $this->redirectToRoute('app_dashboard');
        }
        
        return $this->render('front/form/etablissement.html.twig', [
            'form' => $form->createView(),
            'etablissement' => $etablissement,
            'titre' => $titre,
            'label_btn' => $label_btn,
            'categories' => $categories,
            "errors" => $this->getErrorMessages($form),
            'class' => 'bg__purplelight',
            'class_wrapper' => 'categorie'
        ]);
    }

    /**
     * @Route("/signaler/{slug}", name="app_etablissement_signaler")
     */
    public function signaler(Request $request, Etablissement $etablissement): Response
    {
        $form = $this->createForm(SignalerType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $signaler = new Signaler();
            
            $signalerFormData = $form->getData();

            $signaler->setNom($signalerFormData['nom']);
            $signaler->setEmail($signalerFormData['email']);
            $signaler->setMotif($signalerFormData['motif']);
            $signaler->setEtablissement($etablissement);

            $this->signalerRepository->add($signaler, true);
        }

        $error = $this->getErrorMessages($form);

        return $this->redirectToRoute('app_etablissement', [
            'category_slug' => $etablissement->getCategory()->getSlug(),
            'etablissement_slug' => $etablissement->getSlug()
        ]);
    }

    /**
     * @Route("/{category_slug}/{etablissement_slug}", name="app_etablissement", requirements={"category_slug"="^((?!_).)*$"})
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     * @ParamConverter("etablissement", options={"mapping": {"etablissement_slug": "slug"}})
     */
    public function etablissement(Category $category, Etablissement $etablissement): Response
    {
        $categories = $this->categoryRepository->findAll();
        
        $form = $this->createForm(SignalerType::class);

        return $this->render('front/etablissement.html.twig', [
            'class' => 'categorie',
            'class_wrapper' => 'categorie',
            'form' => $form->createView(),
            'categories' => $categories,
            'etablissement' => $etablissement
        ]);
    }
    
    private function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $this->slugger->slug($originalFilename) . '-'.uniqid() . '.'.$file->guessExtension();
        $file->move($this->getParameter('upload_etablissement'), $fileName);

        return $fileName;
    }

    // Generate an array contains a key -> value with the errors where the key is the name of the form field
    protected function getErrorMessages(Form $form) 
    {
        if(!$form->isSubmitted()) return;

        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * @Route("/{slug}", name="app_category", requirements={"slug"="^((?!_).)*$"})
     */
    public function category(Request $request, Category $category): Response
    {
        $categories = $this->categoryRepository->findAll();

        $etablissements = $category->getEtablissements();

        $regions = $this->regionRepository->findAll();
        $activites = $this->activiteRepository->findAll();

        $search = $request->query->get('s','');
        $region_id = $request->query->get('region','');
        $activite_id = $request->query->get('activite','');

        return $this->render('front/etablissements.html.twig', [
            'class' => 'categorie',
            'class_wrapper' => '',
            'categories' => $categories,
            'etablissements' => $etablissements,
            'regions' => $regions,
            'activites' => $activites,
            'search' => $search,
            'region_id' => $region_id,
            'activite_id' => $activite_id,
            'title' => $category->getNom()
        ]);
    }
}
