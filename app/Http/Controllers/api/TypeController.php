<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\Type as TypeResource;
use App\Http\Requests\TypeAddChecker;

class TypeController extends BaseController {
    
    public function getTypes() {

        $types = Type::all();

        return $this->sendResponse( $types, "Típusok betöltve" );
    }

    public function addType( TypeAddChecker $request ) {

        $request->validated();

        $input = $request->all();
        $type = Type::create( $input );

        return $this->sendResponse( new TypeResource( $type ), "Sikeres kiírás" );
    }

    public function updateType( TypeAddChecker $request, $id ) {

        $request->validated();
        $input = $request->all();

        $type = Type::find( $id );
        $type->type = $input[ "type" ];
        $type->update();
        
        return $this->sendResponse( new TypeResource( $type ), "Ital frissítve" );
    }

    public function deleteType( $id ) {

        $type = Type::find( $id );
        if( is_null( $type )) {

            return $this->sendError( "Nincs ilyen típus" );

        }else {

            $type->delete();
            return $this->sendResponse( new TypeResource( $type ), "Típus törölve" );

        }
    }

    public function getTypeId( $typeName ) {

        $type = Type::where( "type", $typeName )->first();
        $id = $type->id;

        return $id;
    }
}
