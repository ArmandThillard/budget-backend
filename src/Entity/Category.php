<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "category_seq", initialValue: 1, allocationSize: 1)]
    #[ORM\Column(name: "category_id", type: "integer")]
    #[Groups(['show_transaction', 'show_category'])]
    private ?int $categoryId = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_transaction', 'show_category'])]
    private ?string $label = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_transaction', 'show_category'])]
    private ?int $parentCategoryId = null;

    #[ORM\OneToMany(mappedBy: 'categoryId', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
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

    public function getParentCategoryId(): ?int
    {
        return $this->parentCategoryId;
    }

    public function setParentCategoryId(int $parentCategoryId): self
    {
        $this->parentCategoryId = $parentCategoryId;

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
            $transaction->setCategoryId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCategoryId() === $this) {
                $transaction->setCategoryId(null);
            }
        }

        return $this;
    }
}
