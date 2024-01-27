<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model {
    
    use HasFactory;

    protected $fillable = [
        "drink",
        "amount",
        "type_id",
        "quantity_id"
    ];

    public function type() {

        return $this->belongsTo( Type::class );
    }

    public function quantity() {

        return $this->belongsTo( Quantity::class );
    }
}
