<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Controllers\User;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ListUserTest extends UserTestCase
{
    public function testGuestCannotSeeUserList(): void
    {
        $loginUrl = $this->generator->generate('login', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $userListUrl = $this->generator->generate('user_list');

        $this->client->request('GET', $userListUrl);

        self::assertResponseRedirects($loginUrl);
    }

    public function testAUserCannotSeeUserList(): void
    {
        $userListUrl = $this->generator->generate('user_list');

        $this->actingAsUser();
        $this->client->request('GET', $userListUrl);
        $crawler = $this->client->followRedirect();

        self::assertForbidden($crawler, "Vous n'avez pas le droit d'accéder à cette page.");
    }

    public function testAnAdminCanSeeUserList(): void
    {
        $userListUrl = $this->generator->generate('user_list');

        $this->actingAsAdmin();

        $crawler = $this->client->request('GET', $userListUrl);

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('user@localhost', $crawler->html());
    }
}
