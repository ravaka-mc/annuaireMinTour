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

    /**
     * @ORM\ManyToOne(targetEntity=Activite::class, inversedBy="enfants")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Activite::class, mappedBy="parent")
     */
    private $enfants;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="activitesLicenceA")
     */
    private $categoriesLicenceA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    public function __construct()
    {
        $this->etablissements = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->activitesC = new ArrayCollection();
        $this->categoriesLicenceB = new ArrayCollection();
        $this->categoriesLicenceC = new ArrayCollection();
        $this->enfants = new ArrayCollection();
        $this->categoriesLicenceA = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getEnfants(): Collection
    {
        return $this->enfants;
    }

    public function addEnfant(self $enfant): self
    {
        if (!$this->enfants->contains($enfant)) {
            $this->enfants[] = $enfant;
            $enfant->setParent($this);
        }

        return $this;
    }

    public function removeEnfant(self $enfant): self
    {
        if ($this->enfants->removeElement($enfant)) {
            // set the owning side to null (unless already changed)
            if ($enfant->getParent() === $this) {
                $enfant->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoriesLicenceA(): Collection
    {
        return $this->categoriesLicenceA;
    }

    public function addCategoriesLicenceA(Category $categoriesLicenceA): self
    {
        if (!$this->categoriesLicenceA->contains($categoriesLicenceA)) {
            $this->categoriesLicenceA[] = $categoriesLicenceA;
            $categoriesLicenceA->addActivitesLicenceA($this);
        }

        return $this;
    }

    public function removeCategoriesLicenceA(Category $categoriesLicenceA): self
    {
        if ($this->categoriesLicenceA->removeElement($categoriesLicenceA)) {
            $categoriesLicenceA->removeActivitesLicenceA($this);
        }

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }
}
