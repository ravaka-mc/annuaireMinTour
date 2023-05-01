<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Vich\Uploadable
 */
class Category
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
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @Vich\UploadableField(mapping="categories", fileNameProperty="icon")
     * 
     * @var File|null
     */
    private $iconFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity=Etablissement::class, mappedBy="category")
     */
    private $etablissements;

    /**
     * @ORM\ManyToMany(targetEntity=Activite::class, inversedBy="categories")
     */
    private $activites;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $viewType;

    /**
     * @ORM\ManyToMany(targetEntity=Groupement::class, inversedBy="categories")
     */
    private $groupements;

    /**
     * @ORM\ManyToMany(targetEntity=Activite::class, inversedBy="categoriesLicenceB")
     * @ORM\JoinTable(name="category_activite_licence_b")
     */
    private $activitesLicenceB;

    /**
     * @ORM\ManyToMany(targetEntity=Activite::class, inversedBy="categoriesLicenceC")
     * @ORM\JoinTable(name="category_activite_licence_c")
     */
    private $activitesLicenceC;

    public function __construct()
    {
        $this->etablissements = new ArrayCollection();
        $this->activites = new ArrayCollection();
        $this->groupements = new ArrayCollection();
        $this->activitesLicenceB = new ArrayCollection();
        $this->activitesLicenceC = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return null|File
     */
    public function getIconFile(): ?File
    {
        return $this->iconFile ;
    }

    /**
     * @param null|File $iconFile
     * 
     * @return Category
     */
    public function setIconFile(?File $iconFile): Category
    {
         $this->iconFile = $iconFile ;

         return $this ;
    }

    /**
     * @return Collection<int, Etablissement>
     */
    public function getEtablissements(): Collection
    {
        $criteria = Criteria::create()
        ->where(Criteria::expr()->eq('statut', 'valide'))
        ->orderBy(['dateValidation' => 'DESC']);

        return $this->etablissements->matching($criteria);
    }

    public function addEtablissement(Etablissement $etablissement): self
    {
        if (!$this->etablissements->contains($etablissement)) {
            $this->etablissements[] = $etablissement;
            $etablissement->setCategory($this);
        }

        return $this;
    }

    public function removeEtablissement(Etablissement $etablissement): self
    {
        if ($this->etablissements->removeElement($etablissement)) {
            // set the owning side to null (unless already changed)
            if ($etablissement->getCategory() === $this) {
                $etablissement->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->nom;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        $this->activites->removeElement($activite);

        return $this;
    }

    public function getViewType(): ?string
    {
        return $this->viewType;
    }

    public function setViewType(?string $viewType): self
    {
        $this->viewType = $viewType;

        return $this;
    }

    /**
     * @return Collection<int, Groupement>
     */
    public function getGroupements(): Collection
    {
        return $this->groupements;
    }

    public function addGroupement(Groupement $groupement): self
    {
        if (!$this->groupements->contains($groupement)) {
            $this->groupements[] = $groupement;
        }

        return $this;
    }

    public function removeGroupement(Groupement $groupement): self
    {
        $this->groupements->removeElement($groupement);

        return $this;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivitesLicenceB(): Collection
    {
        return $this->activitesLicenceB;
    }

    public function addActivitesLicenceB(Activite $activitesLicenceB): self
    {
        if (!$this->activitesLicenceB->contains($activitesLicenceB)) {
            $this->activitesLicenceB[] = $activitesLicenceB;
        }

        return $this;
    }

    public function removeActivitesLicenceB(Activite $activitesLicenceB): self
    {
        $this->activitesLicenceB->removeElement($activitesLicenceB);

        return $this;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivitesLicenceC(): Collection
    {
        return $this->activitesLicenceC;
    }

    public function addActivitesLicenceC(Activite $activitesLicenceC): self
    {
        if (!$this->activitesLicenceC->contains($activitesLicenceC)) {
            $this->activitesLicenceC[] = $activitesLicenceC;
        }

        return $this;
    }

    public function removeActivitesLicenceC(Activite $activitesLicenceC): self
    {
        $this->activitesLicenceC->removeElement($activitesLicenceC);

        return $this;
    }
}
