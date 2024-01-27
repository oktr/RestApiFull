<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quantity extends Model {
    
    use HasFactory;

    protected $fillable = [
        "quantity"
    ];

    public function drink() {

        return $this-hasMany( Drink::class );
    }
}
