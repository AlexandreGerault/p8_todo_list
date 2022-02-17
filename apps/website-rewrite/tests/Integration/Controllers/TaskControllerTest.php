<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

use App\Repository\TaskRepository;
use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskControllerTest extends WebTestCase
{
    private UrlGeneratorInterface $generator;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->generator = $this->getContainer()->get('router');
    }

    public function test_it_displays_all_tasks_on_list_page_to_a_user(): void
    {
        $this->actingAsUser($this->client);

        $taskListUrl = $this->generator->generate('task_list');
        $crawler = $this->client->request('GET', $taskListUrl);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Titre tâche 1', $crawler->html());
        $this->assertStringContainsString('Titre tâche 2', $crawler->html());
    }

    public function test_guest_cannot_see_tasks_list(): void
    {
        $taskListUrl = $this->generator->generate('task_list');
        $crawler = $this->client->request('GET', $taskListUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function test_a_guest_cannot_see_creation_form(): void
    {
        $taskCreateUrl = $this->generator->generate('task_create');
        $crawler = $this->client->request('GET', $taskCreateUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function test_a_user_can_create_a_task(): void
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
            ]
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('task_list');
        $this->assertStringContainsString('La tâche a été bien été ajoutée.', $crawler->html());
        $this->assertStringContainsString('Titre de la nouvelle tâche', $crawler->html());
    }

    public function test_a_guest_cannot_see_edit_form(): void
    {
        $taskEditUrl = $this->generator->generate('task_edit', ['id' => 1]);
        $crawler = $this->client->request('GET', $taskEditUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function test_a_user_can_edit_a_task(): void
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
            ]
        ]);

        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('task_list');

        $this->assertStringNotContainsString('Titre tâche 1', $crawler->html());
        $this->assertStringContainsString('La tâche a bien été modifiée.', $crawler->html());
        $this->assertStringContainsString('Titre de la nouvelle tâche', $crawler->html());
    }

    public function test_a_guest_cannot_toggle_a_task(): void
    {
        $taskToggleUrl = $this->generator->generate('task_toggle', ['id' => 1]);
        $crawler = $this->client->request('GET', $taskToggleUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function test_a_user_can_toggle_a_task(): void
    {
        $this->actingAsUser($this->client);

        $taskToggleUrl = $this->generator->generate('task_toggle', ['id' => 1]);
        $crawler = $this->client->request('GET', $taskToggleUrl);

        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('task_list');

        $this->assertCount(1, $crawler->filter('div[data-is-done="1"]'));
    }

    public function test_a_guest_cannot_delete_a_task(): void
    {
        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => 1]);
        $crawler = $this->client->request('GET', $taskDeleteUrl);

        $this->client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function test_a_user_can_delete_a_task(): void
    {
        $this->actingAsUser($this->client);

        $taskDeleteUrl = $this->generator->generate('task_delete', ['id' => 1]);
        $this->client->request('GET', $taskDeleteUrl);

        $crawler = $this->client->followRedirect();
        $this->assertRouteSame('task_list');

        $this->assertStringNotContainsString('Titre tâche 1', $crawler->html());
    }
}
