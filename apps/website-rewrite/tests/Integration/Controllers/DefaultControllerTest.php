<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

class DefaultControllerTest extends ControllerTestCase
{
    public function testItRendersTheHomepageWithPossibleActions(): void
    {
        $this->actingAsAdmin($this->client);
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Bienvenue');
        self::assertSelectorTextContains('a[href="/tasks/create"]', 'Créer une nouvelle tâche');
        self::assertSelectorTextContains('a[href="/tasks"]', 'Consulter la liste des tâches à faire');
    }

    public function testAGuestUserIsRedirected(): void
    {
        $this->client->request('GET', '/');

        self::assertResponseRedirects('http://localhost/login');
    }
}
