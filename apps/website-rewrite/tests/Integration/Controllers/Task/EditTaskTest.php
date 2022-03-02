<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\Task;

class EditTaskTest extends TaskTest
{
    public function testAGuestCannotSeeEditForm(): void
    {
        $taskEditUrl = $this->generator->generate('task_edit', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskEditUrl);
        $this->client->followRedirect();

        $this->assertRouteSame('login');
    }

    public function testAUserCanEditATask(): void
    {
        $this->actingAsUser();

        $taskEditUrl = $this->generator->generate('task_edit', ['id' => self::ACTING_USER_TASK_ID]);
        $crawler = $this->client->request('GET', $taskEditUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Modifier', $crawler->html());

        $this->client->submitForm('Modifier', [
            'task' => [
                'title' => 'Titre de la nouvelle tâche 2',
                'content' => 'Contenu de la nouvelle tâche 2',
            ],
        ]);
        $crawler = $this->client->followRedirect();

        $this->assertRouteSame('task_list');
        $this->assertStringNotContainsString('Titre tâche 2', $crawler->html());
        $this->assertStringContainsString('La tâche a bien été modifiée.', $crawler->html());
        $this->assertStringContainsString('Titre de la nouvelle tâche', $crawler->html());
    }

    public function testAUserCannotEditTheTaskOfAnotherUser(): void
    {
        $this->actingAsUser();

        $taskEditUrl = $this->generator->generate('task_edit', ['id' => self::BELONGING_TO_OTHER_USER_TASK_ID]);

        $this->client->request('GET', $taskEditUrl);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testAUserCannotEditAnAnonymousTask(): void
    {
        $this->actingAsUser();

        $taskEditUrl = $this->generator->generate('task_edit', ['id' => self::ORPHAN_TASK_ID]);

        $this->client->request('GET', $taskEditUrl);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testAnAdminCanEditAnAnonymousTask(): void
    {
        $this->actingAsAdmin();

        $taskEditUrl = $this->generator->generate('task_edit', ['id' => self::ORPHAN_TASK_ID]);
        $crawler = $this->client->request('GET', $taskEditUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Modifier', $crawler->html());

        $this->client->submitForm('Modifier', [
            'task' => [
                'title' => 'Titre de la nouvelle tâche 1',
                'content' => 'Contenu de la nouvelle tâche 1',
            ],
        ]);
        $crawler = $this->client->followRedirect();

        $this->assertRouteSame('task_list');
        $this->assertStringNotContainsString('Titre tâche 1', $crawler->html());
        $this->assertStringContainsString('La tâche a bien été modifiée.', $crawler->html());
        $this->assertStringContainsString('Titre de la nouvelle tâche', $crawler->html());
    }
}
