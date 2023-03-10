<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=EtablissementRepository::class)
 * @Vich\Uploadable
 */
class Etablissement
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteWeb;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gerant;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $membre= false;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateOuverture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreChambres;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $capaciteAccueil;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreCouverts;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreSalaries;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zoneIntervention;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $categorieAutorisation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carteProfessionnelle;

    /**
     * @Vich\UploadableField(mapping="etablissements", fileNameProperty="avatar")
     * 
     * @var File|null
     */
    private $avatarFile;

    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="etablissements")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $category;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $valide = false;

    /**
     * @ORM\ManyToMany(targetEntity=Groupement::class, inversedBy="etablissements")
     */
    private $groupements;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="etablissements")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $region;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $licenceA;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $licenceB;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $licenceC;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateLicenceA;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateLicenceB;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateLicenceC;

    /**
     * @ORM\ManyToOne(targetEntity=Classement::class, inversedBy="etablissements")
     */
    private $classement;

    /**
     * @ORM\ManyToMany(targetEntity=Activite::class, inversedBy="etablissements")
     */
    private $activites;

    public function __construct()
    {
        $this->groupements = new ArrayCollection();
        $this->activites = new ArrayCollection();
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

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): self
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    public function getProprietaire(): ?string
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?string $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getGerant(): ?string
    {
        return $this->gerant;
    }

    public function setGerant(?string $gerant): self
    {
        $this->gerant = $gerant;

        return $this;
    }

    public function isMembre(): ?bool
    {
        return $this->membre;
    }

    public function setMembre(?bool $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getDateOuverture(): ?\DateTimeInterface
    {
        return $this->dateOuverture;
    }

    public function setDateOuverture(?\DateTimeInterface $dateOuverture): self
    {
        $this->dateOuverture = $dateOuverture;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(?string $nif): self
    {
        $this->nif = $nif;

        return $this;
    }

    public function getStat(): ?string
    {
        return $this->stat;
    }

    public function setStat(?string $stat): self
    {
        $this->stat = $stat;

        return $this;
    }

    public function getNombreChambres(): ?int
    {
        return $this->nombreChambres;
    }

    public function setNombreChambres(?int $nombreChambres): self
    {
        $this->nombreChambres = $nombreChambres;

        return $this;
    }

    public function getCapaciteAccueil(): ?string
    {
        return $this->capaciteAccueil;
    }

    public function setCapaciteAccueil(?string $capaciteAccueil): self
    {
        $this->capaciteAccueil = $capaciteAccueil;

        return $this;
    }

    public function getNombreCouverts(): ?int
    {
        return $this->nombreCouverts;
    }

    public function setNombreCouverts(?int $nombreCouverts): self
    {
        $this->nombreCouverts = $nombreCouverts;

        return $this;
    }

    public function getNombreSalaries(): ?int
    {
        return $this->nombreSalaries;
    }

    public function setNombreSalaries(?int $nombreSalaries): self
    {
        $this->nombreSalaries = $nombreSalaries;

        return $this;
    }

    public function getZoneIntervention(): ?string
    {
        return $this->zoneIntervention;
    }

    public function setZoneIntervention(?string $zoneIntervention): self
    {
        $this->zoneIntervention = $zoneIntervention;

        return $this;
    }

    public function getCategorieAutorisation(): ?string
    {
        return $this->categorieAutorisation;
    }

    public function setCategorieAutorisation(?string $categorieAutorisation): self
    {
        $this->categorieAutorisation = $categorieAutorisation;

        return $this;
    }

    public function getCarteProfessionnelle(): ?string
    {
        return $this->carteProfessionnelle;
    }

    public function setCarteProfessionnelle(?string $carteProfessionnelle): self
    {
        $this->carteProfessionnelle = $carteProfessionnelle;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @return null|File
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile ;
    }

    /**
     * @param null|File $avatarFile
     * 
     * @return Etablissement
     */
    public function setAvatarFile(?File $avatarFile): Etablissement
    {
         $this->avatarFile = $avatarFile ;

         return $this ;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(?bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * @return Collection<int, Groupement>
     */
    public function getGroupements(): Collection
    {
        return $this->groupements;
    }

    public function addGroupement(Groupement $groupements): self
    {
        if (!$this->groupements->contains($groupements)) {
            $this->groupements[] = $groupements;
        }

        return $this;
    }

    public function removeGroupement(Groupement $groupements): self
    {
        $this->groupements->removeElement($groupements);

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function isLicenceA(): ?bool
    {
        return $this->licenceA;
    }

    public function setLicenceA(?bool $licenceA): self
    {
        $this->licenceA = $licenceA;

        return $this;
    }

    public function isLicenceB(): ?bool
    {
        return $this->licenceB;
    }

    public function setLicenceB(?bool $licenceB): self
    {
        $this->licenceB = $licenceB;

        return $this;
    }

    public function isLicenceC(): ?bool
    {
        return $this->licenceC;
    }

    public function setLicenceC(?bool $licenceC): self
    {
        $this->licenceC = $licenceC;

        return $this;
    }

    public function getDateLicenceA(): ?\DateTimeInterface
    {
        return $this->dateLicenceA;
    }

    public function setDateLicenceA(?\DateTimeInterface $dateLicenceA): self
    {
        $this->dateLicenceA = $dateLicenceA;

        return $this;
    }

    public function getDateLicenceB(): ?\DateTimeInterface
    {
        return $this->dateLicenceB;
    }

    public function setDateLicenceB(?\DateTimeInterface $dateLicenceB): self
    {
        $this->dateLicenceB = $dateLicenceB;

        return $this;
    }

    public function getDateLicenceC(): ?\DateTimeInterface
    {
        return $this->dateLicenceC;
    }

    public function setDateLicenceC(?\DateTimeInterface $dateLicenceC): self
    {
        $this->dateLicenceC = $dateLicenceC;

        return $this;
    }

    public function getClassement(): ?Classement
    {
        return $this->classement;
    }

    public function setClassement(?Classement $classement): self
    {
        $this->classement = $classement;

        return $this;
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

    public function getLicences(): ?string
    {
        $licences = [];
        
        if($this->licenceA) $licences[] = 'A';
        if($this->licenceB) $licences[] = 'B';
        if($this->licenceC) $licences[] = 'C';

        return  \implode(', ', $licences);
    }
}
