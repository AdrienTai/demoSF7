<?php

namespace App\DTO;

use App\Entity\Service;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO 
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    private string $name = '';

    #[Assert\NotBlank]
    private \DateTimeImmutable $createdAt;

    private string $ipAddress = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 5000)]
    private string $message = '';

    public Service $service;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getService(): Service
    {
        return $this->service;
    }

    public function setService(Service $service): self
    {
        $this->service = $service;

        return $this;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}