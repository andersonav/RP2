<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\EquipamentoModelo;
use App\Models\EquipamentoModeloCapitulo;
use App\Models\EquipamentoModeloSubCapitulo;
use App\Models\EquipamentoModeloSubCapituloN3;

use App\Http\Requests\EquipamentoModeloRequest;
use Illuminate\Support\Facades\Auth;

class EquipamentoModeloController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $equipamentoModelo = new EquipamentoModelo;

        $data = $equipamentoModelo->getEquipamentoModelo($request->all());
        return view('tipos-equipamentos.index', ['dataTiposEquipamentos' => $data]);
    }

    public function create()
    {
        $arrayFilesURL = $this->getImagesTipoEquipamento();

        return view('tipos-equipamentos.create', [ 'dataFilesTMP' => $arrayFilesURL->all() ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $equipamentoModelo = new EquipamentoModelo;

        $data = $equipamentoModelo->saveEquipamentoModelo($request->all(), $user);
        return response()->json($data);
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tiposLaudo = new EquipamentoModelo;

        $data = $tiposLaudo->getEquipamentoModeloById($id);
        $arrayFilesURL = $this->getImagesTipoEquipamento();

        return view('tipos-equipamentos.edit', [
            'dataFilesTMP' => $arrayFilesURL->all() ,
            'equipamentoModelo' => $data
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $tiposLaudo = new EquipamentoModelo;

        $data = $tiposLaudo->updateEquipamentoModelo($request->all(), $id, $user);
        return response()->json($data);
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tiposLaudo = new EquipamentoModelo;

        $data = $tiposLaudo->deleteTipoEquipamento($id);
        return response()->json($data);
    }

    //METHODS IMAGES STORAGE
    /**
     * @return \Illuminate\Http\Response
     */
    public function getImagesTipoEquipamento()
    {
        $directory = '/tmp_images_tipos_equipamento/'.Auth::user()->id;
        $files = Storage::disk('public')->files($directory);

        $arrayFilesURL = collect($files)->map(function($item, $key)
        {
            return Storage::url($item);
        });

        return $arrayFilesURL;
    }

    /**
     * @return View
     */
    public function renderViewUploadImage()
    {
        return view('tipos-equipamentos.upload-image');
    }

    /**
     * @return JSON
     */
    public function getPicturesEquipamento($codLaudo){
        $directory = '/tmp_images_tipos_equipamento/'.$codLaudo;
        $files = Storage::disk('public')->files($directory);

        $arrayFilesURL = collect($files)->map(function($item, $key){
            return Storage::url($item);
        });

        return view('tipos-equipamentos.grid-images', ['arrayImages' => $arrayFilesURL]);
    }

    public function uploadImage(Request $request)
    {
        try {
            $codStorage = empty($request->cod_storage) ? md5(uniqid("")) : $request->cod_storage;

            foreach ($request->file('file') as $k => $v)
            {
                $v->store('/public/tmp_images_tipos_equipamento/'.$codStorage);
            }
            $response = [
                'error' => false,
                'msg' => 'Imagens adicionadas',
                'cod_storage' => $codStorage

            ];
        } catch (\Exception $error) {
            $response = [
                'error' => true,
                'msg' => 'Ocorreu um erro ao adicionar as imagens, tente novamente'
            ];
        }

        return response()->json($response);
    }

    /**
     * @return JSON
     */
    public function removeFileFolder($file)
    {
        try {
            $response = ['error' => true, 'msg' => 'Não foi possível remover a imagem, tente mais tarde'];

            if (isset($file)) {
                $filePublicPath = str_replace(['-storage-', '-'],'/', $file);
            }

            if (Storage::disk('public')->exists($filePublicPath)) {
                Storage::disk('public')->delete($filePublicPath);

                $response['error'] = false;
                $response['msg'] = "Imagem removida";
            }

            return response()->json($response);
        } catch(\Exception $error) {
            return response()->json([
                'error' => true,
                'msg' => 'Não foi possível excluír a imagem, tente de novo',
                'error_message' => $error->getMessage()
            ]);
        }
    }
    //END METHODS IMAGES STORAGE
}
