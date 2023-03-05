<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Region;
use App\Entity\Category;
use App\Entity\Classement;
use App\Entity\Groupement;
use App\Entity\Etablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EtablissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ->add('proprietaire')
            ->add('gerant')
            ->add('membre', ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false,
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
            ->add('nombreSalaries')
            ->add('zoneIntervention')
            ->add('categorieAutorisation')
            ->add('carteProfessionnelle')
            ->add('avatarFile', FileType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '--------------------',
            ])
            ->add('groupements', EntityType::class, [
                'class' => Groupement::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('classement', EntityType::class, [
                'class' => Classement::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('activites', EntityType::class, [
                'class' => Activite::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '--------------------',
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
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etablissement::class,
        ]);
    }
}
