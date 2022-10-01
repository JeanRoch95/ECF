<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: UserStructure::class, inversedBy: 'permissions')]
    private Collection $structurePerm;

    public function __construct()
    {
        $this->structurePerm = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, UserStructure>
     */
    public function getStructurePerm(): Collection
    {
        return $this->structurePerm;
    }

    public function addStructurePerm(UserStructure $structurePerm): self
    {
        if (!$this->structurePerm->contains($structurePerm)) {
            $this->structurePerm->add($structurePerm);
        }

        return $this;
    }

    public function removeStructurePerm(UserStructure $structurePerm): self
    {
        $this->structurePerm->removeElement($structurePerm);

        return $this;
    }
}
