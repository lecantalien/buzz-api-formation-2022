<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class TruncateExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('trunc', [$this, 'truncate']),
        ];
    }

    public function truncate($input, int $size): string
    {
        // slice input to size
        if (strlen($input) > $size) {
            $input = substr($input, 0, $size);
            $input .= '...';
        }
        return $input;
    }
}