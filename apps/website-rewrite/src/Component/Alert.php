<?php

declare(strict_types=1);

namespace App\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('alert')]
class Alert
{
    public string $text;

    public string $colorClasses = ' text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 ';

    public function mount(string $color = 'blue'): void
    {
        $this->colorClasses = match (Colors::from($color)) {
            Colors::RED => ' bg-red-200 text-red-900 ',
            Colors::BLUE => ' bg-blue-200 text-blue-900 ',
            Colors::GREEN => ' bg-green-200 text-green-900 ',
            Colors::ORANGE => ' bg-orange-200 text-orange-900 ',
        };
    }
}
