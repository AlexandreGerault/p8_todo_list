<?php

declare(strict_types=1);

namespace App\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('error-block')]
class ErrorBlock
{
    public string $text;
}
