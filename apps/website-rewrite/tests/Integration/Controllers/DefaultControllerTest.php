<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

use App\Tests\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function test_it_renders_the_homepage_with_possible_actions(): void
    {
        $client = static::createClient();

        $this->actingAsAdmin($client);
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue');
        $this->assertSelectorTextContains('a[href="/tasks/create"]', 'Créer une nouvelle tâche');
        $this->assertSelectorTextContains('a[href="/tasks"]', 'Consulter la liste des tâches à faire');
    }

    public function test_a_guest_user_is_redirected(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseRedirects('http://localhost/login');
    }
}
