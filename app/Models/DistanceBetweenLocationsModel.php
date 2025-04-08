<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Stores the DistanceBetweenLocations array data. mainly to show I can join tables.
 * as this model is a child of CourierCostCalculationHistoryModel
 * Although a real life application of this is so we can see which user has used millage over time
 */
class DistanceBetweenLocationsModel extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'distance',
        'courier_cost_calc_history_id',
    ];
    public $timestamps = false;
    protected $table = 'distance_between_locations';

    public function getCourierCostCalculationHistory(){
        return $this->belongsTo(CourierCostCalculationHistoryModel::class, 'courier_cost_calc_history_id','id');
    }

    /** @var $distance float */
    protected $distance;

    /** @var $courier_cost_calc_history integer */
    protected $courier_cost_calc_history_id;

}
