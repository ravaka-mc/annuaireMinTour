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
use App\Repository\UserRepository;
use App\Repository\RegionRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserController extends AdminController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }


    /**
     * @Route("/admin/user", name="app_admin_user")
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $users = $this->userRepository->findAll();

        return $this->render('admin/layout/user.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/user/add", name="app_admin_user_add")
     */
    public function add(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(["ROLE_ETABLISSEMENT"]);

            $this->userRepository->add($user, true);

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
     * @Route("/admin/user/{id}/edit", name="app_admin_user_edit")
     */
    public function edit(Request $request, User $user,  UserPasswordHasherInterface $userPasswordHasher): Response
    {
        /**
         * @var User $user
         */
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

        return $this->redirectToRoute('app_admin_user');
    }


    /**
     * @Route("/admin/user/{id}/delete", name="app_admin_user_delete")
     */
    public function delete(Request $request, User $user): Response
    {
        
        $this->userRepository->remove($user, true);

        return $this->redirectToRoute('app_admin_user');
    }
}
