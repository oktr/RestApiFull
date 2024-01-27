<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\Drink;
use App\Http\Resources\Drink as DrinkResource;
use App\Http\Requests\DrinkAddChecker;

class DrinkController extends BaseController {
    
    public function getDrinks() {

        $drinks = Drink::with( "type", "quantity" )->get();

        return $this->sendResponse( $drinks, "Adatok betöltve" );
    }

    public function getOneDrink( $id ){
        $drink = Drink::with( "type", "quantity" )->find( $id );

        if( is_null( $drink )){

            return $this->sendError( "Nincs ilyen ital" );
        }

        return $this->sendResponse( new DrinkResource( $drink ), "Ital kiválasztva" );

    }

    public function addDrink( DrinkAddChecker $request ) {

        $request->validated();
        $input = $request->all();

        $drink = new Drink;
        $drink->drink = $input["drink"];
        $drink->amount = $input["amount"];
        $drink->type_id = ( new TypeController )->getTypeId( $input[ "type" ]);;
        $drink->quantity_id = ( new QuantityController )->getQuantityId( $input[ "quantity" ]);
        
        $drink->save();

        return $this->sendResponse( new DrinkResource( $drink ), "Ital kiírva" );;
    }

    public function updateDrink( DrinkAddChecker $request, $id ) {

        $request->validated();
        $input = $request->all();

        $drink = Drink::find( $id );
        $drink->drink = $input[ "drink" ];
        $drink->amount = $input[ "amount" ];
        $drink->type_id = ( new TypeController )->getTypeId( $input[ "type" ]);;
        $drink->quantity_id = ( new QuantityController )->getQuantityId( $input[ "quantity" ]);
        
        $drink->update();
        
        return $this->sendResponse( new DrinkResource( $drink ), "Ital frissítve" );
    }

    public function deleteDrink( $id ) {

        $drink = Drink::find( $id );
        $drink->delete();

        return $this->sendResponse( new DrinkResource( $drink ), "Ital törölve" );
    }
}
