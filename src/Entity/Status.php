<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\OneToOne(inversedBy: 'status', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'permission_id', referencedColumnName: 'permID')]

    private ?Permission $statusPermId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
            $this->active = $active;

        return $this;
    }

    public function getStatusPermId(): ?Permission
    {
        return $this->statusPermId;
    }

    public function setStatusPermId(?Permission $statusPermId): self
    {
        $this->statusPermId = $statusPermId;

        return $this;
    }
}
