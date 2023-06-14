<?php

namespace App\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use App\Repository\ActiviteRepository;

class TypeActiviteExtension extends AbstractExtension
{
    private $activiteRepository;
    
    public function __construct(ActiviteRepository $activiteRepository){
        $this->activiteRepository = $activiteRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('type_activite', [$this, 'typeActivite']),
        ];
    }

    public function typeActivite($id)
    {
        $activite = $this->activiteRepository->findOneBy(['id' => (int) $id]);
        
        return $activite->getType();
    }
}