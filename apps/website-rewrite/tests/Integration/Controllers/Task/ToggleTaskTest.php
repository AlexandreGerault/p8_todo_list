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

        $this->assertRouteSame('login');
    }

    public function testAUserCanToggleATask(): void
    {
        $this->actingAsUser();

        $taskToggleUrl = $this->generator->generate('task_toggle', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskToggleUrl);
        $crawler = $this->client->followRedirect();

        $this->assertRouteSame('task_list');
        $this->assertCount(1, $crawler->filter('div[data-is-done="1"]'));
    }
}
