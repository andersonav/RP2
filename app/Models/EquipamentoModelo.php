<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

// MODELS
use App\Models\EquipamentoModeloCapitulo;
use App\Models\EquipamentoModeloSubCapitulo;
use App\Models\EquipamentoModeloSubCapituloN3;

class EquipamentoModelo extends Model
{
    use HasFactory;

    protected $table = 'equipamento_modelo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome_modelo',
        'descricao_modelo',
        'user_id',
        'data_html',
        'tipo'
    ];

    //RELATIONS
    public function equipamentoCapitulos(){
        return $this->hasMany(EquipamentoModeloCapitulo::class);
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    //END RELATIONS

    public function getEquipamentoModelo($request = []){
        $conditions = [];

        if(isset($request['nome_modelo']) && !empty($request['nome_modelo'])){
            $conditions[] = ['nome_modelo', 'LIKE', "%".$request['nome_modelo']."%"];
        }

        return $this
            ->where($conditions)
            ->orderBy('id')
            ->with('user')
            ->get();
    }

    public function getEquipamentoModeloById($id){
        return $this
            ->find($id);
    }

    public function getEquipamentoFilterRelations($id, $request = []){
        $arrayCapitulos = [];
        $arraySubCapitulos = [];
        $arrayN3 = [];

        if(isset($request['capitulos']) && !empty($request['capitulos'])){
            foreach($request['capitulos'] as $k => $v){
                $arrayCapitulos[] = $v['id_capitulo'];

                if(!empty($v['subcapitulos'])){
                    foreach($v['subcapitulos'] as $key => $value){
                        $arraySubCapitulos[] = $value['id_subcap'];

                        if(!empty($value['n3'])){
                            foreach($value['n3'] as $i => $j){
                                $arrayN3[] = $j['id_n3'];
                            }
                        }
                    }
                }
            }
        }

        $data = $this
            ->with([
                'equipamentoCapitulos' => function($query) use($arrayCapitulos){
                    return $query->whereIn('id', $arrayCapitulos)->get();
                },
                'equipamentoCapitulos.equipamentoModeloSubcapitulos' => function($query) use($arraySubCapitulos){
                    return $query->whereIn('equipamento_modelo_subcapitulos.id', $arraySubCapitulos);
                },
                'equipamentoCapitulos.equipamentoModeloSubcapitulos.subCapsN3' => function($query) use($arrayN3){
                    return $query->whereIn('equipamento_modelo_subcapitulosn3.id', $arrayN3);
                }
            ])
            ->find($id);

        return $data;
    }

    public function getOptionsEquipamentoModelo(){
        return $this
            ->pluck('nome_modelo', 'id')
            ->toArray();
    }

    public function saveEquipamentoModelo($request = [], $user){
        try{
            $response = ['msg' => 'Não foi possível salvar o registro, tente novamente', 'error' => true];

            DB::beginTransaction();
            $fillData = $this->fill([
                'nome_modelo' => $request['nome_modelo'] ?? '',
                'user_id' => $user->id,
                'data_html' => $request['data_html'] ?? '',
                'descricao_modelo' => $request['descricao_modelo'] ?? '',
                'tipo' => $request['tipo'] ?? 'equipment'
            ]);

            $fillData->save();

            $response['error'] = false;
            $response['msg'] = "Registro salvo com sucesso!";

            DB::commit();
            return $response;
        }catch(\Exception $error){
            DB::rollback();
            dd($error);
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o modelo de equipamento, tente novamente mais tarde',
                'error_message' => $error->getMessage()
            ];
        }
    }

    public function updateEquipamentoModelo($request = [], $id, $user){
        try{
            $response = ['msg' => 'Não foi possível alterar o regsitro', 'error' => true];

            DB::beginTransaction();
            $thisEquipamento = $this->find($id);
            $prepareEquipamento = $thisEquipamento->fill([
                'nome_modelo' => $request['nome_modelo'] ?? '',
                'user_id' => $user->id,
                'data_html' => $request['data_html'] ?? '',
                'descricao_modelo' => $request['descricao_modelo'] ?? '',
                'tipo' => $request['tipo'] ?? 'equipment'
            ]);

            $prepareEquipamento->save();

            $response['error'] = false;
            $response['msg'] = "Registro salvo com sucesso!";

            DB::commit();
            return $response;
        }catch(\Exception $error){
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Não foi possível alterar o modelo de equipamento, tente novamente mais tarde',
                'error_message' => $error->getMessage()
            ];
        }
    }

    public function deleteTipoEquipamento($id){
        try{
            $response = ['msg' => 'Não foi possível excluir o registro, tente de novo', 'error' => true];

            DB::beginTransaction();
            $tipoEquipamento = $this->find($id);

            if($tipoEquipamento->delete()){
                $response['msg'] = "Registro excluído com sucesso!";
                $response['error'] = false;
            }

            $response['error'] ? DB::rollback() : DB::commit();
            return $response;
        }catch(\Exception $error){
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Ocorreu um erro interno, tente novamente mais tarde ou abra um chamado.',
                'error_message' => $error->getMessage()
            ];
        }
    }
}
