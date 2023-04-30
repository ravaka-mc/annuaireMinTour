<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=EtablissementRepository::class)
 * @Vich\Uploadable
 * @UniqueEntity(fields={"nom"}, message="Cet établissement est déjà inscrit. Si vous voulez gérer cet établissement, vous pouvez nous contacter")
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
     * @ORM\Column(type="string", length=255, unique=true)
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Slug(fields={"nom"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateValidation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="etablissements")
     */
    private $createdBy;

    /**
     * @ORM\OneToOne(targetEntity=Refuse::class, mappedBy="etablissement", cascade={"persist", "remove"})
     */
    private $refused;

    /**
     * @ORM\OneToMany(targetEntity=Signaler::class, mappedBy="etablissement", orphanRemoval=true)
     */
    private $signalers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $autreGroupement;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $statut;

    /**
     * @ORM\OneToOne(targetEntity=Delete::class, mappedBy="etablissement", cascade={"persist", "remove"})
     */
    private $delete;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $salleConference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\ManyToOne(targetEntity=District::class, inversedBy="etablissements")
     */
    private $district;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreLit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $superficieSalle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreVoiture;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreSalaireFemme;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $autreActivite;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $categorieGuide = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agrement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referenceA;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referenceB;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referenceC;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreResto;

    public function __construct()
    {
        $this->groupements = new ArrayCollection();
        $this->activites = new ArrayCollection();
        $this->signalers = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTimeInterface $dateValidation): self
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getRefused(): ?Refuse
    {
        return $this->refused;
    }

    public function setRefused(Refuse $refused): self
    {
        // set the owning side of the relation if necessary
        if ($refused->getEtablissement() !== $this) {
            $refused->setEtablissement($this);
        }

        $this->refused = $refused;

        return $this;
    }

    public function getDelete(): ?Delete
    {
        return $this->delete;
    }

    public function setDelete(Delete $delete): self
    {
        // set the owning side of the relation if necessary
        if ($delete->getEtablissement() !== $this) {
            $delete->setEtablissement($this);
        }

        $this->delete = $delete;

        return $this;
    }

    /**
     * @return Collection<int, Signaler>
     */
    public function getSignalers(): Collection
    {
        return $this->signalers;
    }

    public function addSignaler(Signaler $signaler): self
    {
        if (!$this->signalers->contains($signaler)) {
            $this->signalers[] = $signaler;
            $signaler->setEtablissement($this);
        }

        return $this;
    }

    public function removeSignaler(Signaler $signaler): self
    {
        if ($this->signalers->removeElement($signaler)) {
            // set the owning side to null (unless already changed)
            if ($signaler->getEtablissement() === $this) {
                $signaler->setEtablissement(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->nom;
    }

    public function getAutreGroupement(): ?string
    {
        return $this->autreGroupement;
    }

    public function setAutreGroupement(?string $autreGroupement): self
    {
        $this->autreGroupement = $autreGroupement;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getSalleConference(): ?string
    {
        return $this->salleConference;
    }

    public function setSalleConference(?string $salleConference): self
    {
        $this->salleConference = $salleConference;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getNombreLit(): ?int
    {
        return $this->nombreLit;
    }

    public function setNombreLit(?int $nombreLit): self
    {
        $this->nombreLit = $nombreLit;

        return $this;
    }

    public function getSuperficieSalle(): ?int
    {
        return $this->superficieSalle;
    }

    public function setSuperficieSalle(?int $superficieSalle): self
    {
        $this->superficieSalle = $superficieSalle;

        return $this;
    }

    public function getNombreVoiture(): ?int
    {
        return $this->nombreVoiture;
    }

    public function setNombreVoiture(?int $nombreVoiture): self
    {
        $this->nombreVoiture = $nombreVoiture;

        return $this;
    }

    public function getNombreSalaireFemme(): ?int
    {
        return $this->nombreSalaireFemme;
    }

    public function setNombreSalaireFemme(?int $nombreSalaireFemme): self
    {
        $this->nombreSalaireFemme = $nombreSalaireFemme;

        return $this;
    }

    public function getAutreActivite(): ?string
    {
        return $this->autreActivite;
    }

    public function setAutreActivite(?string $autreActivite): self
    {
        $this->autreActivite = $autreActivite;

        return $this;
    }

    public function getCategorieGuide(): ?array
    {
        return $this->categorieGuide;
    }

    public function setCategorieGuide(?array $categorieGuide): self
    {
        $this->categorieGuide = $categorieGuide;

        return $this;
    }

    public function getAgrement(): ?string
    {
        return $this->agrement;
    }

    public function setAgrement(?string $agrement): self
    {
        $this->agrement = $agrement;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getReferenceA(): ?string
    {
        return $this->referenceA;
    }

    public function setReferenceA(?string $referenceA): self
    {
        $this->referenceA = $referenceA;

        return $this;
    }

    public function getReferenceB(): ?string
    {
        return $this->referenceB;
    }

    public function setReferenceB(?string $referenceB): self
    {
        $this->referenceB = $referenceB;

        return $this;
    }

    public function getReferenceC(): ?string
    {
        return $this->referenceC;
    }

    public function setReferenceC(?string $referenceC): self
    {
        $this->referenceC = $referenceC;

        return $this;
    }

    public function getNombreResto(): ?int
    {
        return $this->nombreResto;
    }

    public function setNombreResto(?int $nombreResto): self
    {
        $this->nombreResto = $nombreResto;

        return $this;
    }
}
