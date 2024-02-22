<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UserRegisterChecker;
use App\Http\Requests\UserLoginChecker;
use Carbon\Carbon;
class AuthController extends BaseController {

    public function register( UserRegisterChecker $request ) {

        $request->validated();

        $input = $request->all();
        $input[ "password" ] = bcrypt( $input[ "password" ]);
        $user = User::create( $input );
        $success[ "name" ] = $user->name;

        return $this->sendResponse( $success, "Sikeres regisztrácio" );
    }

    public function login( UserLoginChecker $request ) {

        $bannedTime = null;
        $request->validated();

        if(( Auth::attempt([ "name" => $request->name, "password" => $request->password ]))) {

            $bannedTime = ( new BanningTimeController )->getUserBannedTime( $request->name );

            if( $bannedTime > Carbon::now( "Europe/Budapest" ) ) {

                return $this->sendError( "Túl sok próbálkozás", [ 
                    "nextlogin" => $bannedTime
                 ], 429 );
            }

            ( new BanningTimeController )->resetBannedData( $request->name );
            $authUser = Auth::user();
            $success[ "token" ] = $authUser->createToken( $authUser->name."Token" )->plainTextToken;
            $success[ "name" ] = $authUser->name;

            return $this->sendResponse( $success, "Sikeres bejelentkezés" );

        }else {

            $loginCounter = ( new BanningTimeController )->getLoginAttempts( $request->name );
            if( $loginCounter < 3 ) {

                ( new BanningTimeController )->setLoginAttempts( $request->name );
                return $this->sendError( "Sikertelen azonosítás", [ "error" => "Hibás felhasználónév vagy jelszó" ]);
                    
            }else if( $loginCounter == 3 &&  is_null( $bannedTime )) {

                $bannedTime = ( new BanningTimeController )->setUserBannedTime( $request->name );
                $allert = ( new AllertController )->sendEmail( $request->name, $bannedTime );
                return $this->sendError( "Sikertelen azonosítás", [ "error" => "Túl sok próbálkozás" ]);
            }
        }
    }

    public function logOut( Request $request ){

        auth("sanctum")->user()->currentAccessToken()->delete();

        return response()->json("Sikeres kijelentkezés");
    }

    public function isValidUser( UserLoginChecker $request ) {
        
        $count = User::where( "name", $request->name )->count();
        if( $count != 0 ) {

            $data = $this->login( $request );
            return $data;

        }else {

            return $this->sendError( "Azonosítási hiba", [ "Nincs ilyen felhasználó" ] );
        }
    }
}
