<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Controllers\Task;

use App\Tests\Functionnal\Controllers\ControllerTestCase;

abstract class TaskTest extends ControllerTestCase
{
    public const ORPHAN_TASK_ID = 1;
    public const BELONGING_TO_ADMIN_TASK_ID = 2;
    public const BELONGING_TO_USER_TASK_ID = 3;
    public const BELONGING_TO_OTHER_USER_TASK_ID = 4;
}
