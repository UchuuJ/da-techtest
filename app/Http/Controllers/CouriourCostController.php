<?php

namespace App\Http\Controllers;

use App\Helpers\CourierCostCalculateHelper;
use App\Models\CourierCostCalculationHistoryModel;
use App\Models\DistanceBetweenLocationsModel;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CouriourCostController extends BaseController
{
    public function courierCostCalculater(Request $Request){
        //print "LOL!";
     //   var_dump($Request->all());
     //   var_dump(json_decode($Request->input('distance_between_locations')));
        if(!Auth::check()){
            //print "holder";
        }
        /**
         * How this is probably going to work
         * Send data to route
         * Store data in a collection (Not sure of the syntax at the moment, goal is to just get it to work )
         * Process data and do calculation
         * Store data in database IF we're using Mysql
         * return Calculated data
         */

        $CourierCostCalculationHistoryModel = new CourierCostCalculationHistoryModel();
        $CourierCostCalculationHistoryModel->cost_per_mile = $Request->input('cost_per_mile');
        $CourierCostCalculationHistoryModel->no_of_drop_off_locations = $Request->input('no_of_drop_off_locations');
        $CourierCostCalculationHistoryModel->extra_person_count = $Request->input('extra_person_count') ?? null;
        $CourierCostCalculationHistoryModel->extra_person_price_override = $Request->input('extra_person_price_override') ?? null;

        $DistanceData = $Request->input('distance_between_locations');
        if(strlen($DistanceData) > 0) {
            $DistanceData = json_decode($DistanceData);
        }
        if(!$this->validateDistanceData($DistanceData, $CourierCostCalculationHistoryModel->no_of_drop_off_locations)){
            throw new \Exception("The number of Drop off Locations don't match the number of Distances",400);
        }

        $DistanceBetweenLocationsModels = [];
        foreach ($DistanceData as $distance){
           $DistanceBetweenLocationsModel = new DistanceBetweenLocationsModel();
           $DistanceBetweenLocationsModel->distance = $distance;
           $DistanceBetweenLocationsModels[] = clone $DistanceBetweenLocationsModel;
        }

        $CourierCostCalculateHelper = new CourierCostCalculateHelper();
        $TotalMilageAndCosts = $CourierCostCalculateHelper->calculateMilageageAndCosts($CourierCostCalculationHistoryModel->cost_per_mile,array_column($DistanceBetweenLocationsModels,'distance'));
        $TotalExtraPersonCost = $CourierCostCalculateHelper->calculateExtraPersonCost($CourierCostCalculationHistoryModel->extra_person_count, $CourierCostCalculationHistoryModel->extra_person_price_override);

        if(empty($TotalMilageAndCosts)){
            throw new \Exception("Was unable to calculate totalCost and totalDistance is the input json missing something?",400);
        }
        var_dump($TotalMilageAndCosts);
        $CourierCostCalculationHistoryModel->total_price = $TotalMilageAndCosts['totalCost'];
        $CourierCostCalculationHistoryModel->total_distance = $TotalMilageAndCosts['totalDistance'];
        if(!empty($TotalExtraPersonCost)){
            $CourierCostCalculationHistoryModel->total_price += $TotalExtraPersonCost;
        }


        //Check if we're using MYSQL and Save to DB


        //return Calculated values
        return response()->json([
            'user_id' => 1,
            'number_of_drop_offs' => $CourierCostCalculationHistoryModel->no_of_drop_off_locations,
            'total_distance' => $CourierCostCalculationHistoryModel->total_distance,
            'cost_per_mile'=>$CourierCostCalculationHistoryModel->cost_per_mile,
            'extra_person_price' =>$CourierCostCalculationHistoryModel->extra_person_price_override ?? null,
            'extra_person_count' =>$CourierCostCalculationHistoryModel->extra_person_count ?? null,
            'total_price' => $CourierCostCalculationHistoryModel->total_price,
            'calculation_created_at' =>(New \DateTime())->format('Y-m-d H:i:s'),
        ],'200');

    }

    private function validateDistanceData($DistanceData, $numberOfDropOffLocations){
        /**
         * IF DistanceData exists AND DistanceData matches the amount of Drop off Locations AND The amount of Drop off Locations is less then 5
         */

        return sizeof($DistanceData)
            && (sizeof($DistanceData) == $numberOfDropOffLocations)
            //TODO: Make magic number a ENV VAR
            && (sizeof($DistanceData) < 5);
    }
}
