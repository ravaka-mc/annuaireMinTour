<?php

namespace App\Entity;

use App\Repository\RefuseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RefuseRepository::class)
 */
class Refuse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Etablissement::class, inversedBy="refused", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $etablissement;

    /**
     * @ORM\Column(type="text")
     */
    private $raison;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }
}
