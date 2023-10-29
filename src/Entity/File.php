<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "file_id", type: "integer")]
    #[Groups(['show_transaction', 'show_file'])]
    private ?int $fileId = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_transaction', 'show_file'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_transaction', 'show_file'])]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_transaction', 'show_file'])]
    private ?string $hash = null;

    #[ORM\OneToMany(mappedBy: 'fileId', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['show_transaction', 'show_file'])]
    private ?\DateTimeInterface $importDate = null;

    #[ORM\Column(length: 7)]
    #[Groups(['show_transaction', 'show_file'])]
    private ?string $month = null;

    #[ORM\Column]
    #[Groups(['show_transaction', 'show_file'])]
    private ?bool $income = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getFileId(): ?int
    {
        return $this->fileId;
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

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
            $transaction->setFileId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction) && $transaction->getFileId() === $this) {
            // set the owning side to null (unless already changed)
            $transaction->setFileId(null);
        }

        return $this;
    }

    public function getImportDate(): ?\DateTimeInterface
    {
        return $this->importDate;
    }

    public function setImportDate(\DateTimeInterface $importDate): self
    {
        $this->importDate = $importDate;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function isIncome(): ?bool
    {
        return $this->income;
    }

    public function setIncome(bool $income): self
    {
        $this->income = $income;

        return $this;
    }
}
