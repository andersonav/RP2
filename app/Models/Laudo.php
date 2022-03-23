<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Laudo extends Model
{
    use HasFactory;

    protected $table = "laudos";
    protected $fillable = [
        'cliente_id',
        'laudo_modelo_id',
        'nome_laudo',
        'anexo_url',
        'data_html',
        'cod_storage_laudo',
        'images'
    ];

    //RELATIONS
    public function cliente(){
        return $this->hasOne(Cliente::class, 'id', 'cliente_id');
    }

    public function laudoModelo(){
        return $this->hasOne(LaudoModelo::class, 'id', 'laudo_modelo_id');
    }
    //END RELATIONS
    
    public function getLaudos($request = []){
        $conditions = [];

        if(isset($request['nome_laudo']) && !empty($request['nome_laudo'])){
            $conditions[] = ['nome_laudo','LIKE', "%".$request['nome_laudo']."%"];
        }

        return $this
            ->where($conditions)
            ->whereHas('cliente', function($q) use($conditions, $request){
                if(isset($request['cnpj_cliente']) && !empty($request['cnpj_cliente'])){
                    $q->where('cnpjcpf','LIKE', "%".$request['cnpj_cliente']."%");
                } 
            })
            ->with('laudoModelo')
            ->get();
    }

    public function getLaudoById($id){
        return $this    
            ->with('laudoModelo', 'cliente')
            ->find($id);
    }

    public function saveLaudo($request = []){
        try{            
            $prepareSave = $this->fill([
                'cliente_id' => $request['cliente_id'],
                'laudo_modelo_id' => $request['laudo_modelo_id'],
                'cod_storage_laudo' => $request['cod_storage'],
                'anexo_url' => $request['anexo_url'],
                'data_html' => $request['data_html'],
                'images'    => $request['images']
            ]);

            $prepareSave->save();
            return true;
        }catch(\Exception $error){
            return false;
        }
    }

    public function deleteLaudo($id){
        try{
            DB::table('custom_widget_1')->where('laudo_id', $id)->delete();
            DB::table('custom_widget_2')->where('laudo_id', $id)->delete();
            DB::table('historic_laudo')->where('laudo_id', $id)->delete();
            
            if($this->find($id)->delete()){
                return [
                    'error' => false,
                    'msg' => 'Registro excluído com sucesso!'
                ];
            }else{
                return [
                    'error' => true,
                    'msg' => 'Não foi possível excluir o registro, tente novamente ou abra um chamado'
                ];
            }
        }catch(\Exception $error){
            return [
                'error' => true,
                'error_message' => $error->getMessage()
            ];
        }
    }

}
