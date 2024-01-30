<?php

namespace App\Http\Controllers\api;

//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UserRegisterChecker;
use App\Http\Requests\UserLoginChecker;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController {

    public function register( UserRegisterChecker $request ) {

        $request->validated();

        $input = $request->all();
        $input[ "password" ] = bcrypt( $input[ "password" ]);
        $input[ "toomanylogintime" ] = now();
        $user = User::create( $input );
        $success[ "name" ] = $user->name;

        return $this->sendResponse( $success, "Sikeres regisztrácio" );

    }

    public function login( UserLoginChecker $request ) {

        $bannedTime = $this->getUserBannedTime( $request->name );
        $request->validated();

        if((( $bannedTime + 60 < time() ) && 
             Auth::attempt([ "name" => $request->name, "password" => $request->password ]))) {

            $this->resetBannedData( $request->name );
            // $authUser = Auth::user();
            // $success[ "token" ] = $authUser->createToken( $authUser->name."Token" )->plainTextToken;
            // $success[ "name" ] = $authUser->name;

            return "ok"; //$this->sendResponse( $success, "Sikeres bejelentkezés" );

        }else {

            $loginCounter = $this->getLoginAttempts( $request->name );
            if( $loginCounter < 3 ) {

                $this->setLoginAttempts( $request->name );
                return "Próbálkozások: ".$loginCounter;
                
            }else if( $loginCounter == 3 &&  is_null( $bannedTime )) {

                $this->setUserBannedTime( $request->name );

                return $bannedTime;
            }
            
            return $bannedTime; //$this->sendError( "Sikertelen azonosítás", [ "error" => "Hibás felhasználónév vagy jelszó" ]);
        }
    }

    public function logOut( Request $request ){

        auth("sanctum")->user()->currentAccessToken()->delete();

        return response()->json("Sikeres kijelentkezés");
    }

    private function getLoginAttempts( $name ) {

        $user = User::where( "name", $name )->first();
        $loginAttempts = $user->login_attempts;

        return $loginAttempts;
    }

    private function getUserBannedTime( $name ) {

        $user = User::where( "name", $name )->first();

        return $user->banned_time;
    }

    private function setLoginAttempts( $name ) {
        
        User::where( "name", $name )->increment( "login_attempts" );
    }

    private function setUserBannedTime( $name ) {

        $user = User::where( "name", $name )->first();
        $user->banned_time = time();
        $user->save();
    }

    private function resetBannedData( $name ) {

        $user = User::where( "name", $name )->first();
        $user->login_attempts = 0;
        $user->banned_time = null;

        $user->save();
    }
}
