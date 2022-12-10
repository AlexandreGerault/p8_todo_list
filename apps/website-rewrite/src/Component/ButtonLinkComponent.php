<?php

declare(strict_types=1);

namespace App\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('button-link')]
class ButtonLinkComponent
{
    public string $href;

    public string $text;

    public string $color = 'blue';
}
