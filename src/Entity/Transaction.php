<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "transaction_seq", initialValue: 1, allocationSize: 1)]
    #[ORM\Column(name: 'transaction_id', type: 'integer')]
    #[Groups(['show_transaction'])]
    private ?int $transactionId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['show_transaction'])]
    private ?\DateTimeInterface $dateOp = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['show_transaction'])]
    private ?\DateTimeInterface $dateVal = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_transaction'])]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[Groups(['show_transaction'])]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "category_id")]
    private ?Category $categoryId = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[Groups(['show_transaction'])]
    #[ORM\JoinColumn(name: "supplier_id", referencedColumnName: "supplier_id")]
    private ?Supplier $supplierId = null;

    #[ORM\Column]
    #[Groups(['show_transaction'])]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: "account_id", referencedColumnName: "account_id", nullable: false)]
    #[Groups(['show_transaction'])]
    private ?Account $accountId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_transaction'])]
    private ?string $comment = null;

    #[ORM\Column]
    #[Groups(['show_transaction'])]
    private ?bool $pointed = null;

    #[ORM\Column]
    #[Groups(['show_transaction'])]
    private ?bool $need = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: "file_id", referencedColumnName: "file_id", nullable: false)]
    #[Groups(['show_transaction'])]
    private ?File $fileId = null;

    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }

    public function getDateOp(): ?\DateTimeInterface
    {
        return $this->dateOp;
    }

    public function setDateOp(\DateTimeInterface $dateOp): self
    {
        $this->dateOp = $dateOp;

        return $this;
    }

    public function getDateVal(): ?\DateTimeInterface
    {
        return $this->dateVal;
    }

    public function setDateVal(\DateTimeInterface $dateVal): self
    {
        $this->dateVal = $dateVal;

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

    public function getCategoryId(): ?Category
    {
        return $this->categoryId;
    }

    public function setCategoryId(?Category $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getSupplierId(): ?Supplier
    {
        return $this->supplierId;
    }

    public function setSupplierId(?Supplier $supplierId): self
    {
        $this->supplierId = $supplierId;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAccountId(): ?Account
    {
        return $this->accountId;
    }

    public function setAccountId(?Account $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function isPointed(): ?bool
    {
        return $this->pointed;
    }

    public function setPointed(bool $pointed): self
    {
        $this->pointed = $pointed;

        return $this;
    }

    public function isNeed(): ?bool
    {
        return $this->need;
    }

    public function setNeed(bool $need): self
    {
        $this->need = $need;

        return $this;
    }

    public function getFileId(): ?File
    {
        return $this->fileId;
    }

    public function setFileId(?File $fileId): self
    {
        $this->fileId = $fileId;

        return $this;
    }
}
