<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserControllerTest extends ControllerTestCase
{
    public function testGuestCannotSeeUserList(): void
    {
        $loginUrl = $this->generator->generate('login', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $userListUrl = $this->generator->generate('user_list');

        $this->client->request('GET', $userListUrl);

        $this->assertResponseRedirects($loginUrl);
    }

    public function testAUserCannotSeeUserList(): void
    {
        $userListUrl = $this->generator->generate('user_list');

        $this->actingAsUser();
        $this->client->request('GET', $userListUrl);

        $this->assertResponseStatusCodeSame(403);
    }

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

    public function testAGuestCannotEditUser(): void
    {
        $loginUrl = $this->generator->generate('login', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $userEditUrl = $this->generator->generate('user_edit', ['id' => 1]);

        $this->client->request('GET', $userEditUrl);

        $this->assertResponseRedirects($loginUrl);
//        $this->client->submitForm('Modifier', [
//            'user' => [
//                'username' => 'Utilisateur modifié',
//                'password' => ['first' => 'password', 'second' => 'password'],
//                'email' => 'nouvel-utilisateur@email.fr'
//            ],
//        ]);
//        $crawler = $this->client->followRedirect();
//        $this->assertRouteSame('user_list');
//        $this->assertStringContainsString("L'utilisateur a bien été modifié.", $crawler->html());
    }

    public function testAUserCannotEditAnotherUser(): void
    {
        $this->actingAsUser();

        $userEditUrl = $this->generator->generate('user_edit', ['id' => 1]);
        $this->client->request('GET', $userEditUrl);

        $this->assertResponseStatusCodeSame(403);


    }

    public function testAnAdminCanSeeUserList(): void
    {
        $userListUrl = $this->generator->generate('user_list');

        $this->actingAsAdmin();

        $crawler = $this->client->request('GET', $userListUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('user@localhost', $crawler->html());
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

    public function testAnAdminCanEditUser(): void
    {
        $loginUrl = $this->generator->generate('login', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $userEditUrl = $this->generator->generate('user_edit', ['id' => 1]);

        $this->actingAsAdmin();
        $this->client->request('GET', $userEditUrl);

        $this->assertResponseIsSuccessful();
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
}
