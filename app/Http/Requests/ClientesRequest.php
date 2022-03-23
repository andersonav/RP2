<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


use App\Models\Cliente;
use App\Http\Controllers\Controller;

class ClientesRequest extends FormRequest
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
                    'cnpjcpf' => [
                        function($attribute, $value, $fail){
                            $helper = new Controller;

                            if($value && strlen($value) <= 14){
                                if(!$helper->validaCPF($value)){
                                    $fail('CPF Invalido');
                                }
                            }else{
                                if($value && !$helper->validarCNPJ($value)){
                                    $fail('CNPJ Invalido');
                                }
                            }
                        },
                    ]
                ];

            case 'update':
                return [
                    'cnpjcpf' => [
                        function($attribute, $value, $fail){
                            $helper = new Controller;
                            $cliente = new Cliente;

                            $findCliente = $cliente->where('cnpjcpf', $value);
                            if($value && $findCliente->exists() && $findCliente->first()->id != $this->route('id')){
                                $fail('Este registro já existe em nossa base de dados');
                            }

                            if($value && strlen($value) <= 14){
                                if(!$helper->validaCPF($value)){
                                    $fail('CPF Invalido');
                                }
                            }else{
                                if($value && !$helper->validarCNPJ($value)){
                                    $fail('CNPJ Invalido');
                                }
                            }
                        },
                    ],
                    'email' => [
                        function($attribute, $value, $fail){
                            $cliente = new Cliente;

                            $findCliente = $cliente->where('email', $value);
                            if($value && $findCliente->exists() && $findCliente->first()->id != $this->route('id')){
                                $fail('Este registro já existe em nossa base de dados');
                            }
                        }
                    ],
                ];

        }
    }

    public function messages(){
        return [
            'required' => 'Campo obrigatório',
            'email' => 'Informe um e-mail válido',
            'unique' => 'Registro já existente na base de dados'
        ];
    }
}
