<?php

declare(strict_types=1);

namespace App\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('button')]
class ButtonComponent
{
    public string $text;

    public string $type = 'button';

    public string $colorClasses = ' text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 ';

    public function mount(string $color = 'blue'): void
    {
        $this->colorClasses = match (Colors::from($color)) {
            Colors::RED => ' text-white bg-red-600 hover:bg-red-700 focus:ring-red-500 ',
            Colors::BLUE => ' text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 ',
            Colors::GREEN => ' text-white bg-green-600 hover:bg-green-700 focus:ring-green-500 ',
        };
    }
}
