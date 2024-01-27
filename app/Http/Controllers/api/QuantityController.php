<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quantity;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\Quantity as QuantityResource;
use App\Http\Requests\QuantityAddChecker;

class QuantityController extends BaseController {
    
    public function getQuantities() {

        $quantities = Quantity::all();

        return $this->sendResponse( $quantities, "Kiszerelések betöltve" );
    }

    public function addQuantity( QuantityAddChecker $request ) {

        $request->validated();

        $input = $request->all();
        $quantity = Quantity::create( $input );

        return $this->sendResponse( new QuantityResource( $quantity ), "Sikeres kiírás" );
    }

    public function updateQuantity( QuantityAddChecker $request, $id ) {

        $request->validated();
        $input = $request->all();

        $quantity = Quantity::find( $id );
        $quantity->quantity = $input[ "quantity" ];
        $quantity->update();
        
        return $this->sendResponse( new QuantityResource( $quantity ), "Kiszerelés frissítve" );
    }

    public function deleteQuantity( $id ) {

        $quantity = Quantity::find( $id );
        if( is_null( $quantity )) {

            return $this->sendError( "Nincs ilyen típus" );

        }else {

            $quantity->delete();
            return $this->sendResponse( new QuantityResource( $quantity ), "Kiszerelés törölve" );

        }
    }

    public function getQuantityId( $quantityName ) {

        $quantity = Quantity::where( "quantity", $quantityName )->first();
        $id = $quantity->id;

        return $id;
    }
}
