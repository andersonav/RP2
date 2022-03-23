<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

use App\Models\LaudoModelo;
use App\Models\LaudoModeloCapitulo;
use App\Models\LaudoModeloSubCapitulo;
use App\Models\LaudoModeloSubCapituloN3;

use App\Http\Requests\LaudoModeloRequest;

class LaudoModeloController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $laudoModelo = new LaudoModelo;

        $data = $laudoModelo->getLaudoModelo($request->all());
        return view('tipos-laudos.index', ['dataTiposLaudos' => $data]);
    }

    public function create()
    {
        $arrayFilesURL = $this->getImagesTipoLaudo(null);
        return view('tipos-laudos.create');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LaudoModeloRequest $request)
    {
        $user = Auth::user();
        $laudoModelo = new LaudoModelo;

        $data = $laudoModelo->saveLaudoModelo($request->all(), $user);

        // dd($data);
        return response()->json($data);
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tiposLaudo = new LaudoModelo;

        $data = $tiposLaudo->getLaudoModeloById($id);
        $arrayFilesURL = $this->getImagesTipoLaudo($data->cod_storage_tipo_laudo);

        return view('tipos-laudos.edit', [
            'dataFilesTMP' => $arrayFilesURL->all() ,
            'laudoModelo' => $data
        ]);
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editModal($id)
    {
        $tiposLaudo = new LaudoModelo;

        $data = $tiposLaudo->getLaudoModeloById($id);

        return view('tipos-laudos.modal-edit', [
            'laudoModelo' => $data
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LaudoModeloRequest $request, $id)
    {
        $user = Auth::user();
        $tiposLaudo = new LaudoModelo;

        $data = $tiposLaudo->updateLaudoModelo($request->all(), $id, $user);
        return response()->json($data);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateModal(LaudoModeloRequest $request, $id)
    {
        $user = Auth::user();
        $tiposLaudo = new LaudoModelo;

        $data = $tiposLaudo->updateLaudoModeloCapitulos($request->all(), $id, $user);
        return response()->json($data);
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tiposLaudo = new LaudoModelo;

        $data = $tiposLaudo->deleteTipoLaudo($id);
        return response()->json($data);
    }

    public function destroyCapitulo($id){
        $laudoModeloCapitulo = new LaudoModeloCapitulo;

        $data = $laudoModeloCapitulo->deleteCapitulo($id);

        return response()->json($data);
    }

    public function destroySubCapitulo($id){
        $laudoModeloSubCapitulo = new LaudoModeloSubCapitulo;

        $data = $laudoModeloSubCapitulo->deleteSubCapitulo($id);
        return response()->json($data);
    }

    public function destroySubCapituloN3($id){
        $n3 = new LaudoModeloSubCapituloN3;

        $data = $n3->deleteSubcapituloN3($id);
        return response()->json($data);
    }

    //METHODS IMAGES STORAGE
     /**
     * @return \Illuminate\Http\Response
     */
    public function getImagesTipoLaudo($code){
        $directory = '/tmp_images_tipos_laudo/'.$code;
        $files = Storage::disk('public')->files($directory);

        $arrayFilesURL = collect($files)->map(function($item, $key){
            return Storage::url($item);
        });

        return $arrayFilesURL;
    }

    /**
     * @return View
     */
    public function renderViewUploadImage($code){
        $arrayFilesURL = $this->getImagesTipoLaudo($code);
        //dd($arrayFilesURL);
        return view('tipos-laudos.upload-image', [ 'dataFilesTMP' => $arrayFilesURL->all() ]);
    }
    public function renderViewImage($code){
        $arrayFilesURL = $this->getImagesTipoLaudo($code);

        return view('tipos-laudos.grid-images', [ 'arrayImages' => $arrayFilesURL->all() ]);
    }

    /**
     * @return JSON
     */
    public function uploadImage($id,$code,Request $request){
        try{
            $codStorage = $code == "0" ? md5(uniqid("")) : $code;

            foreach($request->file('file') as $k => $v){
                $v->store('/public/tmp_images_tipos_laudo/'.$codStorage);
            }
            $cod = $codStorage;
            if($id != 0){
                LaudoModelo::where('id', $id)->update([
                    'cod_storage_tipo_laudo' => $codStorage,
                ]);
            }

            $response = [
                'error' => false,
                'msg' => 'Imagens adicionadas',
                'cod_storage' => $codStorage,
            ];
        }catch(\Exception $error){
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
    public function removeFileFolder($file){
        try{
            $response = ['error' => true, 'msg' => 'Não foi possível remover a imagem, tente mais tarde'];

            if(isset($file)){
                $filePublicPath = str_replace(['-storage-', '-'],'/', $file);
            }

            if(Storage::disk('public')->exists($filePublicPath)){
                Storage::disk('public')->delete($filePublicPath);

                $response['error'] = false;
                $response['msg'] = "Imagem removida";
            }

            return response()->json($response);
        }catch(\Exception $error){
            return response()->json([
                'error' => true,
                'msg' => 'Não foi possível excluir a imagem, tente de novo',
                'error_message' => $error->getMessage()
            ]);
        }
    }
    //END METHODS IMAGES STORAGE
}
