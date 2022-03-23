<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomWidget2 extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = "custom_widget_2";
    
    protected $fillable = [
        'id',
        'laudo_id',
        'name',
        'number_properties',
        'properties',
        'apartments'
    ];
}
