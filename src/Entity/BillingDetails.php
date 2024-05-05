<?php

namespace App\Entity;

use App\Repository\BillingDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BillingDetailsRepository::class)]
class BillingDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $agreementId = null;

    #[ORM\Column(length: 50)]
    private ?string $nextBillingDate = null;

    #[ORM\Column(length: 50)]
    private ?string $token = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgreementId(): ?string
    {
        return $this->agreementId;
    }

    public function setAgreementId(string $agreementId): static
    {
        $this->agreementId = $agreementId;

        return $this;
    }

    public function getNextBillingDate(): ?string
    {
        return $this->nextBillingDate;
    }

    public function setNextBillingDate(string $nextBillingDate): static
    {
        $this->nextBillingDate = $nextBillingDate;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
