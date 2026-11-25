<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'clients')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Ismni kiriting')]
    #[Assert\Length(max: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Emailni kiriting')]
    #[Assert\Email(message: "Email noto'g'ri")]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Assert\NotNull(message: 'Ishlash joyini tanlang')]
    private ?Company $company = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastActivity = null;

    public function __construct()
    {
        $this->lastActivity = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getLastActivity(): ?\DateTimeImmutable
    {
        return $this->lastActivity;
    }

    public function setLastActivity(\DateTimeImmutable $lastActivity): static
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company ? [
                'id' => $this->company->getId(),
                'name' => $this->company->getName(),
            ] : null,
            'lastActivity' => $this->lastActivity?->format(DATE_ATOM),
        ];
    }
}
