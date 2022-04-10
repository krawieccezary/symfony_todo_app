<?php

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
    private ?bool $is_period;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $period_from;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $period_to;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $period_time;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $is_completed;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'todos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    #[ORM\ManyToOne(targetEntity: Priority::class, inversedBy: 'todos')]
    #[ORM\JoinColumn(nullable: false)]
    private $priority;

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
        return $this->is_period;
    }

    public function setIsPeriod(?bool $is_period): self
    {
        $this->is_period = $is_period;

        return $this;
    }

    public function getPeriodFrom(): ?\DateTimeInterface
    {
        return $this->period_from;
    }

    public function setPeriodFrom(?\DateTimeInterface $period_from): self
    {
        $this->period_from = $period_from;

        return $this;
    }

    public function getPeriodTo(): ?\DateTimeInterface
    {
        return $this->period_to;
    }

    public function setPeriodTo(?\DateTimeInterface $period_to): self
    {
        $this->period_to = $period_to;

        return $this;
    }

    public function getPeriodTime(): ?\DateTimeInterface
    {
        return $this->period_time;
    }

    public function setPeriodTime(?\DateTimeInterface $period_time): self
    {
        $this->period_time = $period_time;

        return $this;
    }

    public function getIsCompleted(): ?bool
    {
        return $this->is_completed;
    }

    public function setIsCompleted(?bool $is_completed): self
    {
        $this->is_completed = $is_completed;

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
