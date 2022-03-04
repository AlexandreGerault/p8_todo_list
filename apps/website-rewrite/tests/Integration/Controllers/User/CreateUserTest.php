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
                'email' => 'nouvel-utilisateur@email.fr',
                'role' => 'user'
            ],
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('user_list');
        $this->assertStringContainsString("L'utilisateur a bien été ajouté.", $crawler->html());
        $this->assertEquals(
            ['ROLE_USER'],
            $this->userRepository->findOneBy(['username' => 'Nouvel utilisateur'])->getRoles()
        );
    }

    public function testAnAdminCanCreateAdmin(): void
    {
        $userCreateUrl = $this->generator->generate('user_create');

        $this->actingAsAdmin();
        $crawler = $this->client->request('GET', $userCreateUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Ajouter', $crawler->html());

        $this->client->submitForm('Ajouter', [
            'user' => [
                'username' => 'Nouvel administrateur',
                'password' => ['first' => 'password', 'second' => 'password'],
                'email' => 'nouvel-administrateur@email.fr',
                'role' => 'admin'
            ],
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('user_list');
        $this->assertStringContainsString("L'utilisateur a bien été ajouté.", $crawler->html());
        $this->assertContains(
            'ROLE_ADMIN',
            $this->userRepository->findOneBy(['username' => 'Nouvel administrateur'])->getRoles()
        );
    }
}
