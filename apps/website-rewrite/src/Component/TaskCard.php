<?php

declare(strict_types=1);

namespace App\Component;

use App\Entity\Task;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('task-card')]
class TaskCard
{
    public Task $task;
}
