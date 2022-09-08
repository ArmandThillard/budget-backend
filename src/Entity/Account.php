<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "account_id", type: "integer")]
    private ?int $accountId = null;

    #[ORM\Column(length: 255)]
    private ?string $num = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'accountId', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setAccountId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getAccountId() === $this) {
                $transaction->setAccountId(null);
            }
        }

        return $this;
    }
}
