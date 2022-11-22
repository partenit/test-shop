<?php

namespace Tests;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->user = User::factory()
            ->create();
    }

    public function __get($key)
    {
        if ($key === 'faker') {
            return $this->faker;
        } else {
            throw new \Exception('Unknown Key Requested');
        }
    }
}
