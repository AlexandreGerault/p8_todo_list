<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

class SecurityControllerTest extends ControllerTestCase
{
    public function testItRedirectsTheUserOnTheHomepageWhenAlreadyLoggedIn(): void
    {
        $this->actingAsUser();

        $loginUrl = $this->generator->generate('login');
        $this->client->request('GET', $loginUrl);

        $homepageUrl = $this->generator->generate('homepage');
        self::assertResponseRedirects($homepageUrl);
    }

    public function testItShowsTheFormToTheUserAndRedirectsIfLoginSuccessfully(): void
    {
        $url = $this->generator->generate('login');
        $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();

        $this->client->submitForm('Se connecter', [
            '_username' => 'User',
            '_password' => 'user',
        ]);

        $this->client->followRedirect();
        self::assertRouteSame('homepage');
    }

    public function testItShowsTheFormToTheUserAndDisplayWrongCredentialsError(): void
    {
        $url = $this->generator->generate('login');
        $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();

        $this->client->submitForm('Se connecter', [
            '_username' => 'User',
            '_password' => 'wrong_password',
        ]);

        $crawler = $this->client->followRedirect();
        $alert = $crawler->filter('[role="alert"]')->innerText();
        self::assertRouteSame('login');
        self::assertEquals('Identifiants invalides.', $alert);
    }

    public function testItShowsTheFormToTheUserAndDisplayInvalidCredentialsError(): void
    {
        $url = $this->generator->generate('login');
        $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();

        $this->client->submitForm('Se connecter', [
            '_username' => 'wrong-user',
            '_password' => 'user',
        ]);

        $crawler = $this->client->followRedirect();
        $alert = $crawler->filter('[role="alert"]')->innerText();
        self::assertRouteSame('login');
        self::assertEquals('Identifiants invalides.', $alert);
    }
}
