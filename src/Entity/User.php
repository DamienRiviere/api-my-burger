<?php

namespace App\Entity;

use App\Domain\Doctrine\UuidEncoder;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"showUser"})
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @var array
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"showUser"})
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"showUser"})
     * @var string
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"showUser"})
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showUser"})
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showUser"})
     * @var string
     */
    private $uuidEncoded;

    /**
     * User constructor.
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(
        string $email,
        string $firstName,
        string $lastName
    ) {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Initialize date when user is created
     * @ORM\PrePersist
     */
    public function initializeCreatedAt(): void
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new DateTime();
        }
    }

    /**
     * Initialize uuid when user is created
     * @ORM\PrePersist
     */
    public function initializeUuid(): void
    {
        if (empty($this->uuid)) {
            $this->uuid = Uuid::uuid4();
        }
    }

    /**
     * Initialize slug when user is created
     * @ORM\PrePersist
     */
    public function initializeSlug(): void
    {
        if (empty($this->slug)) {
            $slug = strtolower($this->firstName . "-" . $this->lastName);
            $this->slug = $slug;
        }
    }

    /**
     * Initializer roles when user is created
     * @ORM\PrePersist
     */
    public function initializerRoles(): void
    {
        if (empty($this->roles)) {
            $this->roles = ['ROLE_USER'];
        }
    }

    /**
     * Initializer uuidEncoded when user is created
     * @ORM\PrePersist
     */
    public function initializeUuidEncoded(): void
    {
        if (empty($this->uuidEncoded)) {
            $this->uuidEncoded = UuidEncoder::encode($this->uuid);
        }
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuidEncoded(): string
    {
        return $this->uuidEncoded;
    }
}
