<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 *  @ApiResource()
 */
class UserGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false,  onDelete="CASCADE")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class)
     * @ORM\JoinColumn(nullable=false,  onDelete="CASCADE")
     */
    private $groupId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getGroupId(): ?Group
    {
        return $this->groupId;
    }

    public function setGroupId(?Group $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }
}
