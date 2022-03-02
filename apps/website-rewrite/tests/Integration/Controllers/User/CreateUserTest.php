<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\User;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateUserTest extends UserTestCase
{
    public function testAGuestCannotCreateAUser(): void
    {
        $this->actingAsUser();

        $userCreateUrl = $this->generator->generate('user_create');
        $this->client->request('GET', $userCreateUrl);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testAUserCannotCreateAUser(): void
    {
        $loginUrl = $this->generator->generate('login', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $userCreateUrl = $this->generator->generate('user_create');

        $this->client->request('GET', $userCreateUrl);

        $this->assertResponseRedirects($loginUrl);
    }

    public function testAnAdminCanCreateUser(): void
    {
        $userCreateUrl = $this->generator->generate('user_create');

        $this->actingAsAdmin();
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
