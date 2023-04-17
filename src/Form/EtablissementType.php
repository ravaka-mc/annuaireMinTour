<?php

namespace App\Form;

use DateTime;
use App\Entity\User;
use App\Entity\Region;
use App\Entity\Activite;
use App\Entity\Category;
use App\Entity\Classement;
use App\Entity\Groupement;
use App\Entity\Etablissement;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EtablissementType extends AbstractType
{
    private $userRepository;
    private $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $defaultUser = $this->userRepository->find($user->getId());

        $builder
            ->add('auteur')
            ->add('ville', TextType::class, [
                'required' => true,
            ])
            ->add('codePostal', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'La valeur du Code postal est incorrecte',
                    ])
                ],
            ])
            
            ->add('adresse', TextType::class, [
                'required' => true,
            ])
            ->add('telephone', TelType::class, [
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                ],
                'required' => true,
            ])
            ->add('avatarFile', FileType::class,[
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier valide (jpeg, png, svg)',
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '--------------------',
            ])
            ->add('createdBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => function ($createdBy) {
                    return $createdBy->getDisplayName();
                },
                'expanded' => false,
                'multiple' => false,
                'required' => false,
            ])
            ->add('membre', ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
            

            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $data = $event->getData();
                    $this->dynamiqueForm($event->getForm(), $data->getCategory());
                }
            );

            $builder->get('category')->addEventListener(
                FormEvents::POST_SUBMIT, 
                function (FormEvent $event) {
                    $data = $event->getForm()->getData();
                    $form = $event->getForm()->getParent();
                    $this->dynamiqueForm($form, $data);
                }
            );
    }

    private function dynamiqueForm(FormInterface $form, Category $category = null) : void {
        
        if($category == null) return;

        $form->add('groupements', EntityType::class, [
            'class' => Groupement::class,
            'choice_label' => 'nom',
            'expanded' => true,
            'multiple' => true,
            'required' => false,
            'choices' => $category->getGroupements()
        ]);

        switch($category->getViewType()){
            case 'TYPE_1':
                $form->add('activites', EntityType::class, [
                    'class' => Activite::class,
                    'choice_label' => 'nom',
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                    'choices' => $category->getActivites()
                ])
                ->add('nom', TextType::class, ['label' => 'Dénomination sociale'])
                ->add('siteWeb', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^((?:https?:\/\/)?(?:www\.)?[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,}(?:\/[\w\-\.]*)*\/?)$/',
                            'message' => 'La valeur du Site Web est incorrecte',
                        ])
                    ],
                ])
                ->add('proprietaire', TextType::class, [
                    'constraints' => [
                        new Regex('/[^\d]/')
                    ],
                    'required' => true,
                ])
                ->add('gerant', TextType::class, [
                    'constraints' => [
                        new Regex('/[^\d]/')
                    ],
                    'required' => true,
                ])
                ->add('region', EntityType::class, [
                    'class' => Region::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => '--------------------',
                    'required' => true,
                ])
                ->add('nif', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du NIF est incorrecte',
                        ])
                    ],
                ])
                ->add('stat', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du Stat est incorrecte',
                        ])
                    ],
                ])
                ->add('licenceA', CheckboxType::class, [
                    'label'    => 'A',
                    'required' => false,
                ])
                ->add('dateLicenceA', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                ])
                ->add('licenceB', CheckboxType::class, [
                    'label'    => 'B',
                    'required' => false,
                ])
                ->add('dateLicenceB', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                ])
                ->add('licenceC', CheckboxType::class, [
                    'label'    => 'C',
                    'required' => false,
                ])
                ->add('dateLicenceC', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                ]);
                break;
            case 'TYPE_2':
                $form->add('classement', EntityType::class, [
                    'class' => Classement::class,
                    'choice_label' => 'nom',
                    'expanded' => true,
                    'multiple' => false,
                    'empty_data' => '',
                    'placeholder' => false,
                    'required' => false,
                ])
                ->add('nom', TextType::class, ['label' => 'Dénomination sociale'])
                ->add('siteWeb', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^((?:https?:\/\/)?(?:www\.)?[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,}(?:\/[\w\-\.]*)*\/?)$/',
                            'message' => 'La valeur du Site Web est incorrecte',
                        ])
                    ],
                ])
                ->add('proprietaire', TextType::class, [
                    'constraints' => [
                        new Regex('/[^\d]/')
                    ],
                    'required' => true,
                ])
                ->add('gerant', TextType::class, [
                    'constraints' => [
                        new Regex('/[^\d]/')
                    ],
                    'required' => true,
                ])
                ->add('region', EntityType::class, [
                    'class' => Region::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => '--------------------',
                    'required' => true,
                ])
                ->add('activites', EntityType::class, [
                    'class' => Activite::class,
                    'choice_label' => 'nom',
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                    'choices' => $category->getActivites()
                ])
                ->add('dateOuverture', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                ])
                ->add('reference', TextType::class, [
                    'required' => true,
                ])
                ->add('nif', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du NIF est incorrecte',
                        ])
                    ],
                ])
                ->add('stat', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du Stat est incorrecte',
                        ])
                    ],
                ])
                ->add('nombreChambres', IntegerType::class, [
                    'required' => true,
                ])
                ->add('capaciteAccueil', IntegerType::class, [
                    'required' => true,
                ])
                ->add('nombreSalaries', IntegerType::class, [
                    'required' => true,
                ]);
                break;
            case 'TYPE_3':
                $form->add('classement', EntityType::class, [
                    'class' => Classement::class,
                    'choice_label' => 'nom',
                    'expanded' => true,
                    'multiple' => false,
                    'empty_data' => '',
                    'placeholder' => false,
                    'required' => false,
                ])
                ->add('nom', TextType::class, ['label' => 'Dénomination sociale'])
                ->add('siteWeb', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^((?:https?:\/\/)?(?:www\.)?[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,}(?:\/[\w\-\.]*)*\/?)$/',
                            'message' => 'La valeur du Site Web est incorrecte',
                        ])
                    ],
                ])
                ->add('proprietaire', TextType::class, [
                    'constraints' => [
                        new Regex('/[^\d]/')
                    ],
                    'required' => true,
                ])
                ->add('gerant', TextType::class, [
                    'required' => true,
                    'constraints' => [
                        new Regex('/[^\d]/')
                    ],
                ])
                ->add('region', EntityType::class, [
                    'class' => Region::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => '--------------------',
                    'required' => true,
                ])
                ->add('activites', EntityType::class, [
                    'class' => Activite::class,
                    'choice_label' => 'nom',
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                    'choices' => $category->getActivites()
                ])
                ->add('dateOuverture', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                ])
                ->add('reference', TextType::class, [
                    'required' => true,
                ])
                ->add('nif', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du NIF est incorrecte',
                        ])
                    ],
                ])
                ->add('stat', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du Stat est incorrecte',
                        ])
                    ],
                ])
                ->add('nombreChambres', IntegerType::class, [
                    'required' => true,
                ])
                ->add('capaciteAccueil', IntegerType::class, [
                    'required' => true,
                ])
                ->add('salleConference', IntegerType::class, [
                    'required' => true,
                ])
                ->add('nombreCouverts', IntegerType::class, [
                    'required' => true,
                ])
                ->add('nombreSalaries', IntegerType::class, [
                    'required' => true,
                ]);
                break;
            case 'TYPE_4':
                $form->add('classement', EntityType::class, [
                    'class' => Classement::class,
                    'choice_label' => 'nom',
                    'expanded' => true,
                    'multiple' => false,
                    'empty_data' => '',
                    'placeholder' => false,
                    'required' => false,
                ])
                ->add('nom', TextType::class, ['label' => 'Dénomination sociale'])
                ->add('siteWeb', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^((?:https?:\/\/)?(?:www\.)?[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,}(?:\/[\w\-\.]*)*\/?)$/',
                            'message' => 'La valeur du Site Web est incorrecte',
                        ])
                    ],
                ])
                ->add('proprietaire', TextType::class, [
                    'required' => true,
                    'constraints' => [
                        new Regex('/[^\d]/')
                    ],
                ])
                ->add('gerant', TextType::class, [
                    'required' => true,
                    'constraints' => [
                        new Regex('/[^\d]/')
                    ],
                ])
                ->add('region', EntityType::class, [
                    'class' => Region::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => '--------------------',
                    'required' => true,
                ])
                ->add('activites', EntityType::class, [
                    'class' => Activite::class,
                    'choice_label' => 'nom',
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                    'choices' => $category->getActivites()
                ])
                ->add('dateOuverture', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                ])
                ->add('reference', TextType::class, [
                    'required' => true,
                ])
                ->add('nif', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du NIF est incorrecte',
                        ])
                    ],
                ])
                ->add('stat', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du Stat est incorrecte',
                        ])
                    ],
                ])
                ->add('nombreCouverts', IntegerType::class, [
                    'required' => true,
                ])
                ->add('nombreSalaries', IntegerType::class, [
                    'required' => true,
                ]);
                break;
            case 'TYPE_5':
                $form->add('zoneIntervention')
                ->add('nom', TextType::class, ['label' => 'Nom et Prénom'])
                ->add('categorieAutorisation')
                ->add('carteProfessionnelle')
                ->add('autreGroupement')
                ->add('nif', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du NIF est incorrecte',
                        ])
                    ],
                ])
                ->add('stat', TextType::class, [
                    'constraints' => [
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => 'La valeur du Stat est incorrecte',
                        ])
                    ],
                ]);
                break;

        }
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etablissement::class,
        ]);
    }
}
