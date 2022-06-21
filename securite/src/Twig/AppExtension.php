<?php 
// src/Twig/TelExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('prixFr', [$this, 'formatPrixFr']),
        ];
    }

    public function formatPrixFr(float $prix , ?float $tva = 1.0 ): string
    {
        $taxe = "TTC";
        if($tva === 1.0) $taxe = "HT";
        return number_format($prix * $tva , 2 , ",", " ") . " € $taxe";
    }
}
