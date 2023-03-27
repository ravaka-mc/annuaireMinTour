<?php

namespace App\Entity;

use App\Repository\DeleteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeleteRepository::class)
 * @ORM\Table(name="`delete`")
 */
class Delete
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Etablissement::class, inversedBy="delete", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE") 
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
