<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaudoModeloCapitulo extends Model
{
    use HasFactory;
    protected $table = 'laudo_modelo_capitulos';
    protected $fillable = [
        'nome_capitulo',
        'laudo_modelo_id',
        'texto_padrao',
        'position'
    ];

    public $timestamps = false;

    public function laudoModeloSubcapitulos(){
        return  $this->hasMany(LaudoModeloSubCapitulo::class);
    }

    public function deleteCapitulo($id){
        try{
            $capitulo = $this->find($id);

            foreach($capitulo->laudoModeloSubcapitulos as $subCaps) {
                $subCaps->subCapsN3()->delete();
            }

            if($capitulo->laudoModeloSubcapitulos()->delete()){
                if($capitulo->delete()){
                    $laudoModelo = LaudoModelo::find($capitulo->laudo_modelo_id);
                    foreach($laudoModelo->laudoCapitulos as $i => $laudoCapitulo) {
                        $laudoCapitulo->position = $i;
                        $laudoCapitulo->save();
                    }

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
