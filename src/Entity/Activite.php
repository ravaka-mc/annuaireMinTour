<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActiviteRepository::class)
 */
class Activite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Etablissement::class, mappedBy="activites")
     */
    private $etablissements;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="activites")
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="activitesB")
     */
    private $activitesC;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="activitesLicenceB")
     * @ORM\JoinTable(name="category_activite_licence_b")
     */
    private $categoriesLicenceB;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="activitesLicenceC")
     * @ORM\JoinTable(name="category_activite_licence_c")
     */
    private $categoriesLicenceC;

    public function __construct()
    {
        $this->etablissements = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->activitesC = new ArrayCollection();
        $this->categoriesLicenceB = new ArrayCollection();
        $this->categoriesLicenceC = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Etablissement>
     */
    public function getEtablissements(): Collection
    {
        return $this->etablissements;
    }

    public function addEtablissement(Etablissement $etablissement): self
    {
        if (!$this->etablissements->contains($etablissement)) {
            $this->etablissements[] = $etablissement;
            $etablissement->addActivite($this);
        }

        return $this;
    }

    public function removeEtablissement(Etablissement $etablissement): self
    {
        if ($this->etablissements->removeElement($etablissement)) {
            $etablissement->removeActivite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addActivite($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeActivite($this);
        }

        return $this;
    }


    public function __toString(){
        return $this->nom;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getActivitesC(): Collection
    {
        return $this->activitesC;
    }

    public function addActivitesC(Category $activitesC): self
    {
        if (!$this->activitesC->contains($activitesC)) {
            $this->activitesC[] = $activitesC;
            $activitesC->addActivitesB($this);
        }

        return $this;
    }

    public function removeActivitesC(Category $activitesC): self
    {
        if ($this->activitesC->removeElement($activitesC)) {
            $activitesC->removeActivitesB($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoriesLicenceB(): Collection
    {
        return $this->categoriesLicenceB;
    }

    public function addCategoriesLicenceB(Category $categoriesLicenceB): self
    {
        if (!$this->categoriesLicenceB->contains($categoriesLicenceB)) {
            $this->categoriesLicenceB[] = $categoriesLicenceB;
            $categoriesLicenceB->addActivitesLicenceB($this);
        }

        return $this;
    }

    public function removeCategoriesLicenceB(Category $categoriesLicenceB): self
    {
        if ($this->categoriesLicenceB->removeElement($categoriesLicenceB)) {
            $categoriesLicenceB->removeActivitesLicenceB($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoriesLicenceC(): Collection
    {
        return $this->categoriesLicenceC;
    }

    public function addCategoriesLicenceC(Category $categoriesLicenceC): self
    {
        if (!$this->categoriesLicenceC->contains($categoriesLicenceC)) {
            $this->categoriesLicenceC[] = $categoriesLicenceC;
            $categoriesLicenceC->addActivitesLicenceC($this);
        }

        return $this;
    }

    public function removeCategoriesLicenceC(Category $categoriesLicenceC): self
    {
        if ($this->categoriesLicenceC->removeElement($categoriesLicenceC)) {
            $categoriesLicenceC->removeActivitesLicenceC($this);
        }

        return $this;
    }
}
