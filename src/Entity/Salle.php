<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 */
class Salle
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
     * @ORM\ManyToOne(targetEntity=Ecole::class, inversedBy="salles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ecole;

    /**
     * @ORM\OneToMany(targetEntity=Gardienne::class, mappedBy="salles", orphanRemoval=true)
     */
    private $gardiennes;

    /**
     * @ORM\OneToMany(targetEntity=Enfant::class, mappedBy="salle", orphanRemoval=true)
     */
    private $enfants;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="salle", orphanRemoval=true)
     */
    private $notifications;

    public function __construct()
    {
        $this->gardiennes = new ArrayCollection();
        $this->enfants = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    public function getEcole(): ?Ecole
    {
        return $this->ecole;
    }

    public function setEcole(?Ecole $ecole): self
    {
        $this->ecole = $ecole;

        return $this;
    }

    /**
     * @return Collection|Gardienne[]
     */
    public function getGardiennes(): Collection
    {
        return $this->gardiennes;
    }

    public function addGardienne(Gardienne $gardienne): self
    {
        if (!$this->gardiennes->contains($gardienne)) {
            $this->gardiennes[] = $gardienne;
            $gardienne->setSalles($this);
        }

        return $this;
    }

    public function removeGardienne(Gardienne $gardienne): self
    {
        if ($this->gardiennes->removeElement($gardienne)) {
            // set the owning side to null (unless already changed)
            if ($gardienne->getSalles() === $this) {
                $gardienne->setSalles(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Collection|Enfant[]
     */
    public function getEnfants(): Collection
    {
        return $this->enfants;
    }

    public function addEnfant(Enfant $enfant): self
    {
        if (!$this->enfants->contains($enfant)) {
            $this->enfants[] = $enfant;
            $enfant->setSalle($this);
        }

        return $this;
    }

    public function removeEnfant(Enfant $enfant): self
    {
        if ($this->enfants->removeElement($enfant)) {
            // set the owning side to null (unless already changed)
            if ($enfant->getSalle() === $this) {
                $enfant->setSalle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setSalle($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getSalle() === $this) {
                $notification->setSalle(null);
            }
        }

        return $this;
    }
}
