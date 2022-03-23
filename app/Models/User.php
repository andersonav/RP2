<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'cell',
        'password',
        'active',
        'last_name',
        'data_nascimento',
        'url_photo'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUsers($request = []){
        $conditions = [];

        if(isset($request['name']) && !empty($request['name'])){
            $conditions[] = ['name', 'LIKE', "%".$request['name']."%"];
        }

        if(isset($request['email']) && !empty($request['email'])){
            $conditions[] = ['email', 'LIKE', "%".$request['email']."%"];
        }

        if(isset($request['active']) && !empty($request['active'])){
            $conditions[] = ['active', '=', $request['active']];
        }

        $data = $this
            ->where($conditions) 
            ->get();

        return $data;
    }       

    public function saveUser($request = []){
        try{
            if(isset($request['password']) && !empty($request['password'])){
                $request['password'] = bcrypt($request['password']);
            }

            $this->fill($request)->save();

            return [
                'error' => false,
                'msg' => 'Novo usuário cadastrado!'
            ];
        }catch(\Exception $error){
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o usuário, tente de novo.'
            ];
        }
    }

    public function updateUser($id, $request = []){
        try{
            if(isset($request) && !empty($request['password']) && !empty($request['password_confirmation'])){
                if($request['password'] === $request['password_confirmation']){
                    $request['password'] = bcrypt($request['password']);
                }
            }

            $user = $this->find($id);
            $user->fill($request)->save();

            return [
                'error' => false,
                'msg' => 'Registro salvo com sucesso!'
            ];
        }catch(\Exception $error){
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o registro, tente de novo',
                'error_message' => $error->getMessage()
            ];
        }
    }

    public function deleteUser($id){
        if($this->find($id)->delete()){
            return [
                'error' => false,
                'msg' => 'Registro excluído com sucesso!'
            ];
        }else{  
            return [
                'error' => false,
                'msg' => 'Não foi possível excluir o registro, tente novamente'
            ];
        }
    }
}
