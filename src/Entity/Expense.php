<?php

namespace App\Entity;

use App\Exception\UnknownExpenseStatusException;
use App\Repository\ExpenseRepository;
use DateTimeInterface;
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

        if (!in_array($status, [self::PENDING, self::DISPUTED, self::DISBURSED])) {
            throw new UnknownExpenseStatusException("Unknown expense status '$status' provided");
        }
    }

    public function getId()
    : ?int {

        return $this->id;
    }

    public function getAmount()
    : float {

        return $this->amount;
    }

    public function setAmount(float $amount)
    : self {

        $this->amount = $amount;

        return $this;
    }

    public function getIncurredOn()
    : DateTimeInterface {

        return $this->incurredOn;
    }

    public function setIncurredOn(DateTimeInterface $incurredOn)
    : self {

        $this->incurredOn = $incurredOn;

        return $this;
    }

    public function getStatus()
    : ?string {

        return $this->status;
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
