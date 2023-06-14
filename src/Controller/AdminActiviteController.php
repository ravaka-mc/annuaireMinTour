<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminActiviteController extends AdminController
{
    private $activiteRepository;
    private $twig;
    private $etablissementRepository;

    public function __construct(ActiviteRepository $activiteRepository, Environment $twig, EtablissementRepository $etablissementRepository){
        $this->activiteRepository = $activiteRepository;
        $this->twig = $twig;
        $this->etablissementRepository = $etablissementRepository;
    }


    /**
     * @Route("/admin/activite", name="app_admin_activite")
     */
    public function index(): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);

        $activites = $this->activiteRepository->findBy([], [
            'nom' => 'asc'
        ]);

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
        $parent_id = $request->request->get('parent', 0) == "" ? 0 : $request->request->get('parent', 0);
        $ordre = $request->request->get('ordre', 0) == "" ? 0 : $request->request->get('ordre', 0);
        $type = $request->request->get('type');

        if($parent_id != 0){
            $activite->setParent($this->activiteRepository->findOneBy(['id' => (int) $parent_id]));
        }
        
        $activite->setNom($nom);
        $activite->setOrdre($ordre);
        $activite->setType($type);

        $this->activiteRepository->add($activite, true);

        return $this->redirectToRoute('app_admin_activite');
    }


    /**
     *  @Route("/activite/sous-activites", name="app_sous_activites")
     */
    public function getSousActivites(Request $request){
        $activite_id = $request->query->get('activite_id'); 
        $etablissement_id = $request->query->get('etablissement_id', '');
        $activite_ids = [];
        if($etablissement_id != '') {
            $etablissement = $this->etablissementRepository->findOneBy(['id' => (int) $etablissement_id]);
            foreach ($etablissement->getActivites() as $activite){
                $activite_ids[] = $activite->getId();
            }
        }

        $activite =  $this->activiteRepository->findOneBy(['id' => (int) $activite_id]);
        $sousActivites = $activite->getEnfants();
        
        $html = $this->twig->render('admin/block/sous-activites.html.twig', [
            'sousActivites' => $sousActivites,
            'activite_ids' => $activite_ids
        ]);
        
        return new JsonResponse([
            "html" => $html
        ]);
    }
}
