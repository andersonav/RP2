<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipamentoModeloSubCapitulo extends Model
{
    use HasFactory;
    protected $table = 'equipamento_modelo_subcapitulos';
    protected $fillable = [
        'nome_subcapitulo',
        'equipamento_modelo_capitulo_id',
        'texto_padrao'
    ];

    public $timestamps = false;

    public function subCapsN3(){
        return $this->hasMany(EquipamentoModeloSubCapituloN3::class, 'equipamento_modelo_subcapitulo_id');
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
