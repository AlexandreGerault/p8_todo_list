<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\User;

use App\Repository\UserRepository;
use App\Tests\Integration\Controllers\ControllerTestCase;

class UserTestCase extends ControllerTestCase
{
    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }
}
