<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "supplier_seq", initialValue: 1, allocationSize: 1)]
    #[ORM\Column(name: "supplier_id", type: "integer")]
    #[Groups(['show_transaction'])]
    private ?int $supplierId = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_transaction'])]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_transaction'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'supplierId', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getSupplierId(): ?int
    {
        return $this->supplierId;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $transaction->setSupplierId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction) && $transaction->getSupplierId() === $this) {
            // set the owning side to null (unless already changed)
            $transaction->setSupplierId(null);
        }

        return $this;
    }
}
