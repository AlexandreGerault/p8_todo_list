<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\Task;

use Symfony\Component\DomCrawler\Crawler;

class CreateTaskTest extends TaskTest
{
    public function testAGuestCannotSeeCreationForm(): void
    {
        $taskCreateUrl = $this->generator->generate('task_create');

        $this->client->request('GET', $taskCreateUrl);
        $this->client->followRedirect();

        self::assertRouteSame('login');
    }

    public function testAUserCanCreateATask(): void
    {
        $this->actingAsUser();

        $crawler = $this->createTask();

        self::assertRouteSame('task_list');
        self::assertStringContainsString('La tâche a été bien été ajoutée.', $crawler->html());
        self::assertStringContainsString('Titre de la nouvelle tâche', $crawler->html());
    }

    public function testAnAdminCanCreateATask(): void
    {
        $this->actingAsAdmin();

        $crawler = $this->createTask();

        self::assertRouteSame('task_list');
        self::assertStringContainsString('La tâche a été bien été ajoutée.', $crawler->html());
        self::assertStringContainsString('Titre de la nouvelle tâche', $crawler->html());
    }

    private function createTask(): Crawler
    {
        $taskCreateUrl = $this->generator->generate('task_create');
        $crawler = $this->client->request('GET', $taskCreateUrl);

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Ajouter', $crawler->html());

        $this->client->submitForm('Ajouter', [
            'task' => [
                'title' => 'Titre de la nouvelle tâche',
                'content' => 'Contenu de la nouvelle tâche',
            ],
        ]);

        return $this->client->followRedirect();
    }
}
