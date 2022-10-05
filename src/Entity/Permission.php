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
    private Collection $userPermission;

    #[ORM\OneToOne(mappedBy: 'statusPermId', cascade: ['persist', 'remove'])]
    private ?Status $status = null;

    public function __construct()
    {
        $this->userPermission = new ArrayCollection();
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
    public function getUserPermission(): Collection
    {
        return $this->userPermission;
    }

    public function addUserPermission(UserStructure $userPermission): self
    {
        if (!$this->userPermission->contains($userPermission)) {
            $this->userPermission->add($userPermission);
        }

        return $this;
    }

    public function removeUserPermission(UserStructure $userPermission): self
    {
        $this->userPermission->removeElement($userPermission);

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        // unset the owning side of the relation if necessary
//        if ($status === null && $this->status !== null) {
//            $this->status->setStatusPermId(null);
//        }

        // set the owning side of the relation if necessary
//        if ($status !== null && $status->getStatusPermId() !== $this) {
//            $status->setStatusPermId($this);
//        }

        $this->status = $status;

        return $this;
    }
}
