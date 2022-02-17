<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

class DefaultControllerTest extends ControllerTestCase
{
    public function testItRendersTheHomepageWithPossibleActions(): void
    {
        $this->actingAsAdmin($this->client);
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue');
        $this->assertSelectorTextContains('a[href="/tasks/create"]', 'Créer une nouvelle tâche');
        $this->assertSelectorTextContains('a[href="/tasks"]', 'Consulter la liste des tâches à faire');
    }

    public function testAGuestUserIsRedirected(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseRedirects('http://localhost/login');
    }
}
