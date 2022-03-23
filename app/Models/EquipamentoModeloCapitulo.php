<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipamentoModeloCapitulo extends Model
{
    use HasFactory;
    protected $table = 'equipamento_modelo_capitulos';
    protected $fillable = [
        'nome_capitulo',
        'equipamento_modelo_id'
    ];

    public $timestamps = false;

    public function equipamentoModeloSubcapitulos(){
        return  $this->hasMany(EquipamentoModeloSubCapitulo::class);
    }

    public function deleteCapitulo($id){
        try{
            $capitulo = $this->find($id);

            if($capitulo->equipamentoModeloSubcapitulos()->delete()){
                if($capitulo->delete()){
                    return [
                        'error' => false,
                        'msg' => 'Registro excluído com sucesso!'
                    ];
                }
            }else{
                return [
                    'error' => true,
                    'msg' => 'Não foi possível excluír o registro, tente novamente mais tarde'
                ];
            }

            }catch(\Exception $error){
                return [
                    'error' => true,
                    'msg' => 'Não foi possível excluír o registro, tente novamente mais tarde',
                    'error_message' => $error->getMessage()
                ];
            }
    }

}
