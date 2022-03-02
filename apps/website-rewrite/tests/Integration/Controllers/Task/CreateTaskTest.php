<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\Task;

class CreateTaskTest extends TaskTest
{
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
}
