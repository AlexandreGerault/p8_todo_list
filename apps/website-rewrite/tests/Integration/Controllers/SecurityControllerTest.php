<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

class SecurityControllerTest extends ControllerTestCase
{
    public function testItRedirectsTheUserOnTheHomepageWhenAlreadyLoggedIn(): void
    {
        $this->actingAsUser($this->client);

        $loginUrl = $this->generator->generate('login');
        $this->client->request('GET', $loginUrl);

        $homepageUrl = $this->generator->generate('homepage');
        $this->assertResponseRedirects($homepageUrl);
    }

    public function testItShowsTheFormToTheUserAndRedirectsIfLoginSuccessfully(): void
    {
        $url = $this->generator->generate('login');
        $this->client->request('GET', $url);

        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Se connecter', [
            '_username' => 'User',
            '_password' => 'user',
        ]);

        $this->client->followRedirect();
        $this->assertRouteSame('homepage');
    }

    public function testItShowsTheFormToTheUserAndDisplayWrongCredentialsError(): void
    {
        $url = $this->generator->generate('login');
        $this->client->request('GET', $url);

        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Se connecter', [
            '_username' => 'User',
            '_password' => 'wrong_password',
        ]);

        $crawler = $this->client->followRedirect();
        $alert = $crawler->filter('[role="alert"]')->innerText();
        $this->assertRouteSame('login');
        $this->assertEquals('Identifiants invalides.', $alert);
    }

    public function testItShowsTheFormToTheUserAndDisplayInvalidCredentialsError(): void
    {
        $url = $this->generator->generate('login');
        $this->client->request('GET', $url);

        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Se connecter', [
            '_username' => 'wrong-user',
            '_password' => 'user',
        ]);

        $crawler = $this->client->followRedirect();
        $alert = $crawler->filter('[role="alert"]')->innerText();
        $this->assertRouteSame('login');
        $this->assertEquals('Identifiants invalides.', $alert);
    }
}
