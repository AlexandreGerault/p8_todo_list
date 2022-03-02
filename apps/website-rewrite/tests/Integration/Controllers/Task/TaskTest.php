<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controllers\Task;

use App\Tests\Integration\Controllers\ControllerTestCase;

abstract class TaskTest extends ControllerTestCase
{
    public const ORPHAN_TASK_ID = 1;
    public const ACTING_USER_TASK_ID = 2;
    public const BELONGING_TO_OTHER_USER_TASK_ID = 3;
}
