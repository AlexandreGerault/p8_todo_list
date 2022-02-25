<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

use App\Repository\UserRepository;

class UserControllerTest extends ControllerTestCase
{
    public function testGuestCanSeeUserList(): void
    {
        $userListUrl = $this->generator->generate('user_list');
        $crawler = $this->client->request('GET', $userListUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString("user@localhost", $crawler->text());
    }

    public function testAUserCanSeeUserList(): void
    {
        $this->actingAsUser($this->client);

        $userListUrl = $this->generator->generate('user_list');
        $crawler = $this->client->request('GET', $userListUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString("user@localhost", $crawler->text());
    }

    public function testAGuestCanCreateAUser(): void
    {
        $this->actingAsUser($this->client);

        $this->assertCanCreateUser();
    }

    public function testAUserCanCreateAUser(): void
    {
        $this->assertCanCreateUser();
    }

    public function testAGuestCanEditUser(): void
    {
        $userEditUrl = $this->generator->generate('user_edit', ['id' => 1]);
        $crawler = $this->client->request('GET', $userEditUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Modifier', $crawler->html());

        $this->client->submitForm('Modifier', [
            'user' => [
                'username' => 'Utilisateur modifié',
                'password' => ['first' => 'password', 'second' => 'password'],
                'email' => 'nouvel-utilisateur@email.fr'
            ],
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('user_list');
        $this->assertStringContainsString("L'utilisateur a bien été modifié.", $crawler->html());
    }

    public function testAUserCanEditAnotherUser(): void
    {
        $this->actingAsUser($this->client);

        $userEditUrl = $this->generator->generate('user_edit', ['id' => 1]);
        $crawler = $this->client->request('GET', $userEditUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Modifier', $crawler->html());

        $this->client->submitForm('Modifier', [
            'user' => [
                'username' => 'Utilisateur modifié',
                'password' => ['first' => 'password', 'second' => 'password'],
                'email' => 'nouvel-utilisateur@email.fr'
            ],
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('user_list');
        $this->assertStringContainsString("L'utilisateur a bien été modifié.", $crawler->html());

    }

    private function assertCanCreateUser(): void
    {
        $userCreateUrl = $this->generator->generate('user_create');
        $crawler = $this->client->request('GET', $userCreateUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Ajouter', $crawler->html());

        $this->client->submitForm('Ajouter', [
            'user' => [
                'username' => 'Nouvel utilisateur',
                'password' => ['first' => 'password', 'second' => 'password'],
                'email' => 'nouvel-utilisateur@email.fr'
            ],
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('user_list');
        $this->assertStringContainsString("L'utilisateur a bien été ajouté.", $crawler->html());
    }
}
