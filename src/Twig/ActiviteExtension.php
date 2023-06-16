<?php

namespace App\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use App\Repository\ActiviteRepository;

class ActiviteExtension extends AbstractExtension
{
    private $activiteRepository;
    
    public function __construct(ActiviteRepository $activiteRepository){
        $this->activiteRepository = $activiteRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('type_activite', [$this, 'typeActivite']),
            new TwigFunction('classement_activite', [$this, 'classementActivite']),
            new TwigFunction('hebergement_activite', [$this, 'hebergementActivite']),
            new TwigFunction('restaurant_activite', [$this, 'restaurantActivite']),
        ];
    }

    public function typeActivite($id)
    {
        $activite = $this->activiteRepository->findOneBy(['id' => (int) $id]);
        
        return $activite->getType();
    }

    public function classementActivite($id)
    {
        $activite = $this->activiteRepository->findOneBy(['id' => (int) $id]);
        
        return $activite->isClassement();
    }

    public function hebergementActivite($activitesForm)
    {
        foreach ($activitesForm->getIterator() as $child){
            if($this->typeActivite($child->vars['value']) == 'TYPE_HEBERGEMENT'){
                return true;
            }
        }
        return false;
    }

    public function restaurantActivite($activitesForm)
    {
        foreach ($activitesForm->getIterator() as $child){
            if($this->typeActivite($child->vars['value']) == 'TYPE_RESTAURATION'){
                return true;
            }
        }

        return false;
    }
}