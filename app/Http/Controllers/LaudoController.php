<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use voku\helper\HtmlDomParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

//REQUEST
use App\Http\Requests\LaudoRequest;

//MODELS
use App\Models\Laudo;
use App\Models\Cliente;
use App\Models\CustomWidget1;
use App\Models\CustomWidget2;
use App\Models\EquipamentoModelo;
use App\Models\HistoricLaudo;
use App\Models\LaudoModelo;
use DOMDocument;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;

class LaudoController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $laudo = new Laudo;

        $data = $laudo->getLaudos($request->all());
        return view('laudos.index', ['dataLaudos' => $data]);
    }

    public function create()
    {
        $cliente = new Cliente;
        $laudoModelo = new LaudoModelo;

        $optionsCliente = $cliente->getOptionsClientes();
        $optionsTiposLaudos = $laudoModelo->getOptionsLaudoModelo();

        return view('laudos.create', [
            'optionsCliente' => $optionsCliente,
            'optionsTiposLaudos' => $optionsTiposLaudos
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editor(LaudoRequest $request)
    {
        $cliente = new Cliente;
        $laudoModelo = new LaudoModelo;

        $dataCliente = $cliente->getClienteByID($request->cliente_id);
        $dataModeloLaudo = $laudoModelo->getLaudoFilterRelations($request->laudo_modelo_id, $request->all());

        return view('laudos.editor', [
            'dataCliente' => $dataCliente,
            'dataModeloLaudo' => $dataModeloLaudo
        ]);
    }

    /**
     * @param int $id
     * @return View
     */
    public function renderCapitulosByLaudoModelo($id)
    {
        $laudoModelo = new LaudoModelo;
        $dataModeloLaudo = $laudoModelo->getLaudoModeloById($id);

        return view('laudos.select-capitulos', ['dataModeloLaudo' => $dataModeloLaudo]);
    }

    public function renderModalCreateFiguras()
    {
        return view('laudos.modal_figuras');
    }

    public function uploadPicturesLaudos(Request $request)
    {
        try {
            $codStorage = empty($request->cod_storage) ? md5(uniqid("")) : $request->cod_storage;
            $arrFiles = array();
            $id = $request->laudo_id;
            foreach ($request->file('file') as $k => $v) {
                $path = $v->store('/public/tmp_images_laudo/' . $codStorage);

                array_push($arrFiles, $path);
            }

            Laudo::where('id', $id)->update([
                'cod_storage_laudo' => $codStorage,
            ]);

            $response = [
                'error' => false,
                'msg' => 'Imagens adicionadas',
                'cod_storage' => $codStorage,
                'files' => $arrFiles
            ];
        } catch (\Exception $error) {
            $response = [
                'error' => true,
                'msg' => 'Ocorreu um erro ao adicionar as imagens, tente novamente'
            ];
        }

        return response()->json($response);
    }

    public function getPicturesLaudos($codLaudo)
    {
        $directory = '/tmp_images_laudo/' . $codLaudo;
        $files = Storage::disk('public')->files($directory);

        $arrayFilesURL = collect($files)->map(function ($item, $key) {
            return Storage::url($item);
        });

        return view('laudos.grid-images', ['arrayImages' => $arrayFilesURL]);
    }

    public function removeFileImg($laudoId, $pathFile)
    {
        //dd("Teste");
        try {

            $pathFile = str_replace(['-storage-', '-'], '/', $pathFile);

            if (Storage::disk('public')->exists($pathFile)) {
                $deleteImg = Storage::disk('public')->delete($pathFile);


                $imagesLaudo = DB::table('laudos')->where('id', $laudoId)->pluck('images')->first();

                $imageToDelete = str_replace('/tmp_images_laudo', 'public/tmp_images_laudo', $pathFile);
                $imageToDelete = str_replace('/', '\/', $imageToDelete);

                $replaceImg = preg_replace("/($imageToDelete;|;$imageToDelete|$imageToDelete)/i", '', $imagesLaudo);

                // dd(preg_replace("/($imageToDelete;|$imageToDelete)/i", '', $imagesLaudo));
                // dd($imagesLaudo, $imageToDelete, $replaceImg);

                if ($deleteImg) {
                    Laudo::where('id', $laudoId)->update([
                        'images' => $replaceImg
                    ]);
                }

                $response['error'] = false;
                $response['msg'] = "Imagem removida";
            }

            return response()->json($response);
        } catch (\Exception $error) {
            return response()->json([
                'error' => true,
                'msg' => 'Não foi possível excluír a imagem, tente de novo',
                'error_message' => $error->getMessage()
            ]);
        }
    }

    public function renderModalPersonalizado()
    {
        return view('laudos.modal_personalizado');
    }

    public function renderModalPersonalizadoImoveis()
    {
        return view('laudos.modal_personalizado_imoveis');
    }

    public function generatePDF(Request $request, $figure = null)
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $response = ['error' => true, 'msg' => 'Não foi possível gerar o PDF, contacte o administrador do sistema.'];

        $laudo = new Laudo;
        $laudoModelo = new LaudoModelo;

        $dataLaudoModelo = $laudoModelo->getLaudoModeloById($request->laudo_modelo_id);
        $data['dataLaudoModelo'] = $dataLaudoModelo->data_html;
        $data['dataLaudoModeloHeader'] = $dataLaudoModelo->data_html_header;
        $data['dataLaudoModeloFooter'] = $dataLaudoModelo->data_html_footer;

        $d = HtmlDomParser::str_get_html($request->data_html);

        $images = [];

        foreach ($d->find('small') as $cap) {
            $images[] = $cap->plaintext;
        }

        $data['images'] = $images;
        $data['content'] = $this->FormatHtml($request->data_html);
        $data['matches'] = $this->getHeadings($request->data_html);
        $data['figure'] = $figure;
        $htmlLaudo = view('laudos.pdf', $data)->render();

        $htmlLaudoReplaced = Str::replace('../../storage', asset('storage'), $htmlLaudo);
        $htmlLaudoReplaced = Str::replace('../storage', asset('storage'), $htmlLaudoReplaced);
        $htmlLaudoReplaced = Str::replace('<!-- pagebreak -->', '<div style="page-break-before: always;"></div>', $htmlLaudoReplaced);

        $pdf = $this->renderPDF($request->cliente_id, $htmlLaudoReplaced);
        $htmlLaudo = $request->data_html;
        if (!$pdf['error']) {

            //* Não precisa gerar histórico se for um laudo recentemente criado
            if ($request->laudo_id) {
                HistoricLaudo::create([
                    'laudo_id' => $request->laudo_id,
                    'user_id' => FacadesAuth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $arrayRequest = array_merge(
                $request->all(),
                [
                    'data_html' => $htmlLaudo,
                    'anexo_url' => "/" . $pdf['filePath'],
                    'images' => isset($request["images"]) ? implode(';', $request["images"]) : null
                ]
            );

            if ($laudo->saveLaudo($arrayRequest)) {
                $response['error'] = false;
                $response['msg'] = 'Registro salvo com sucesso!';
                $response['file_anexo'] = "/" . $pdf['filePath'];
            }

            if (isset($request['customWidget1Name'])) {
                foreach ($request['customWidget1Name'] as $indexName => $customWidget1Name) {
                    CustomWidget1::create([
                        'laudo_id' => $laudo->id,
                        'name' => $customWidget1Name,
                        'type' => isset($request['customWidget1Type'][$indexName]) ? $request['customWidget1Type'][$indexName] : null,
                        'number_unit' => isset($request['customWidget1NumberUnit'][$indexName]) ? $request['customWidget1NumberUnit'][$indexName] : null,
                        'pavement' => isset($request['customWidget1NumberPavement' . ($indexName + 1)]) ? json_encode($request['customWidget1NumberPavement' . ($indexName + 1)]) : null,
                    ]);
                }
            }

            if (isset($request['customWidget2Name'])) {
                foreach ($request['customWidget2Name'] as $indexName2 => $customWidget2Name) {
                    $properties = array();
                    $apartments = array();

                    if (isset($request['customWidget2NameProperty'])) {
                        foreach ($request['customWidget2NameProperty'] as $indexNameProperty => $nameProperty) {
                            $property = array(
                                'id' => $indexNameProperty + 1,
                                'name' => $nameProperty
                            );

                            array_push($properties, $property);

                            if (isset($request['customWidget2NameResident' . ($indexNameProperty + 1)])) {
                                foreach ($request['customWidget2NameResident' . ($indexNameProperty + 1)] as $indexNameResident => $nameResident) {
                                    $apartment = array(
                                        'id' => $indexNameResident + 1,
                                        'property_id' => $indexNameProperty + 1,
                                        'name_apartment' => implode("", $request['customWidget2NameApartment' . ($indexNameProperty + 1) . '-' . ($indexNameResident + 1)]),
                                        'name_resident' => $nameResident,
                                    );

                                    array_push($apartments, $apartment);
                                }
                            }
                        }
                    }

                    CustomWidget2::create([
                        'laudo_id' => $laudo->id,
                        'name' => $customWidget2Name,
                        'number_properties' => isset($request['customWidget2PropertyNumber'][$indexName2]) ? $request['customWidget2PropertyNumber'][$indexName2] : null,
                        'properties' => json_encode($properties),
                        'apartments' => json_encode($apartments),
                    ]);
                }
            }
        } else {
            $response['error_message'] = $pdf['error_message'];
        }

        return response()->json($response);
    }

    public function editGeneratePDF(Request $request, $figure = null)
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $response = ['error' => true, 'msg' => 'Não foi possível gerar o PDF, contacte o administrador do sistema.'];

        $laudoModelo = new LaudoModelo;

        $dataLaudoModelo = $laudoModelo->getLaudoModeloById($request->laudo_modelo_id);

        //Amir Edit
        $data['dataLaudoModelo'] = $dataLaudoModelo->data_html;
        $data['dataLaudoModeloHeader'] = $dataLaudoModelo->data_html_header;
        $data['dataLaudoModeloFooter'] = $dataLaudoModelo->data_html_footer;

        $d = HtmlDomParser::str_get_html($request->data_html);

        $images = [];

        foreach ($d->find('small') as $cap) {
            $images[] = $cap->plaintext;
        }

        $data['images'] = $images;
        $data['content'] = $this->FormatHtml($request->data_html);
        $data['matches'] = $this->getHeadings($request->data_html);
        $data['figure'] = $figure;
        $htmlLaudo = view('laudos.pdf', $data)->render();

        $htmlLaudoReplaced = Str::replace('../../storage', asset('storage'), $htmlLaudo);
        $htmlLaudoReplaced = Str::replace('../storage', asset('storage'), $htmlLaudoReplaced);
        $htmlLaudoReplaced = Str::replace('<!-- pagebreak -->', '<div style="page-break-before: always;"></div>', $htmlLaudoReplaced);

        // Area de testes
        // dd($htmlLaudoReplaced);


        $pdf = $this->renderPDF($request->input('cliente_id'), $htmlLaudoReplaced);

        $htmlLaudo = $request->data_html;

        if (!$pdf['error']) {
            // $arrayRequest = array_merge(
            //     $request->all(),
            //     ['data_html' => $htmlLaudo,
            //      'anexo_url' => "/".$pdf['filePath']
            //     ]
            // );

            HistoricLaudo::create([
                'laudo_id' => $request->laudo_id,
                'user_id' => FacadesAuth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $lastImages = DB::table('laudos')->where('id', $request->laudo_id)->pluck('images')->first();

            // if ($lastImages) {
            //     $letterEnd = substr(implode(';', $request["images"]) . ";" . $lastImages, -1);
            // } else {
            //     $letterEnd = null;
            // }

            $responseQuery = Laudo::where('id', $request->laudo_id)->update([
                'data_html' => $htmlLaudo,
                'anexo_url' => "/" . $pdf['filePath'],
                // 'images' => isset($request["images"]) ? ($letterEnd === ';' ? substr(implode(';', $request["images"]) . ";" . $lastImages, 0, -1) : implode(';', $request["images"]) . ";" . $lastImages) : $lastImages
                'images' => isset($request["images"]) ? implode(';', $request["images"]) . ";" . $lastImages : $lastImages
            ]);

            $lastImages2 = DB::table('laudos')->where('id', $request->laudo_id)->pluck('images')->first();


            $dbExp = explode(';', $lastImages2);

            if (empty($dbExp[1])) {
                DB::table('laudos')->where('id', $request->laudo_id)->update([
                    'images' => str_replace(';', '', $lastImages2)
                ]);
            }

            if ($responseQuery) {
                $response['error'] = false;
                $response['msg'] = 'Registro salvo com sucesso!';
                $response['file_anexo'] = "/" . $pdf['filePath'];
            }

            if (isset($request['customWidget1Name'])) {
                foreach ($request['customWidget1Name'] as $indexName => $customWidget1Name) {
                    CustomWidget1::create([
                        'laudo_id' => $request->laudo_id,
                        'name' => $customWidget1Name,
                        'type' => isset($request['customWidget1Type'][$indexName]) ? $request['customWidget1Type'][$indexName] : null,
                        'number_unit' => isset($request['customWidget1NumberUnit'][$indexName]) ? $request['customWidget1NumberUnit'][$indexName] : null,
                        'pavement' => isset($request['customWidget1NumberPavement' . ($indexName + 1)]) ? json_encode($request['customWidget1NumberPavement' . ($indexName + 1)]) : null,
                    ]);
                }
            }

            if (isset($request['customWidget2Name'])) {
                foreach ($request['customWidget2Name'] as $indexName2 => $customWidget2Name) {
                    $properties = array();
                    $apartments = array();

                    if (isset($request['customWidget2NameProperty'])) {
                        foreach ($request['customWidget2NameProperty'] as $indexNameProperty => $nameProperty) {
                            $property = array(
                                'id' => $indexNameProperty + 1,
                                'name' => $nameProperty
                            );

                            array_push($properties, $property);

                            if (isset($request['customWidget2NameResident' . ($indexNameProperty + 1)])) {
                                foreach ($request['customWidget2NameResident' . ($indexNameProperty + 1)] as $indexNameResident => $nameResident) {
                                    $apartment = array(
                                        'id' => $indexNameResident + 1,
                                        'property_id' => $indexNameProperty + 1,
                                        'name_apartment' => implode("", $request['customWidget2NameApartment' . ($indexNameProperty + 1) . '-' . ($indexNameResident + 1)]),
                                        'name_resident' => $nameResident,
                                    );

                                    array_push($apartments, $apartment);
                                }
                            }
                        }
                    }

                    CustomWidget2::create([
                        'laudo_id' => $request->laudo_id,
                        'name' => $customWidget2Name,
                        'number_properties' => isset($request['customWidget2PropertyNumber'][$indexName2]) ? $request['customWidget2PropertyNumber'][$indexName2] : null,
                        'properties' => json_encode($properties),
                        'apartments' => json_encode($apartments),
                    ]);
                }
            }
        } else {
            $response['error_message'] = $pdf['error_message'];
        }

        return response()->json($response);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $laudo = DB::table('laudos')
            ->where('id', $id)
            ->first();

        $client = DB::table('clientes')
            ->where('id', $laudo->cliente_id)
            ->first();

        $laudoModel = DB::table('laudo_modelo')
            ->where('id', $laudo->laudo_modelo_id)
            ->first();

        $customWidget1 = DB::table('custom_widget_1')
            ->where('laudo_id', $laudo->id)
            ->get();

        $customWidget2 = DB::table('custom_widget_2')
            ->where('laudo_id', $laudo->id)
            ->get();

        $historics = DB::table('historic_laudo')
            ->join('users', 'users.id', 'historic_laudo.user_id')
            ->where('laudo_id', $laudo->id)
            ->orderBy('historic_laudo.id')
            ->get([
                'historic_laudo.id',
                'historic_laudo.laudo_id',
                'historic_laudo.user_id',
                'historic_laudo.updated_at',
                'users.name',
                'users.last_name'
            ]);
        //dd($laudo);
        return view('laudos.edit', compact(
            'laudo',
            'client',
            'laudoModel',
            'customWidget1',
            'customWidget2',
            'historics'
        ));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $laudo = new Laudo;

        $data = $laudo->deleteLaudo($id);
        return response()->json($data);
    }

    public function addFigurePixie(Request $request)
    {
        $db = DB::table('laudos')->where('cod_storage_laudo', $request->input('storage_code'))->first();

        $data = $request->input('image_upload');
        list($type, $data) = explode(';', $data);
        list(, $extension) = explode('/', $type);
        list(, $data)      = explode(',', $data);

        $fileName = "public/tmp_images_laudo/" . $request->input('storage_code') . "/" . uniqid() . '.' . $extension;
        $data = base64_decode($data);

        $storage = Storage::put($fileName, $data);

        if ($storage && $db) :
            if ($db->images) {
                $db = DB::table('laudos')->where('cod_storage_laudo', $request->input('storage_code'))->update([
                    'images' => $db->images . ';' . $fileName
                ]);
            } else {
                $db = DB::table('laudos')->where('cod_storage_laudo', $request->input('storage_code'))->update([
                    'images' => $db->images . $fileName
                ]);
            }
        endif;

        return[
            'error' => !$storage,
            'msg' => 'Imagens adicionadas',
            'file' => $fileName
        ];
    }

    public function renderPDF($idCliente, $html)
    {
        try {

            $hashNameFile = md5(uniqid("", true));
            $options = new \Dompdf\Options();
            $options->set('dpi', 100);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $path = "/public/pdf_laudos/$idCliente/";
            $storagePath = Storage::makeDirectory($path);


            if ($storagePath) {
                $putContent = "storage/pdf_laudos/$idCliente/$hashNameFile.pdf";

                $dompdf = new \Dompdf\Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->render();
                $output = $dompdf->output();

                Storage::put("/public/pdf_laudos/$idCliente/$hashNameFile.pdf", $output);

                return [
                    'error' => false,
                    'filePath' => $putContent
                ];
            }
        } catch (\Exception $error) {
            return [
                'error' => true,
                'error_message' => $error->getMessage(),
                'filePath' => ""
            ];
        }
    }

    /**
     * Amir Edit
     * get all the heading and sub headings for toc
     * @param HTML content
     *
     * @return Array
     *
     *   */

    public function getHeadings($content)
    {
        $content = preg_replace('#\s(id|class)="[^"]+"#', '', $content);
        $d = new DOMDocument();
        libxml_use_internal_errors(true);
        $d->loadHtml($content);
        $matches = [];
        foreach ($d->getElementsByTagName('h1') as $node) {
            $key = $node->textContent;
            $matches[$key] = array();
            $ph4 = '';
            while (($node = $node->nextSibling) && $node->nodeName !== 'h1') {
                if ($node->nodeName == 'h4') {
                    $ph4 = $node->textContent;
                    $matches[$key][$node->textContent] = [];
                }
                if ($node->nodeName == 'h6') {
                    if ($ph4 != '') {
                        $h6_array[] = $node->textContent;
                        $matches[$key][$ph4][] = $node->textContent;
                    }
                }
            }
        }
        libxml_clear_errors();

        return $matches;
    }

    public function getTextBetweenTags($s, $h)
    {
        $d = new DOMDocument();
        $d->loadHTML($s);
        $return = array();

        foreach ($d->getElementsByTagName($h) as $item) {
            $return[] = $item->textContent;
        }

        return $return[0];
    }

    /**
     * format HTML in a structure
     *
     * masadique80@gmail.com
     *
     */

    public function FormatHtml($html): string
    {
        $org = '';
        $tag_array = [1, 4, 6];


        foreach ($tag_array as $t) {
            $tag = explode('</h' . $t . '>', $html);

            $org = '';
            foreach ($tag as $key => $f) {

                $te = $f . '</h' . $t . '>';

                // Possível função de linkagem
                //                $te = Str::slug($this->getTextBetweenTags($te, 'h' . $t));

                if ($key !== count($tag)) {
                    $index = $this->parseTag($te, $t);
                    $org .= $te . ' <script type="text/php">
                    $GLOBALS["chapters"]["' . $index . '"] = $pdf->get_page_number();
                </script>';
                } else {
                    $org .= $te . $f;
                }
            }

            $html = $org;
        }

        libxml_clear_errors();

        $img = explode('<small', $html);

        $org = '';
        $l = 1;

        foreach ($img as $key => $i) {
            if ($key + 1 !== count($img)) {
                $org .= $i . '<script type="text/php">
                    $GLOBALS["figures"]["' . $l . '"] = $pdf->get_page_number();
                </script><small';
            } else {
                $org .= $i;
            }
            $l++;
        }
        return $org;
    }


    public function parseTag($temp, $t)
    {
        $d = new DOMDocument();
        libxml_use_internal_errors(true);
        $d->loadHTML($temp);
        $v = '';

        foreach ($d->getElementsByTagName('h' . $t) as $t) {

            foreach ($t->attributes as $name => $val) {
                if ($name == 'data-id') {
                    $v = $val->value;
                    break;
                }
            }
        }
        return $v;
    }

    /*End Amir edit function new added  */

    public function downloadPDF($id)
    {
        try {
            $laudo = new Laudo;
            return response()->download(public_path($laudo->find($id)->anexo_url));
        } catch (\Exception $error) {
            return
                redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao baixar o PDF, tente novamente ou abra um chamado');
        }
    }

    public function getWidget1($idLaudo)
    {
        $widget1 = DB::table('custom_widget_1')
            ->where('laudo_id', $idLaudo)
            ->get();

        foreach ($widget1 as $valueWidget1) {
            $valueWidget1->pavement = json_decode($valueWidget1->pavement);
        }

        return $widget1;
    }

    public function getWidget2($idLaudo)
    {
        $widget2 = DB::table('custom_widget_2')
            ->where('laudo_id', $idLaudo)
            ->get();

        foreach ($widget2 as $valueWidget2) {
            $valueWidget2->properties = json_decode($valueWidget2->properties);
            $valueWidget2->apartments = json_decode($valueWidget2->apartments);
        }

        return $widget2;
    }

    public function getWidget3()
    {
        $widget3 = EquipamentoModelo::where('tipo', request()->input('tipo'))->get();

        return response()->json($widget3);
    }

    public function duplicate($id, Laudo $laudo)
    {
        $duplicateFind = $laudo->where('id', $id)->first(['cliente_id', 'laudo_modelo_id', 'nome_laudo', 'anexo_url', 'data_html', 'cod_storage_laudo']);

        $duplicate = $laudo->create([
            'cliente_id' => $duplicateFind->cliente_id,
            'laudo_modelo_id' => $duplicateFind->laudo_modelo_id,
            'nome_laudo' => $duplicateFind->nome_laudo,
            'anexo_url' => $duplicateFind->anexo_url,
            'data_html' => $duplicateFind->data_html,
            'cod_storage_laudo' => $duplicateFind->cod_storage_laudo
        ]);

        $redirect = 'Oops, algo deu errado!';

        if ($duplicate) {
            $redirect = redirect()->to(route('laudos.index'));
        }

        return $redirect;
    }
}
