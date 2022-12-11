<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Controllers\User;

use App\DataFixtures\UserFixture;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EditUserTest extends UserTestCase
{
    public function testAGuestCannotEditUser(): void
    {
        $loginUrl = $this->generator->generate('login', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $userEditUrl = $this->generator->generate('user_edit', ['id' => UserFixture::ACTING_USER]);

        $this->client->request('GET', $userEditUrl);

        self::assertResponseRedirects($loginUrl);
    }

    public function testAUserCannotEditAnotherUser(): void
    {
        $this->actingAsUser();

        $userEditUrl = $this->generator->generate('user_edit', ['id' => UserFixture::ACTING_USER]);
        $this->client->request('GET', $userEditUrl);

        self::assertResponseStatusCodeSame(403);
    }

    public function testAnAdminCanEditUser(): void
    {
        $userEditUrl = $this->generator->generate('user_edit', ['id' => UserFixture::ACTING_USER]);

        $this->actingAsAdmin();
        $this->client->request('GET', $userEditUrl);

        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Modifier', [
            'user' => [
                'username' => 'Utilisateur modifié',
                'password' => ['first' => 'password', 'second' => 'password'],
                'email' => 'nouvel-utilisateur@email.fr',
                'role' => 'user'
            ],
        ]);
        $crawler = $this->client->followRedirect();

        self::assertRouteSame('user_list');
        self::assertStringContainsString("L'utilisateur a bien été modifié.", $crawler->html());
        self::assertEquals(
            ['ROLE_USER'],
            $this->userRepository->findOneBy(['username' => 'Utilisateur modifié'])->getRoles()
        );
    }

    public function testAnAdminCanEditAdmin(): void
    {
        $userEditUrl = $this->generator->generate('user_edit', ['id' => UserFixture::ADMIN_USER]);

        $this->actingAsAdmin();
        $this->client->request('GET', $userEditUrl);

        self::assertResponseIsSuccessful();
        $this->client->submitForm('Modifier', [
            'user' => [
                'username' => 'Administrateur modifié',
                'password' => ['first' => 'password', 'second' => 'password'],
                'email' => 'nouvel-administrateur@email.fr',
                'role' => 'admin',
            ],
        ]);
        $crawler = $this->client->followRedirect();

        self::assertRouteSame('user_list');
        self::assertStringContainsString("L'utilisateur a bien été modifié.", $crawler->html());
        self::assertContains(
            'ROLE_ADMIN',
            $this->userRepository->findOneBy(['username' => 'Administrateur modifié'])->getRoles()
        );
    }

    public function testAnAdminCanElevateUserToAdmin(): void
    {
        $userEditUrl = $this->generator->generate('user_edit', ['id' => UserFixture::NON_ACTING_USER]);

        $this->actingAsAdmin();

        $this->client->request('GET', $userEditUrl);

        self::assertResponseIsSuccessful();

        $this->client->submitForm('Modifier', [
            'user' => [
                'username' => 'Utilisateur promu administrateur',
                'password' => ['first' => 'password', 'second' => 'password'],
                'email' => 'utilisateur-promu-administrateur@email.fr',
                'role' => 'admin',
            ],
        ]);

        $crawler = $this->client->followRedirect();

        self::assertRouteSame('user_list');
        self::assertStringContainsString("L'utilisateur a bien été modifié.", $crawler->html());
        self::assertContains(
            'ROLE_ADMIN',
            $this->userRepository->findOneBy(['username' => 'Utilisateur promu administrateur'])->getRoles()
        );
    }

    public function testAnAdminCanLowerAdminToUser(): void
    {
        $userEditUrl = $this->generator->generate('user_edit', ['id' => UserFixture::ADMIN_TO_BE_EDITED_USER]);

        $this->actingAsAdmin();

        $this->client->request('GET', $userEditUrl);
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Modifier', [
            'user' => [
                'username' => 'Administrateur déchu utilisateur',
                'password' => ['first' => 'password', 'second' => 'password'],
                'email' => 'administrateur-dechu-utilisateur@email.fr',
                'role' => 'user',
            ],
        ]);
        $crawler = $this->client->followRedirect();

        self::assertRouteSame('user_list');
        self::assertStringContainsString("L'utilisateur a bien été modifié.", $crawler->html());
        self::assertNotContains(
            'ROLE_ADMIN',
            $this->userRepository->findOneBy(['username' => 'Administrateur déchu utilisateur'])->getRoles()
        );
    }
}
