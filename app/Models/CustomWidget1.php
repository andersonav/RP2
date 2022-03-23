<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomWidget1 extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = "custom_widget_1";
    
    protected $fillable = [
        'id',
        'laudo_id',
        'name',
        'type',
        'number_unit',
        'pavement'
    ];
}
