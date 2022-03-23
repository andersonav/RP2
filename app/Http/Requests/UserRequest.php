<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

use App\Models\User;

class UserRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {   
        $currentRoute = explode('.', Route::currentRouteName());

        switch(end($currentRoute)){
            case 'store': 
                return [
                    'name' => 'required',
                    'last_name' => 'required',
                    'data_nascimento' => 'required',
                    'email' => 'email|required|unique:users,email',
                    'password' => 'required',
                    'cell' => 'required|unique:users,cell',
                    'active' => 'required',
                    'password_confirmation' => 'required|same:password'
                ];
            
            case 'update':
                return [
                    'name' => 'required',
                    'last_name' => 'required',
                    'data_nascimento' => 'required',
                    'email' => [
                        'required',
                        function($attribute, $value , $fail){
                            $user = new User;
                            $findUser = $user->where('email', $value);

                            if($findUser->exists() && $findUser->first()->id != $this->route('id')){
                                $fail('Este e-mail já existe em nossa base de dados');
                            }
                        },
                        'email'
                    ],
                    'cell' => [
                        'required',
                        function($attribute, $value, $fail){
                            $user = new User;
                            $findUser = $user->where('cell', $value);
                
                            if($findUser->exists() && $findUser->first()->id != $this->route('id')){
                                $fail('Este número/celular já existe em nossa base de dados');
                            }
                        }
                    ],
                    'active' => 'required',
                ];
        }
    }

    public function messages(){
        return [
            'required' => 'Campo obrigatório',
            'email' => 'Informe um e-mail válido',
            'same' => 'Senhas não conferem',
            'unique' => 'Este e-mail já existe em nossa base de dados'
        ];
    }
}
