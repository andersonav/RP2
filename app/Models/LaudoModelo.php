<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

//MODELS
use App\Models\LaudoModeloCapitulo;
use App\Models\LaudoModeloSubCapitulo;
use App\Models\LaudoModeloSubCapituloN3;

class LaudoModelo extends Model
{
    use HasFactory;

    protected $table = 'laudo_modelo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome_modelo',
        'descricao_modelo',
        'user_id',
        'data_html',
        'data_html_header',
        'data_html_footer',
    ];

    //RELATIONS
    public function laudoCapitulos()
    {
        return $this->hasMany(LaudoModeloCapitulo::class)->orderBy('position', 'asc');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    //END RELATIONS

    public function getLaudoModelo($request = [])
    {
        $conditions = [];

        if (isset($request['nome_modelo']) && !empty($request['nome_modelo'])) {
            $conditions[] = ['nome_modelo', 'LIKE', "%" . $request['nome_modelo'] . "%"];
        }

        return $this
            ->where($conditions)
            ->with('user')
            ->get();
    }

    public function getLaudoModeloById($id)
    {
        return $this
            ->with('laudoCapitulos.laudoModeloSubcapitulos.subCapsN3')
            ->find($id);
    }

    public function getLaudoFilterRelations($id, $request = [])
    {
        $arrayCapitulos = [];
        $arraySubCapitulos = [];
        $arrayN3 = [];

        if (isset($request['capitulos']) && !empty($request['capitulos'])) {
            foreach ($request['capitulos'] as $k => $v) {
                $arrayCapitulos[] = $v['id_capitulo'];

                if (!empty($v['subcapitulos'])) {
                    foreach ($v['subcapitulos'] as $key => $value) {
                        $arraySubCapitulos[] = $value['id_subcap'];

                        if (!empty($value['n3'])) {
                            foreach ($value['n3'] as $i => $j) {
                                $arrayN3[] = $j['id_n3'];
                            }
                        }
                    }
                }
            }
        }

        $data = $this
            ->with([
                'laudoCapitulos' => function ($query) use ($arrayCapitulos) {
                    return $query->whereIn('id', $arrayCapitulos)->get();
                },
                'laudoCapitulos.laudoModeloSubcapitulos' => function ($query) use ($arraySubCapitulos) {
                    return $query->whereIn('laudo_modelo_subcapitulos.id', $arraySubCapitulos);
                },
                'laudoCapitulos.laudoModeloSubcapitulos.subCapsN3' => function ($query) use ($arrayN3) {
                    return $query->whereIn('laudo_modelo_subcapitulosn3.id', $arrayN3);
                }
            ])
            ->find($id);

        return $data;
    }

    public function getOptionsLaudoModelo()
    {
        return $this
            ->pluck('nome_modelo', 'id')
            ->toArray();
    }

    public function saveLaudoModelo($request = [], $user)
    {
        try {
            $response = ['msg' => 'Não foi possível salvar o registro, tente novamente', 'error' => true];


            DB::beginTransaction();
            $fillData = $this->fill([
                'nome_modelo' => $request['nome_modelo'] ?? '',
                'user_id' => $user->id,
                'data_html' => $request['data_html'] ?? '',
                'data_html_header' => $request['data_html_header'] ?? '',
                'data_html_footer' => $request['data_html_footer'] ?? '',
                'descricao_modelo' => $request['descricao_modelo'] ?? '',
            ]);

            if ($fillData->save()) {
                $laudoModeloCapitulo = new LaudoModeloCapitulo;
                foreach ($request['capitulos'] as $k => $v) {
                    $fillLaudoCapitulo = [
                        'nome_capitulo' => $v['nome_capitulo'] ?? '',
                        'texto_padrao' => $v['texto_padrao'] ?? '',
                        'laudo_modelo_id' => $fillData->id,
                        'position' => $k
                    ];

                    if ($dataCapitulo = $laudoModeloCapitulo->create($fillLaudoCapitulo)) {
                        $laudoModeloSubCapitulo = new LaudoModeloSubCapitulo;
                        if (isset($v['subcapitulos'][1])) {
                            foreach ($v['subcapitulos'] as $key => $value) {
                                // return $key;
                                // Pular primeiro subcapitulo pois ele sempre estará vazio
                                if ($key === 0) {
                                    continue;
                                }

                                // if($value['nome_subcapitulo'] != null){ Está impedindo salvar texto padrão quando o subcapítulo não tem nome
                                $formData = [
                                    'nome_subcapitulo' => $value['nome_subcapitulo'] ?? '',
                                    'texto_padrao' => $value['texto_padrao'] ?? '',
                                    'laudo_modelo_capitulo_id' => $dataCapitulo->id
                                ];

                                if ($dataSubCap = $laudoModeloSubCapitulo->create($formData)) {
                                    if (isset($value['n3']) && !empty($value['n3'])) {
                                        $subCapN3 = new LaudoModeloSubCapituloN3;
                                        foreach ($value['n3'] as $i => $j) {
                                            // if($j['nome_sub_subcapitulo'] != null){ Está impedindo salvar texto padrão quando o subcapítulo não tem nome
                                            $formDataN3 = [
                                                'nome_sub_subcapitulo' => $j['nome_sub_subcapitulo'] ?? '',
                                                'texto_padrao' => $j['texto_padrao'] ?? '',
                                                'laudo_modelo_subcapitulo_id' => $dataSubCap->id
                                            ];

                                            if (!$subCapN3->create($formDataN3)) {
                                                throw new Exception("Não foi possível salvar o registro", 1);
                                            }
                                            // }
                                        }
                                    }
                                    $response['error'] = false;
                                    $response['msg'] = "Registro salvo com sucesso!";
                                }
                                // }

                            }
                        } else {
                            $response['error'] = false;
                            $response['msg'] = "Registro salvo com sucesso!";
                        }
                    }
                }
            }

            DB::commit();
            return $response;
        } catch (\Exception $error) {
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o modelo de laudo, tente novamente mais tarde',
                'error_message' => $error->getMessage()
            ];
        }
    }

    public function updateLaudoModelo($request = [], $id, $user)
    {
        try {
            $response = ['msg' => 'Não foi possível alterar o regsitro', 'error' => true];

            DB::beginTransaction();
            $thisLaudo = $this->find($id);
            $prepareLaudo = $thisLaudo->fill([
                'nome_modelo' => $request['nome_modelo'] ?? '',
                'user_id' => $user->id,
                'data_html' => $request['data_html'] ?? '',
                'data_html_header' => $request['data_html_header'],
                'data_html_footer' => $request['data_html_footer'],
                'descricao_modelo' => $request['descricao_modelo'] ?? '',
            ]);

            if ($prepareLaudo->save()) {
                $laudoModeloCapitulo = new LaudoModeloCapitulo;
                foreach ($request['capitulos'] as $k => $v) {
                    $formData = [
                        'nome_capitulo' => $v['nome_capitulo'] ?? '',
                        'texto_padrao' => $v['texto_padrao'] ?? '',
                        'laudo_modelo_id' => $thisLaudo->id,
                        'position' => $k
                    ];

                    if (isset($v['id'])) {
                        $updatedCapitulo = $laudoModeloCapitulo->find($v['id'])->update($formData);
                    } else {
                        $updatedCapitulo = $laudoModeloCapitulo->create($formData);
                    }

                    //SE É UMA ATUALIZAÇÃO, ATUALIZA EM CASCATA
                    if ($updatedCapitulo) {
                        $laudoModeloSubCapitulo = new LaudoModeloSubCapitulo;

                        foreach ($v['subcapitulos'] as $key => $value) {
                            if ($key === 0 && (isset($value['clone']) && $value['clone'] === '1')) {
                                continue;
                            }

                            $formDataSubCapitulos = [
                                'nome_subcapitulo' => $value['nome_subcapitulo'] ?? '',
                                'texto_padrao' => $value['texto_padrao'] ?? '',
                                'laudo_modelo_capitulo_id' => is_object($updatedCapitulo)
                                    ? $updatedCapitulo->id
                                    : $v['id']
                            ];

                            if (!is_object($updatedCapitulo) && isset($value['id'])) {
                                $updatedSubcapitulo = $laudoModeloSubCapitulo->find($value['id'])->update($formDataSubCapitulos);
                            } else {
                                $updatedSubcapitulo = $laudoModeloSubCapitulo->create($formDataSubCapitulos);
                            }

                            if ($updatedSubcapitulo) {
                                if (isset($value['n3']) && !empty($value['n3'])) {
                                    $n3 = new LaudoModeloSubCapituloN3;
                                    foreach ($value['n3'] as $i => $j) {
                                        if ($j['nome_sub_subcapitulo']) {
                                            $formDataN3 = [
                                                'nome_sub_subcapitulo' => $j['nome_sub_subcapitulo'] ?? '',
                                                'texto_padrao' => $j['texto_padrao'] ?? '',
                                                'laudo_modelo_subcapitulo_id' => is_object($updatedSubcapitulo)
                                                    ? $updatedSubcapitulo->id
                                                    : $value['id']
                                            ];

                                            if (isset($j['id'])) {
                                                $updatedN3 = $n3->find($j['id'])->update($formDataN3);
                                            } else {
                                                if (!$updatedN3 = $n3->create($formDataN3)) {
                                                    throw new Exception("Não foi possível alterar o registro", 1);
                                                }
                                            }
                                        } else {
                                            if (isset($j['id'])) {
                                                $formDataN3 = [
                                                    'nome_sub_subcapitulo' => '',
                                                    'texto_padrao' => '',
                                                    'laudo_modelo_subcapitulo_id' => is_object($updatedSubcapitulo)
                                                        ? $updatedSubcapitulo->id
                                                        : $value['id']
                                                ];
                                                $updatedN3 = $n3->find($j['id'])->update($formDataN3);
                                            }
                                        }
                                    }
                                }

                                $response['error'] = false;
                                $response['msg'] = "Registro alterado com sucesso!";
                                DB::commit();
                            }
                        }
                    }
                }
            }

            return $response;
        } catch (\Exception $error) {
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Não foi possível alterar o modelo de laudo, tente novamente mais tarde',
                'error_message' => $error->getMessage()
            ];
        }
    }


    public function updateLaudoModeloCapitulos($request = [], $id, $user)
    {
        try {
            $response = ['msg' => 'Não foi possível alterar o regsitro', 'error' => true];

            DB::beginTransaction();
            $thisLaudo = $this->find($id);

            $laudoModeloCapitulo = new LaudoModeloCapitulo;
            foreach ($request['capitulos'] as $k => $v) {
                $formData = [
                    'nome_capitulo' => $v['nome_capitulo'] ?? '',
                    'texto_padrao' => $v['texto_padrao'] ?? '',
                    'laudo_modelo_id' => $thisLaudo->id,
                    'position' => $k
                ];

                if (isset($v['id'])) {
                    $updatedCapitulo = $laudoModeloCapitulo->find($v['id'])->update($formData);
                } else {
                    $updatedCapitulo = $laudoModeloCapitulo->create($formData);
                }

                //SE É UMA ATUALIZAÇÃO, ATUALIZA EM CASCATA
                if ($updatedCapitulo) {
                    $laudoModeloSubCapitulo = new LaudoModeloSubCapitulo;
                    foreach ($v['subcapitulos'] as $key => $value) {
                        if ($key === 0 && (isset($value['clone']) && $value['clone'] === '1')) {
                            continue;
                        }
                        $formDataSubCapitulos = [
                            'nome_subcapitulo' => $value['nome_subcapitulo'] ?? '',
                            'texto_padrao' => $value['texto_padrao'] ?? '',
                            'laudo_modelo_capitulo_id' => is_object($updatedCapitulo)
                                ? $updatedCapitulo->id
                                : $v['id']
                        ];

                        if (!is_object($updatedCapitulo) && isset($value['id'])) {
                            $updatedSubcapitulo = $laudoModeloSubCapitulo->find($value['id'])->update($formDataSubCapitulos);
                        } else {
                            $updatedSubcapitulo = $laudoModeloSubCapitulo->create($formDataSubCapitulos);
                        }

                        if ($updatedSubcapitulo) {
                            if (isset($value['n3']) && !empty($value['n3'])) {
                                $n3 = new LaudoModeloSubCapituloN3;
                                foreach ($value['n3'] as $i => $j) {
                                    if ($j['nome_sub_subcapitulo']) {
                                        $formDataN3 = [
                                            'nome_sub_subcapitulo' => $j['nome_sub_subcapitulo'] ?? '',
                                            'texto_padrao' => $j['texto_padrao'] ?? '',
                                            'laudo_modelo_subcapitulo_id' => is_object($updatedSubcapitulo)
                                                ? $updatedSubcapitulo->id
                                                : $value['id']
                                        ];

                                        if (isset($j['id'])) {
                                            $updatedN3 = $n3->find($j['id'])->update($formDataN3);
                                        } else {
                                            if (!$updatedN3 = $n3->create($formDataN3)) {
                                                throw new Exception("Não foi possível alterar o registro", 1);
                                            }
                                        }
                                    } else {
                                        if (isset($j['id'])) {
                                            $formDataN3 = [
                                                'nome_sub_subcapitulo' => '',
                                                'texto_padrao' => '',
                                                'laudo_modelo_subcapitulo_id' => is_object($updatedSubcapitulo)
                                                    ? $updatedSubcapitulo->id
                                                    : $value['id']
                                            ];
                                            $updatedN3 = $n3->find($j['id'])->update($formDataN3);
                                        }
                                    }
                                }
                            }

                            $response['error'] = false;
                            $response['msg'] = "Registro alterado com sucesso!";
                            DB::commit();
                        }
                    }
                }
            }

            return $response;
        } catch (\Exception $error) {
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Não foi possível alterar o modelo de laudo, tente novamente mais tarde',
                'error_message' => $error->getMessage()
            ];
        }
    }

    public function deleteTipoLaudo($id)
    {
        try {
            $response = ['msg' => 'Não foi possível excluir o registro, tente de novo', 'error' => true];

            $laudoCapitulos = new LaudoModeloCapitulo;
            $laudoSubCapitulos = new LaudoModeloSubCapitulo;
            $n3 = new LaudoModeloSubCapituloN3;

            DB::beginTransaction();
            $tipoLaudo = $this->find($id);
            $capitulos = $laudoCapitulos->where('laudo_modelo_id', $id);

            $idCapitulos = $capitulos->pluck('id')->toArray();
            $subCapitulos = $laudoSubCapitulos->whereIn('laudo_modelo_capitulo_id', $idCapitulos);
            $subCapitulosN3 = $n3->whereIn('laudo_modelo_subcapitulo_id', $subCapitulos->pluck('id')->toArray());

            $subCapitulosN3->delete();
            $subCapitulos->delete();
            $capitulos->delete();

            if ($tipoLaudo->delete()) {
                $response['msg'] = "Registro excluído com sucesso!";
                $response['error'] = false;
            }

            $response['error'] ? DB::rollback() : DB::commit();
            return $response;
        } catch (\Exception $error) {
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Ocorreu um erro, Há laudos vinculados a este modelo.',
                'error_message' => $error->getMessage()
            ];
        }
    }
}
