<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\Task;

class ToggleTaskTest extends TaskTest
{
    public function testAGuestCannotToggleATask(): void
    {
        $taskToggleUrl = $this->generator->generate('task_toggle', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskToggleUrl);
        $this->client->followRedirect();

        self::assertRouteSame('login');
    }

    public function testAUserCanToggleATask(): void
    {
        $this->actingAsUser();

        $taskToggleUrl = $this->generator->generate('task_toggle', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskToggleUrl);
        $crawler = $this->client->followRedirect();

        self::assertRouteSame('task_list');
        self::assertCount(1, $crawler->filter('div[data-is-done="1"]'));
    }

    public function testAUserCannotToggleATaskBelongingToAnotherUser(): void
    {
        $this->actingAsUser();

        $taskToggleUrl = $this->generator->generate('task_toggle', ['id' => self::BELONGING_TO_OTHER_USER_TASK_ID]);

        $this->client->request('GET', $taskToggleUrl);

        self::assertResponseStatusCodeSame(403);
    }
}
