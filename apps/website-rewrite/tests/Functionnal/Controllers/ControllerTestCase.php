<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Controllers;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\RouterInterface;

class ControllerTestCase extends WebTestCase
{
    protected RouterInterface $generator;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->generator = self::getContainer()->get('router');
    }

    public function actingAsAdmin(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $admin = $userRepository->findOneByEmail('admin@localhost');
        $this->client->loginUser($admin);
    }

    public function actingAsUser(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('user@localhost');
        $this->client->loginUser($user);
    }

    public static function assertForbidden(Crawler $crawler, string $message): void
    {
        self::assertStringContainsString($message, $crawler->html());
    }
}
