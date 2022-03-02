<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

class TaskControllerTest extends ControllerTestCase
{
    public function testItDisplaysAllTasksOnListPageToAUser(): void
    {
        $this->actingAsUser($this->client);

        $taskListUrl = $this->generator->generate('task_list');
        $crawler = $this->client->request('GET', $taskListUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Titre tâche 1', $crawler->html());
        $this->assertStringContainsString('Titre tâche 2', $crawler->html());
    }

    public function testGuestCannotSeeTasksList(): void
    {
        $taskListUrl = $this->generator->generate('task_list');
        $crawler = $this->client->request('GET', $taskListUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testAGuestCannotSeeCreationForm(): void
    {
        $taskCreateUrl = $this->generator->generate('task_create');
        $crawler = $this->client->request('GET', $taskCreateUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testAUserCanCreateATask(): void
    {
        $this->actingAsUser($this->client);

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
        $taskEditUrl = $this->generator->generate('task_edit', ['id' => 1]);
        $this->client->request('GET', $taskEditUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testAUserCanEditATask(): void
    {
        $this->actingAsUser($this->client);

        $taskEditUrl = $this->generator->generate('task_edit', ['id' => 1]);
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

    public function testAGuestCannotToggleATask(): void
    {
        $taskToggleUrl = $this->generator->generate('task_toggle', ['id' => 1]);
        $crawler = $this->client->request('GET', $taskToggleUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testAUserCanToggleATask(): void
    {
        $this->actingAsUser($this->client);

        $taskToggleUrl = $this->generator->generate('task_toggle', ['id' => 1]);
        $crawler = $this->client->request('GET', $taskToggleUrl);

        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('task_list');

        $this->assertCount(1, $crawler->filter('div[data-is-done="1"]'));
    }

    public function testAGuestCannotDeleteATask(): void
    {
        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => 1]);
        $crawler = $this->client->request('GET', $taskDeleteUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testAUserCanDeleteATask(): void
    {
        $this->actingAsUser($this->client);

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => 1]);
        $this->client->request('GET', $taskDeleteUrl);

        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('task_list');

        $this->assertStringNotContainsString('Titre tâche 1', $crawler->html());
    }

    public function testAUserCannotEditTheTaskOfAnotherUser(): void
    {
        $this->actingAsUser();

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => 3]);
        $this->client->request('GET', $taskDeleteUrl);

        $this->assertResponseStatusCodeSame(403);

    }
}
