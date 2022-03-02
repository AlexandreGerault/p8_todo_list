<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\Task;

use App\Tests\Integration\Controllers\ControllerTestCase;

class ListTasksTest extends ControllerTestCase
{
    public function testGuestCannotSeeTasksList(): void
    {
        $taskListUrl = $this->generator->generate('task_list');

        $this->client->request('GET', $taskListUrl);
        $this->client->followRedirect();

        $this->assertRouteSame('login');
    }

    public function testItDisplaysAllTasksOnListPageToAUser(): void
    {
        $this->actingAsUser();

        $taskListUrl = $this->generator->generate('task_list');

        $crawler = $this->client->request('GET', $taskListUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Titre tâche 1', $crawler->html());
        $this->assertStringContainsString('Titre tâche 2', $crawler->html());
    }
}
