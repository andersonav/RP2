<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaudoModeloSubCapitulo extends Model
{
    use HasFactory;
    protected $table = 'laudo_modelo_subcapitulos';
    protected $fillable = [
        'nome_subcapitulo',
        'laudo_modelo_capitulo_id',
        'texto_padrao'
    ];

    public $timestamps = false;

    public function subCapsN3(){
        return $this->hasMany(LaudoModeloSubCapituloN3::class, 'laudo_modelo_subcapitulo_id');
    }

    public function deleteSubCapitulo($id){
        if($this->find($id)->delete()){
            return [
                'error' => false,
                'msg' => 'Registro excluído!'
            ];
        }else{
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o registro, tente novamente'
            ];  
        }
    }
}
