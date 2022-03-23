<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ClienteEndereco;
use DB;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tipo_pessoa',
        'cnpjcpf',
        'razao_social',
        'nome_fantasia',
        'inscricao_municipal',
        'inscricao_estadual',
        'email',
        'phone',
        'celular',
        'attachments'
    ];

    //RELATIONS
    public function clienteEndereco(){
        return $this->hasMany(clienteEndereco::class, 'cliente_id', 'id');
    }

    //END RELATION
    public function getClientes($request = []){
        $conditions = [];

        if(isset($request['razao_social']) && !empty($request['razao_social'])){
            $conditions[] = ['razao_social', 'LIKE', "%".$request['razao_social']."%"];
        }

        if(isset($request['cnpjcpf']) && !empty($request['cnpjcpf'])){
            $conditions[] = ['cnpjcpf', 'LIKE', "%".$request['cnpjcpf']."%"];
        }

        if(isset($request['tipo_pessoa']) && !empty($request['tipo_pessoa'])){
            $conditions[] = ['tipo_pessoa', '=', $request['tipo_pessoa']];
        }

        return $this
            ->where($conditions)
            ->get();
    }

    public function getClienteByID($id){
        return $this
            ->with('clienteEndereco')
            ->find($id);
    }

    public function getOptionsClientes(){
        return $this
            ->select(DB::raw("CONCAT(cnpjcpf, ' - ' , nome_fantasia) AS info_empresa"), 'id')
            ->pluck('info_empresa', 'id')
            ->toArray();
    }

    public function saveCliente($request = []){
        try{
            DB::beginTransaction();

            if($this->fill([
                'tipo_pessoa' => $request['tipo_pessoa'] ?? 'F',
                'cnpjcpf' => $request['cnpjcpf'] ?? '',
                'razao_social' => $request['razao_social'] ?? '',
                'nome_fantasia' => $request['nome_fantasia'] ?? '',
                'inscricao_municipal' => $request['inscricao_municipal'] ?? null,
                'inscricao_estadual' => $request['inscricao_estadual'] ?? null,
                'email' => $request['email'] ?? '',
                'phone' => $request['phone'] ?? '',
                'celular' => $request['celular'] ?? ''
            ])->save()){
                $request['cliente_id'] = $this->id;
                foreach($request['endereco'] ?? [] as $i => $endereco) {
                    $this->clienteEndereco()->create([
                        'endereco' => $request['endereco'][$i] ?? '',
                        'bairro' => $request['bairro'][$i] ?? '',
                        'cidade' => $request['cidade'][$i] ?? '',
                        'estado' => $request['estado'][$i] ?? '',
                        'pais' => $request['pais'][$i] ?? '',
                        'cliente_id' => $request['cliente_id']
                    ]);
                }
            }

            DB::commit();
            return [
                'error' => false,
                'msg' => 'Registro salvo com sucesso!'
            ];
        }catch(\Exception $error){
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o registro tente novamente',
                'error_message' => $error->getMessage()
            ];
        }
    }

    public function updateCliente($id, $request = []){
        try{
            DB::beginTransaction();

            $cliente = $this->find($id);
            if($cliente->fill([
                'tipo_pessoa' => $request['tipo_pessoa'] ?? 'F',
                'cnpjcpf' => $request['cnpjcpf'] ?? '',
                'razao_social' => $request['razao_social'] ?? '',
                'nome_fantasia' => $request['nome_fantasia'] ?? '',
                'inscricao_municipal' => $request['inscricao_municipal'] ?? null,
                'inscricao_estadual' => $request['inscricao_estadual'] ?? null,
                'email' => $request['email'] ?? '',
                'phone' => $request['phone'] ?? '',
                'celular' => $request['celular'] ?? ''
            ])->save()){
                ClienteEndereco::where('cliente_id', $id)->delete();
                foreach($request['endereco'] ?? [] as $i => $endereco) {
                    ClienteEndereco::create([
                        'endereco' => $request['endereco'][$i] ?? '',
                        'bairro' => $request['bairro'][$i] ?? '',
                        'cidade' => $request['cidade'][$i] ?? '',
                        'estado' => $request['estado'][$i] ?? '',
                        'pais' => $request['pais'][$i] ?? '',
                        'cliente_id' => $id
                    ]);
                }
            }

            DB::commit();

            return [
                'error' => false,
                'msg' => 'Registro alterado com sucesso!',
            ];
        }catch(\Exception $error){
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Não foi possível alterar o registro, tente novamente mais tarde',
                'error_message' => $error->getMessage()
            ];
        }
    }

    public function deleteCliente($id){
        try{
            $cliente = $this->find($id);

            if($cliente->clienteEndereco()->delete()){
                $cliente->delete();
            }else{
                $cliente->delete();
            }

            DB::commit();

            return [
                'error' => false,
                'msg' => 'Registro excluído com sucesso!'
            ];
        }catch(\Exception $error){
            DB::rollback();

            return [
                'error' => true,
                'msg' => 'Não foi possível excluir o registro. Há laudos vinculados a este cliente.'
            ];
        }
    }
}
