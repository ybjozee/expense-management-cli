<?php

namespace App\Entity;

use App\Exception\UnknownExpenseStatusException;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExpenseRepository::class)
 */
class Expense {

    const DISBURSED = 'disbursed';
    const PENDING   = 'pending';
    const DISPUTED  = 'disputed';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $incurredOn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $owner;

    public function __construct($amount, $incurredOn, $status, $owner) {

        $this->amount = $amount;
        $this->incurredOn = $incurredOn;
        $this->status = $status;
        $this->owner = $owner;
    }

    public static function verifyExpenseStatus(string $status) {

        if (!in_array($status, [Expense::PENDING, Expense::DISPUTED, Expense::DISBURSED])) {
            throw new UnknownExpenseStatusException("Unknown expense status '$status' provided");
        }
    }

    public function getId()
    : ?int {

        return $this->id;
    }

    public function getAmount()
    : ?string {

        return number_format($this->amount, 2);
    }

    public function setAmount(float $amount)
    : self {

        $this->amount = $amount;

        return $this;
    }

    public function getIncurredOn()
    : string {

        return $this->incurredOn->format('l jS F, Y');
    }

    public function setIncurredOn(\DateTimeInterface $incurredOn)
    : self {

        $this->incurredOn = $incurredOn;

        return $this;
    }

    public function getStatus()
    : ?string {

        return ucfirst($this->status);
    }

    public function setStatus(string $status)
    : self {

        $this->status = $status;

        return $this;
    }

    public function getOwner()
    : ?string {

        return $this->owner;
    }

    public function setOwner(string $owner)
    : self {

        $this->owner = $owner;

        return $this;
    }
}
