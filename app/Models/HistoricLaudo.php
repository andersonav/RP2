<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricLaudo extends Model
{
    use HasFactory;

    use HasFactory;

    public $timestamps  = false; 
    
    protected $table    = 'historic_laudo';

    protected $fillable = [
        'id',
        'laudo_id',
        'user_id',
        'updated_at',
    ];
}
