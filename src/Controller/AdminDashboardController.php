<?php

namespace App\Controller;


use Symfony\Component\Form\Form;
use App\Repository\CategoryRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    private $categoryRepository;
    private $security;
    
    public function __construct(CategoryRepository $categoryRepository,  Security $security){
        $this->categoryRepository = $categoryRepository;
        $this->security = $security;
    }

    /**
     * @Route("/admin/", name="app_admin")
     */
    public function dashboard(): Response
    {   
        $user = $this->security->getUser();

        if(in_array('ROLE_ETABLISSEMENT', $user->getRoles()))
            return $this->redirectToRoute('app_dashboard');

        return $this->render('admin/layout/dashboard.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
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
}
