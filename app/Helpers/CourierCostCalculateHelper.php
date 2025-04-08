<?php
namespace App\Helpers;

class CourierCostCalculateHelper
{
    public function calculateMilageageAndCosts($costPerMile, $Distances){
        if(empty($costPerMile) || empty($Distances) ){
            return false;
        }
        $totalCost = 0;
        $totalDistance = 0;
        foreach ($Distances as $distance) {
           $totalCost += ($costPerMile * $distance);
           $totalDistance += $distance;
        }
        return ['totalCost' => $totalCost, 'totalDistance' => $totalDistance];
    }

    public function calculateExtraPersonCost($extraPersonCount = null, $extraPersonPriceOverride = null ){
        if(empty($extraPersonCount) || empty($extraPersonPriceOverride) ){
            return false;
        }

       return $extraPersonCount * $extraPersonPriceOverride;
    }
}
