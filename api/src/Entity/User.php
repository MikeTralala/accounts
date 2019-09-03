<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var string The identifier (Internal unique identifier)
     *
     * @Groups({"user:read"})
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var string The full name
     *
     * @Groups({"user:read", "user:write"})
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     */
    private $name;

    /**
     * @var string The email-address (Public unique identifier)
     *
     * @Groups({"user:read", "user:write"})
     *
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Groups({"user:read", "user:write"})
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $phoneNumber;

    /**
     * @Groups({"user:read"})
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     *
     * @Groups({"user:read", "user:write"})
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups({"user:read"})
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @Groups({"user:read"})
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $activatedAt;

    /**
     * @Groups({"user:read"})
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deactivatedAt;

    /**
     * @Groups({"user:read"})
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $lastSeenAt;

    /**
     * @Groups({"user:read"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $confirmationToken;

    public function __construct()
    {
        $this->id        = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();

        $this->createConfirmationToken();
    }

    public function getId(): string
    {
        return $this->id;
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
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
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

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
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
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function activate(): void
    {
        $this->deactivatedAt     = null;
        $this->activatedAt       = new DateTimeImmutable();
        $this->confirmationToken = null;
    }

    public function deactivate(): void
    {
        $this->activatedAt   = null;
        $this->deactivatedAt = new DateTimeImmutable();
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getActivatedAt(): ?DateTimeInterface
    {
        return $this->activatedAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDeactivatedAt(): ?DateTimeInterface
    {
        return $this->deactivatedAt;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function createConfirmationToken(): void
    {
        $this->confirmationToken = Uuid::uuid4();
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getLastSeenAt(): ?DateTimeInterface
    {
        return $this->lastSeenAt;
    }

    public function setLastSeen(): void
    {
        $this->lastSeenAt = new DateTimeImmutable();
    }
}
