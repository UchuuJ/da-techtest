<?php

namespace App\Http\Controllers;

use App\Helpers\CourierCostCalculateHelper;
use App\Models\CourierCostCalculationHistoryModel;
use App\Models\DistanceBetweenLocationsModel;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CourierCostController extends BaseController
{
    public function courierCostCalculater(Request $Request){
        /**
         * How this method works
         * Send data to route
         * Store data in a collection (Not sure of the syntax at the moment, goal is to just get it to work )
         * Process data and do calculation
         * Store data in database
         * return Calculated data
         */

        /**
         * Controller mainly used to organise and validate the data. While the actual logic is offloaded into a helper class
         * So we can reuse that piece of logic if needed.
         */

        $Request->validate([
            'cost_per_mile' => 'required|numeric',
            'no_of_drop_off_locations' => 'required|numeric',
            'distance_between_locations' => 'required',

        ]);

        $CourierCostCalculationHistoryModel = new CourierCostCalculationHistoryModel();
        $CourierCostCalculationHistoryModel->cost_per_mile = $Request->input('cost_per_mile');
        $CourierCostCalculationHistoryModel->no_of_drop_off_locations = $Request->input('no_of_drop_off_locations');
        $CourierCostCalculationHistoryModel->extra_person_count = is_numeric($Request->input('extra_person_count')) ? $Request->input('extra_person_count') : null;
        $CourierCostCalculationHistoryModel->extra_person_price_override = is_numeric($Request->input('extra_person_price_override')) ? $Request->input('extra_person_price_override') : null;

       $DistanceData = $Request->input('distance_between_locations');
       if (strlen($DistanceData) > 0) {
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

        $CourierCostCalculationHistoryModel->total_price = $TotalMilageAndCosts['totalCost'];
        $CourierCostCalculationHistoryModel->total_distance = $TotalMilageAndCosts['totalDistance'];
        if(!empty($TotalExtraPersonCost)){
            $CourierCostCalculationHistoryModel->total_price += $TotalExtraPersonCost;
            $CourierCostCalculationHistoryModel->extra_person_price = $TotalExtraPersonCost;
        } else {
            $CourierCostCalculationHistoryModel->extra_person_price = config('courierCost.DEFAULT_EXTRA_PERSON_PRICE');
        }

        $CourierCostCalculationHistoryModel->calculation_created_at = (New \DateTime())->format('Y-m-d H:i:s');
        $CourierCostCalculationHistoryModel->user_id = Auth::user()->id;
        $CourierCostCalculationHistoryModel->save();

        foreach ($DistanceBetweenLocationsModels as $DistanceBetweenLocationsModel){
            $DistanceBetweenLocationsModel->courier_cost_calc_history_id = $CourierCostCalculationHistoryModel->id;
            $DistanceBetweenLocationsModel->save();
        }


        //return Calculated values
        return response()->json([
            'user_id' => Auth::user()->id,
            'number_of_drop_offs' => $CourierCostCalculationHistoryModel->no_of_drop_off_locations,
            'total_distance' => $CourierCostCalculationHistoryModel->total_distance,
            'cost_per_mile'=>$CourierCostCalculationHistoryModel->cost_per_mile,
            'extra_person_price' =>$CourierCostCalculationHistoryModel->extra_person_price,
            'extra_person_count' =>$CourierCostCalculationHistoryModel->extra_person_count ?? null,
            'total_price' => $CourierCostCalculationHistoryModel->total_price,
            'calculation_created_at' => $CourierCostCalculationHistoryModel->calculation_created_at,
        ],'200');

    }
    private function validateDistanceData($DistanceData, $numberOfDropOffLocations) : bool {
        /**
         * IF DistanceData exists AND DistanceData matches the amount of Drop off Locations AND The amount of Drop off Locations is less then 5
         */
        if(empty($DistanceData)){
            return false;
        }

        return sizeof($DistanceData)
            && (sizeof($DistanceData) == $numberOfDropOffLocations)
            && (sizeof($DistanceData) < config('courierCost.MAXIMUM_AMOUNT_OF_STOPS'));
    }
}
