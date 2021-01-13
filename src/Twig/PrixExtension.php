<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PrixExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('euros', [$this, 'formatEuro']),
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function formatEuro($prix)
    {
        return number_format($prix, 2, ',', ' ').' €';
    }

    public function formatPrice($prix, $format)
    {
        if ($format === 'us') {
            return '$'.number_format($prix, 2, '.', ',');
        }elseif ($format === 'fr') {
            return number_format($prix, 2, ',', ' ').' €';
        }else{
            return $prix;
        }
    }
}
