<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\User;

use App\DataFixtures\UserFixture;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EditUserTest extends UserTestCase
{
    public function testAGuestCannotEditUser(): void
    {
        $loginUrl = $this->generator->generate('login', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $userEditUrl = $this->generator->generate('user_edit', ['id' => UserFixture::ACTING_USER]);

        $this->client->request('GET', $userEditUrl);

        $this->assertResponseRedirects($loginUrl);
    }

    public function testAUserCannotEditAnotherUser(): void
    {
        $this->actingAsUser();

        $userEditUrl = $this->generator->generate('user_edit', ['id' => UserFixture::ACTING_USER]);
        $this->client->request('GET', $userEditUrl);

        $this->assertResponseStatusCodeSame(403);
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
                'email' => 'nouvel-utilisateur@email.fr'
            ],
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('user_list');
        $this->assertStringContainsString("L'utilisateur a bien été modifié.", $crawler->html());
    }
}
