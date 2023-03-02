<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Etablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EtablissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('auteur')
            ->add('adresse')
            ->add('telephone')
            ->add('email')
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
                'widget' => 'single_text'
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
