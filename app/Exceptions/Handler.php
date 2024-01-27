<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
//use Illuminate\Http\Exceptions\TooManyRequestsException;
use Illuminate\Http\Exceptions\Exception;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // $this->renderable(function (ThrottleRequestsException $e, $request) {
        //     throw new ApiException(ErrorEnum::EXCEPTION_THROTTLE_REQUESTS());
        // });
    }

    // public function render( $request, ThrottleRequestsException $exception ) {
        
        
    //     if( $exception instanceof ThrottleRequestsException ) {
            
    //         return response()->json([
    //             "message" => "Ejnye bejnye",
    //             "code" => 429
    //         ]);
    //     }

    //     return parent::render( $request, $exception );
    // }
}
