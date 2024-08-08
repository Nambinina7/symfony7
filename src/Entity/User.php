<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EntityTimestampTrait;
    public const PATH_USER = "/user/images/";

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_EMPLOYER = 'ROLE_EMPLOYER';
    public const ROLE_RH = 'ROLE_RH';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLES = [
        self::ROLE_EMPLOYER => self::ROLE_EMPLOYER,
        self::ROLE_RH => self::ROLE_RH,
        self::ROLE_ADMIN => self::ROLE_ADMIN,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'permission:read', 'holyday:read'])]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'user_upload', fileNameProperty: 'image')]
    #[SerializedName('src')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read'])]
    public ?string $image = null;

    #[Groups(['user:read'])]
    public ?string $path = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'permission:read', 'holyday:read'])]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [self::ROLE_USER];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = '';

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'permission:read', 'holyday:read'])]
    private ?string $position = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'permission:read', 'holyday:read'])]
    #[Assert\Length(min: 6, max: 25)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'permission:read', 'holyday:read'])]
    #[Assert\Length(min: 6, max: 25)]
    private ?string $lastName = null;

    #[ORM\OneToMany(targetEntity: Permission::class, mappedBy: 'userPermissions')]
    private Collection $permissions;

    #[ORM\ManyToMany(targetEntity: Holyday::class, mappedBy: 'userHolydays')]
    private Collection $holydays;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $lastChangeRequest = null;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->holydays = new ArrayCollection();
        $this->lastChangeRequest = (new \DateTime())->modify('+2 hours');
        $this->password = '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }


    public function setImageFile(File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    #[ORM\PreRemove]
    public function removeImageFile(): void
    {
        if ($this->image) {
            $filePathWeb = sprintf('%s/../../public/%s%s', __DIR__, User::PATH_USER, $this->image);

            if (file_exists($filePathWeb)) {
                unlink($filePathWeb);
            }
        }
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = ucwords($firstName);

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = ucwords($lastName);

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): static
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->setUserPermissions($this);
        }

        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        if ($this->permissions->removeElement($permission)) {
            // set the owning side to null (unless already changed)
            if ($permission->getUserPermissions() === $this) {
                $permission->setUserPermissions(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFirstName();
    }

    /**
     * @return Collection<int, Holyday>
     */
    public function getHolydays(): Collection
    {
        return $this->holydays;
    }

    public function addHolyday(Holyday $holyday): static
    {
        if (!$this->holydays->contains($holyday)) {
            $this->holydays->add($holyday);
            $holyday->addUserHolyday($this);
        }

        return $this;
    }

    public function removeHolyday(Holyday $holyday): static
    {
        if ($this->holydays->removeElement($holyday)) {
            $holyday->removeUserHolyday($this);
        }

        return $this;
    }

    public function getLastChangeRequest(): ?\DateTimeInterface
    {
        return $this->lastChangeRequest;
    }
    public function setLastChangeRequest(?\DateTimeInterface $lastChangeRequest): self
    {
        $this->lastChangeRequest = $lastChangeRequest;

        return $this;
    }
}
