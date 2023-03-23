<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Category;
use App\Entity\Groupement;
use App\Repository\ActiviteRepository;
use App\Repository\GroupementRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('iconFile', FileType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('activites', EntityType::class, [
                'class' => Activite::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'query_builder' => function (ActiviteRepository $activiteRepository) {
                    return $activiteRepository->createQueryBuilder('a')
                    ->orderBy('a.nom', 'ASC');
                },
            ])
            ->add('groupements', EntityType::class, [
                'class' => Groupement::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'query_builder' => function (GroupementRepository $groupementRepository) {
                    return $groupementRepository->createQueryBuilder('g')
                    ->orderBy('g.nom', 'ASC');
                },
            ])
            ->add('viewType', ChoiceType::class, [
                'choices' => [
                    'Type d\'affichage 1' => 'TYPE_1',
                    'Type d\'affichage 2' => 'TYPE_2',
                    'Type d\'affichage 3' => 'TYPE_3',
                    'Type d\'affichage 4' => 'TYPE_4',
                    'Type d\'affichage 5' => 'TYPE_5',
                ],
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
