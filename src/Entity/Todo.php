<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TodoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodoRepository::class)]
class Todo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isPeriod;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $periodFrom;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $periodTo;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $periodTime;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isCompleted;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'todos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    #[ORM\ManyToOne(targetEntity: Priority::class, inversedBy: 'todos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Priority $priority;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIsPeriod(): ?bool
    {
        return $this->isPeriod;
    }

    public function setIsPeriod(?bool $isPeriod): self
    {
        $this->isPeriod = $isPeriod;

        return $this;
    }

    public function getPeriodFrom(): ?\DateTimeInterface
    {
        return $this->periodFrom;
    }

    public function setPeriodFrom(?\DateTimeInterface $periodFrom): self
    {
        $this->periodFrom = $periodFrom;

        return $this;
    }

    public function getPeriodTo(): ?\DateTimeInterface
    {
        return $this->periodTo;
    }

    public function setPeriodTo(?\DateTimeInterface $periodTo): self
    {
        $this->periodTo = $periodTo;

        return $this;
    }

    public function getPeriodTime(): ?\DateTimeInterface
    {
        return $this->periodTime;
    }

    public function setPeriodTime(?\DateTimeInterface $periodTime): self
    {
        $this->periodTime = $periodTime;

        return $this;
    }

    public function getIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(?bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

}
