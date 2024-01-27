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

    private $loginCounter = 0;
    
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

        $bannedTime = $this->getTooManyLoginAttemptsTime( $request->name );
        $request->validated();

        if(( $bannedTime + 60 ) < time() && 
            Auth::attempt([ "name" => $request->name, "password" => $request->password ])) {

            $this->resetTooManyLoginAttemptsTime( $request->name );
            $authUser = Auth::user();
            $success[ "token" ] = $authUser->createToken( $authUser->name."Token" )->plainTextToken;
            $success[ "name" ] = $authUser->name;

            return $this->sendResponse( $success, "Sikeres bejelentkezés" );

        }else {

            $this->loginCounter++;
            if( $this->loginCounter == 3 ) {

                $this->setTooManyLoginAttemptsTime();
            }
            
            return $this->loginCounter; //$this->sendError( "Sikertelen azonosítás", [ "error" => "Hibás felhasználónév vagy jelszó" ]);
        }
    }

    public function logOut( Request $request ){

        auth("sanctum")->user()->currentAccessToken()->delete();

        return response()->json("Sikeres kijelentkezés");
    }

    private function getTooManyLoginAttemptsTime( $name ) {

        $attemptUser = User::where( "name", $name )->first();

        return $attemptUser->toomanylogintime;
    }

    private function setTooManyLoginAttemptsTime( $name ) {

        $currentTime = DB::table( "users" )
            ->where( "name", $name )
            ->update([ "toomanylogintime" => time() ]);
    }

    private function resetTooManyLoginAttemptsTime( $name ) {

        DB::table( "users" )->where( "name", $name )->update([ "toomanylogintime" => null ]);
    }
}
