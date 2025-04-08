<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CourierCostCalculationHistoryModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

}
