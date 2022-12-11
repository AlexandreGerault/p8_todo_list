<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Controllers\Task;

class DeleteTaskTest extends TaskTest
{
    public function testAGuestCannotDeleteATask(): void
    {
        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::BELONGING_TO_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        $this->client->followRedirect();

        self::assertRouteSame('login');
    }

    public function testAUserCanDeleteATaskHeOwns(): void
    {
        $this->actingAsUser();

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::BELONGING_TO_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        $crawler = $this->client->followRedirect();

        self::assertRouteSame('task_list');
        self::assertStringNotContainsString('Titre tâche ' . self::BELONGING_TO_USER_TASK_ID, $crawler->html());
    }

    public function testAUserCannotDeleteATaskHeDoesNotOwn(): void
    {
        $this->actingAsUser();

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::BELONGING_TO_OTHER_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        self::assertResponseStatusCodeSame(403);
//        $crawler = $this->client->followRedirect();
//
//        self::assertRouteSame('task_list');
//        self::assertStringContainsString('Impossible de modifier cette tâche', $crawler->html());
    }

    public function testAnAdminCanDeleteATaskHeOwns(): void
    {
        $this->actingAsAdmin();

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::BELONGING_TO_ADMIN_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        $crawler = $this->client->followRedirect();

        self::assertRouteSame('task_list');
        self::assertStringNotContainsString('Titre tâche ' . self::BELONGING_TO_ADMIN_TASK_ID, $crawler->html());
    }

    public function testAnAdminCannotDeleteATaskOfAnotherUser()
    {
        $this->actingAsAdmin();

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::BELONGING_TO_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        self::assertResponseStatusCodeSame(403);
//        $crawler = $this->client->followRedirect();
//
//        self::assertRouteSame('task_list');
//        self::assertStringContainsString('Impossible de modifier cette tâche', $crawler->html());
    }
}
