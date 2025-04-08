<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Stores the Input json and the output json against the user
 * Done so we can see a history previous calculations and maybe debugging.
 */
class CourierCostCalculationHistoryModel extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'cost_per_mile',
        'no_of_drop_off_locations',
        'extra_person_count',
        'extra_person_price_override',
        'total_distance',
        'total_price',
        'extra_person_price',
        'calculation_created_at'
    ];
    public $timestamps = false;
    protected $table = 'courier_cost_calc_history';


    /**
     * Ideally these attributes should have a getter and setter
     */

    /** @var $user_id int */
    protected $user_id;
    /** @var $cost_per_mile float */
    protected $cost_per_mile;
    /** @var $no_of_drop_off_locations int */
    protected $no_of_drop_off_locations;
    /** @var $extra_person_count int */
    protected $extra_person_count;
    /** @var $extra_person_price_override float */
    protected $extra_person_price_override;
    /** @var $total_distance float */
    protected $total_distance = 0;
    /** @var $total_price float */
    protected $total_price = 0;
    /** @var $extra_person_price float */
    protected $extra_person_price;
    /** @var $calculation_created_at datetime */
    protected $calculation_created_at;
}
