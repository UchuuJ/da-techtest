<?php
namespace App\Helpers;

/**
 * Helper class basically where we store the logic that figures out the milage and total costs
 */
class CourierCostCalculateHelper
{
    public function calculateMilageageAndCosts($costPerMile, $Distances): array {
        if(empty($costPerMile) || empty($Distances) ){
            return false;
        }
        $totalCost = 0;
        $totalDistance = 0;
        foreach ($Distances as $distance) {
            if($distance < 0 || $costPerMile < 0){
                throw new \Exception("'Distance' or 'cost per mile' can't be negative",400);
            }

           $totalCost += ($costPerMile * $distance);
           $totalDistance += $distance;
        }
        return ['totalCost' => $totalCost, 'totalDistance' => $totalDistance];
    }

    public function calculateExtraPersonCost($extraPersonCount = null, $extraPersonPriceOverride = null ): float{
        if(empty($extraPersonCount) || empty($extraPersonPriceOverride) ){
            return false;
        }

        if($extraPersonCount < 0 || $extraPersonPriceOverride < 0 ){
            throw new \Exception("'extra_person_count' or 'extra_person_price_override' can't be negative",400);
        }

       return $extraPersonCount * $extraPersonPriceOverride;
    }
}
