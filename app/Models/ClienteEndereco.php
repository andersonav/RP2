<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteEndereco extends Model
{
    use HasFactory;

    protected $table = 'cliente_endereco';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cep',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'numero',
        'pais',
        'cliente_id',
        'attachments'
    ];

    public function cliente(){
        return $this->hasOne(Cliente::class, 'cliente_id', 'id');
    }
}
