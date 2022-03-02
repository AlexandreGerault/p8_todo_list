<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

class TaskControllerTest extends ControllerTestCase
{
    public const ORPHAN_TASK_ID = 1;
    public const ACTING_USER_TASK_ID = 2;
    public const BELONGING_TO_OTHER_USER_TASK_ID = 3;

    public function testItDisplaysAllTasksOnListPageToAUser(): void
    {
        $this->actingAsUser();

        $taskListUrl = $this->generator->generate('task_list');

        $crawler = $this->client->request('GET', $taskListUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Titre tâche 1', $crawler->html());
        $this->assertStringContainsString('Titre tâche 2', $crawler->html());
    }

    public function testGuestCannotSeeTasksList(): void
    {
        $taskListUrl = $this->generator->generate('task_list');

        $this->client->request('GET', $taskListUrl);
        $this->client->followRedirect();

        $this->assertRouteSame('login');
    }

    public function testAGuestCannotSeeCreationForm(): void
    {
        $taskCreateUrl = $this->generator->generate('task_create');

        $this->client->request('GET', $taskCreateUrl);
        $this->client->followRedirect();

        $this->assertRouteSame('login');
    }

    public function testAUserCanCreateATask(): void
    {
        $this->actingAsUser();

        $taskCreateUrl = $this->generator->generate('task_create');
        $crawler = $this->client->request('GET', $taskCreateUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Ajouter', $crawler->html());

        $this->client->submitForm('Ajouter', [
            'task' => [
                'title' => 'Titre de la nouvelle tâche',
                'content' => 'Contenu de la nouvelle tâche',
            ],
        ]);
        $crawler = $this->client->followRedirect();

        $this->assertRouteSame('task_list');
        $this->assertStringContainsString('La tâche a été bien été ajoutée.', $crawler->html());
        $this->assertStringContainsString('Titre de la nouvelle tâche', $crawler->html());
    }

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

    public function testAGuestCannotDeleteATask(): void
    {
        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        $this->client->followRedirect();

        $this->assertRouteSame('login');
    }

    public function testAUserCanDeleteATask(): void
    {
        $this->actingAsUser();

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => self::ACTING_USER_TASK_ID]);

        $this->client->request('GET', $taskDeleteUrl);
        $crawler = $this->client->followRedirect();

        $this->assertRouteSame('task_list');
        $this->assertStringNotContainsString('Titre tâche ' . self::ACTING_USER_TASK_ID, $crawler->html());
    }

    public function testAUserCannotEditTheTaskOfAnotherUser(): void
    {
        $this->actingAsUser();

        $taskEditUrl = $this->generator->generate('task_edit', ['id' => self::BELONGING_TO_OTHER_USER_TASK_ID]);

        $this->client->request('GET', $taskEditUrl);

        $this->assertResponseStatusCodeSame(403);
    }
}
