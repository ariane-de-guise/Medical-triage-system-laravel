<?php

namespace App\Algorithms;

use App\Data\Afflictions;

class AlgorithmePriorite
{
    public static function calculer(array $symptomes) : array {
        $afflictions = AfflictionMatcher::match($symptomes, Afflictions::afflictions);

        $priorite = -1;

        foreach ($afflictions as $affliction) {
            if ($priorite == -1 || $affliction['priorite'] < $priorite) {
                $priorite = $affliction['priorite'];
            }
        }

        return [
            'priorite' => $priorite,
            'afflictions' => $afflictions,
        ];
    }
}
