<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

use App\Tests\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testItRendersTheHomepageWithPossibleActions(): void
    {
        $client = static::createClient();

        $this->actingAsAdmin($client);
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue');
        $this->assertSelectorTextContains('a[href="/tasks/create"]', 'Créer une nouvelle tâche');
        $this->assertSelectorTextContains('a[href="/tasks"]', 'Consulter la liste des tâches à faire');
    }

    public function testAGuestUserIsRedirected(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseRedirects('http://localhost/login');
    }
}
