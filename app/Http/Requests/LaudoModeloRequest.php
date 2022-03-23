<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LaudoModeloRequest extends FormRequest
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
            case 'saveTiposLaudos':
                return [
                    // 'nome_modelo' => 'required',
                    // 'data_html' => 'required',
                    // 'capitulos.*.nome_capitulo' => 'required',
                ];
            case 'updateTiposLaudos':
                return [
                    // 'nome_modelo' => 'required',
                    // 'data_html' => 'required',
                    // 'capitulos.*.nome_capitulo' => 'required',
                ];
            default:
                return [];
        }
    }

    public function messages(){
        return [
            'nome_modelo.required' => 'Campo nome do modelo é obrigatório',
            'descricao_modelo.required' => 'Campo descrição é obrigatório',
            'data_html.required' => 'A capa deve conter algum conteúdo',
            'capitulos.*.nome_subcapitulo.required' => 'Nome do capítulo obrigatório',
            'capitulos.*.subcapitulos.*.nome_subcapitulo.required' => 'Subcapítulo obrigatório',
            'capitulos.*.subcapitulos.*.texto_padrao.required' => 'Texto padrão é obrigatório',
            'capitulos.*.subcapitulos.*.n3.*.nome_sub_subcapitulo.required' => 'Subcapítulo nível 3 é obrigatório',
            'capitulos.*.subcapitulos.*.n3.*.texto_padrao.required' => 'Texto padrão é obrigatório',
        ];
    }
}


