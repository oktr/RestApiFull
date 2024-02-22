<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class BanningTimeController extends Controller {
    
    public function getUserBannedTime( $name ) {

        $user = User::where( "name", $name )->first();

        return $user->banned_time;
    }

    public function getLoginAttempts( $name ) {

        $user = User::where( "name", $name )->first();
        $loginAttempts = $user->login_attempts;

        return $loginAttempts;
    }

    public function setLoginAttempts( $name ) {
        
        User::where( "name", $name )->increment( "login_attempts" );
    }

    public function setUserBannedTime( $name ) {

        $user = User::where( "name", $name )->first();
        $banned_time = Carbon::now( "Europe/Budapest" )->addSeconds( 60 );
        $user->banned_time = $banned_time;
        $user->save();

        return $banned_time;
    }

    public function resetBannedData( $name ) {

        $user = User::where( "name", $name )->first();
        $user->login_attempts = 0;
        $user->banned_time = null;

        $user->save();
    }
}
