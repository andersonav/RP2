<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaudoModeloSubCapituloN3 extends Model
{
    use HasFactory;

    protected $table = 'laudo_modelo_subcapitulosn3';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome_sub_subcapitulo',
        'laudo_modelo_subcapitulo_id',
        'texto_padrao',
    ];

    public function deleteSubcapituloN3($id){
        try{
            $this->find($id)->delete();

            return [
                'error' => false,
                'msg' => 'Registro salvo com sucesso!'
            ];
        }catch(\Exception $error){
            return [
                'error' => true,
                'msg' => 'Não foi possível excluir o registro, tente novamente ou abra um chamado'
            ];
        }
    }
}
