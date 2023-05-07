<?php

namespace App\Controller;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegistrationController extends AbstractController
{
    private $categoryRepository;
    private $tokenStorage;
    private $twig;

    public function __construct(CategoryRepository $categoryRepository, TokenStorageInterface $tokenStorage, Environment $twig,){ 
        $this->categoryRepository = $categoryRepository;
        $this->tokenStorage = $tokenStorage;
        $this->twig = $twig;
    }

    /**
     * @Route("/inscription", priority=3, name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $categories = $this->categoryRepository->findAll();
        
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
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
            $entityManager->persist($user);
            $entityManager->flush();

            // Authenticate the user
            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);


            $html = $this->twig->render('front/email/inscription.html.twig', []);

            $message = (new Email())
            ->from('annuaire@tourisme.gov.mg')
            ->to($user->getEmail())
            ->subject('Formulaire de contact')
            ->html($html);

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
            'class' => '',
            'class_wrapper' => '',
            'active' => 'register',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/login", priority=1, name="app_login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {   
        $categories = $this->categoryRepository->findAll();

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('registration/login.html.twig', [
            'last_username' => $lastUsername,
            'categories' => $categories,
            'error'         => $error,
            'class'         => '',
            'active' => 'login',
            'class_wrapper'         => '',
        ]);
    }

    /**
     * @Route("/logout", priority=2, name="app_logout")
    */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
