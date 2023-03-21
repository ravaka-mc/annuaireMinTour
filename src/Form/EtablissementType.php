<?php

namespace App\Form;

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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('nom')
            ->add('auteur')
            ->add('adresse')
            ->add('telephone')
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                ],
            ])
            ->add('siteWeb')
            ->add('avatarFile', FileType::class,[
                'required' => false,
                'mapped' => false,
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
                'choice_label' => 'nom',
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
            ])
            ->add('groupements', EntityType::class, [
                'class' => Groupement::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => true,
                'required' => false,
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
                ->add('proprietaire')
                ->add('gerant')
                ->add('region', EntityType::class, [
                    'class' => Region::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => '--------------------',
                    'required' => false,
                ])
                ->add('dateOuverture', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                ])
                ->add('reference')
                ->add('nif')
                ->add('stat')
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
                ->add('proprietaire')
                ->add('gerant')
                ->add('region', EntityType::class, [
                    'class' => Region::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => '--------------------',
                    'required' => false,
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
                ->add('reference')
                ->add('nif')
                ->add('stat')
                ->add('nombreChambres')
                ->add('capaciteAccueil')
                ->add('nombreCouverts')
                ->add('nombreSalaries');
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
                ->add('proprietaire')
                ->add('gerant')
                ->add('region', EntityType::class, [
                    'class' => Region::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => '--------------------',
                    'required' => false,
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
                ->add('reference')
                ->add('nif')
                ->add('stat')
                ->add('nombreCouverts')
                ->add('nombreSalaries');
                break;
            case 'TYPE_5':
                $form->add('zoneIntervention')
                ->add('categorieAutorisation')
                ->add('carteProfessionnelle')
                ->add('nif')
                ->add('stat');
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
