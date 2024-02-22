<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\AllertMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class AllertController extends Controller {
    
    public function sendEmail( $user, $time ) {

        $content = [
            "title" => "Felhasználó blokkolva",
            "user" => $user,
            "time" => $time
        ];

        Mail::to( "laravelfejlesztes@gmail.com" )->send( new AllertMail( $content ));

        return false;
    }
}
