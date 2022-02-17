<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers;

use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class ControllerTestCase extends WebTestCase
{
    protected RouterInterface $generator;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->generator = $this->getContainer()->get('router');
    }
}
