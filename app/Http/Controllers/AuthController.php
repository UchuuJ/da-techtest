<?php

namespace App\Http\Controllers;

use App\Helpers\CourierCostCalculateHelper;
use App\Models\CourierCostCalculationHistoryModel;
use App\Models\DistanceBetweenLocationsModel;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends BaseController
{
    public function login(Request $request){
        /**
         * Method used to fetch the Auth_token when a user has successfully logged in
         * You then pass this token to api/courier-cost as an Authorized Bearer token in order to use the calculater.
         */

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($credentials)) {
            $User = Auth::user();
            $token = $User->createToken('auth_token');

            return response()->json(['token' => $token->plainTextToken]);
        }

    }
}
