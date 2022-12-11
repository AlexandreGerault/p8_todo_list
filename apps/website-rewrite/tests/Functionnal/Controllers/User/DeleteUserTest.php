<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Controllers\User;

use App\DataFixtures\UserFixture;

class DeleteUserTest extends UserTestCase
{
    public function testAGuestCannotDeleteAUser(): void
    {
        $userDeleteUrl = $this->generator->generate('user_delete', ['id' => UserFixture::NON_ACTING_USER]);

        $this->client->request('GET', $userDeleteUrl);
        $this->client->followRedirect();

        self::assertRouteSame('login');
    }

    public function testAUserCannotDeleteAUser(): void
    {
        $userDeleteUrl = $this->generator->generate('user_delete', ['id' => UserFixture::NON_ACTING_USER]);

        $this->actingAsUser();
        $this->client->request('GET', $userDeleteUrl);
        $crawler = $this->client->followRedirect();

        self::assertForbidden($crawler, "Vous n'avez pas le droit d'accéder à cette page.");
    }

    public function testAnAdminCanDeleteAUser(): void
    {
        $userDeleteUrl = $this->generator->generate('user_delete', ['id' => UserFixture::NON_ACTING_USER]);

        $this->actingAsAdmin();
        $this->client->request('GET', $userDeleteUrl);
        $crawler = $this->client->followRedirect();

        self::assertStringContainsString('L\'utilisateur a bien été supprimé.', $crawler->html());
        self::assertEquals(
            null,
            $this->userRepository->findOneBy(['id' => UserFixture::NON_ACTING_USER])
        );
    }
}
