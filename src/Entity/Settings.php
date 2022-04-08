<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $notification_day;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $notification_time;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotificationDay(): ?int
    {
        return $this->notification_day;
    }

    public function setNotificationDay(?int $notification_day): self
    {
        $this->notification_day = $notification_day;

        return $this;
    }

    public function getNotificationTime(): ?\DateTimeInterface
    {
        return $this->notification_time;
    }

    public function setNotificationTime(?\DateTimeInterface $notification_time): self
    {
        $this->notification_time = $notification_time;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
