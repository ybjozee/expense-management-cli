<?php

namespace App\DataFixtures;

use App\Entity\Expense;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ExpenseFixture extends Fixture {

    private Generator $faker;

    public function __construct() {

        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager) {

        for ($i = 0; $i < 1000; $i++) {
            $manager->persist($this->getExpense());
        }

        $manager->flush();
    }

    private function getExpense()
    : Expense {

        $possibleStatus = [Expense::DISBURSED, Expense::DISPUTED, Expense::PENDING];

        return new Expense(
            $this->faker->numberBetween(10000, 100000000),
            $this->faker->dateTimeThisYear(),
            $this->faker->randomElement($possibleStatus),
            $this->faker->name()
        );
    }
}
