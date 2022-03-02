<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\User;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ListUserTest extends UserTestCase
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

    public function testAnAdminCanSeeUserList(): void
    {
        $userListUrl = $this->generator->generate('user_list');

        $this->actingAsAdmin();

        $crawler = $this->client->request('GET', $userListUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('user@localhost', $crawler->html());
    }
}
