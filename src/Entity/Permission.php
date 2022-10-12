<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'permID', type: 'integer')]
    #[ORM\Id]
    private int $permID;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: UserStructure::class, inversedBy: 'permissions')]
    #[ORM\JoinColumn(name: 'permission_id', referencedColumnName: 'permID')]
    private Collection $usersStructures;


    public function __construct()
    {
        $this->usersStructures = new ArrayCollection();
    }

    public function getPermID(): ?int
    {
        return $this->permID;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, UserStructure>
     */
    public function getUsersStructures(): Collection
    {
        return $this->usersStructures;
    }

    public function addUsersStructures(UserStructure $usersStructures): self
    {
        if (!$this->usersStructures->contains($usersStructures)) {
            $this->usersStructures->add($usersStructures);
        }

        return $this;
    }

    public function removeUsersStructures(UserStructure $usersStructures): self
    {
        $this->usersStructures->removeElement($usersStructures);

        return $this;
    }
}
