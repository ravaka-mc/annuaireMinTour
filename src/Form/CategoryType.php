<?php

namespace App\Form;

use App\Enum\ViewType;
use App\Entity\Activite;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('iconFile', FileType::class)
            ->add('activites', EntityType::class, [
                'class' => Activite::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
            ])
            ->add('viewType', EnumType::class, [
                'class' => ViewType::class,
                'choice_label' => fn ($choice) => match ($choice) {
                    ViewType::Type1 => 'Type d\'affichage 1',
                    ViewType::Type2 => 'Type d\'affichage 2',
                    ViewType::Type3 => 'Type d\'affichage 3',
                    ViewType::Type4 => 'Type d\'affichage 4',
                    ViewType::Type5 => 'Type d\'affichage 5',
                },
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
