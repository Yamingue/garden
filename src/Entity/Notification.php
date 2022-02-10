<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $create_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $update_at;

    /**
     * @ORM\ManyToOne(targetEntity=Ecole::class, inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ecole;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $salle;

    /**
     * @ORM\ManyToOne(targetEntity=Enfant::class, inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $enfant;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $time;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $waiting;

    /**
     * @ORM\Column(type="boolean")
     */
    private $close;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_ready;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->create_at = new \DateTimeImmutable();
        $this->update_at = new \DateTimeImmutable();
        $this->close = false;
    }

    
    /**
     * @ORM\PreUpdate()
     */
    public function update()
    {
        $this->update_at = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeImmutable $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeImmutable $update_at): self
    {
        $this->update_at = $update_at;

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

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getEnfant(): ?Enfant
    {
        return $this->enfant;
    }

    public function setEnfant(?Enfant $enfant): self
    {
        $this->enfant = $enfant;

        return $this;
    }

    public function getParent(): ?User
    {
        return $this->parent;
    }

    public function setParent(?User $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getTime(): ?\DateTimeImmutable
    {
        return $this->time;
    }

    public function setTime(?\DateTimeImmutable $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getWaiting(): ?bool
    {
        return $this->waiting;
    }

    public function setWaiting(?bool $waiting): self
    {
        $this->waiting = $waiting;

        return $this;
    }

    public function getClose(): ?bool
    {
        return $this->close;
    }

    public function setClose(bool $close): self
    {
        $this->close = $close;

        return $this;
    }

    public function getRestTime()
    {
        $current = new \DateTimeImmutable();
        $milliceCurrent = $current->format('Uv');
        if (!$this->id) {
            # code...
            return 0;
        }
        $millice = $this->time->format('Uv');
        $time = (($millice - $milliceCurrent)/1000)/60;
        if ($time <=0) {
            return 0;
        }
       // dump($milliceCurrent,$millice);
        return $time;
       // $pass->time;
    }

    public function getIsReady(): ?bool
    {
        return $this->is_ready;
    }

    public function setIsReady(?bool $is_ready): self
    {
        $this->is_ready = $is_ready;

        return $this;
    }
}
