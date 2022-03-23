<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ClientesRequest;
use App\Models\Cliente;
use App\Models\ClienteEndereco;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientesController extends Controller
{
    public function index(Request $request){
        $cliente = new Cliente;

        $data = $cliente->getClientes($request->all());
        return view("clientes.index", ['dataClientes' => $data]);
    }

    public function create(){
        return view('clientes.create');
    }

    public function store(ClientesRequest $request){

        try {                        
            $attachmentName = "";
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    $nameExploded = explode('.', $attachment->getClientOriginalName());

                    if (Storage::exists('public/ClientsAttachments/'. $attachment->getClientOriginalName())) {
                        $newName = $this->getNameDifferent($nameExploded);

                    } else {
                        $newName = $nameExploded[0];
                    }

                    $nameFile = $newName . "." . $attachment->extension();
        
                    $upload = $attachment->storeAs("public/ClientsAttachments/", $nameFile);

                    $attachmentName .= ($nameFile .";");

                    if (!$upload) {
                        return [
                            'error' => true,
                            'msg' => 'Falha ao fazer upload da imagem!'
                        ];
                    }
                }
                
            }
    
            $clientResponse = Cliente::create([
                'tipo_pessoa'           => $request["tipo_pessoa"] ?? '',
                'cnpjcpf'               => $request["cnpjcpf"] ?? '',
                'razao_social'          => $request["razao_social"] ?? '',
                'nome_fantasia'         => $request["nome_fantasia"] ?? '',
                'inscricao_municipal'   => $request["inscricao_municipal"] ?? '',
                'inscricao_estadual'    => $request["inscricao_estadual"] ?? '',
                'email'                 => $request["email"] ?? '',
                'phone'                 => $request["phone"] ?? '',
                'celular'               => $request["celular"] ?? '',
                'attachments'           => $attachmentName ?? ''
            ]);

            foreach ($request["endereco"] as $indexAddress => $address) {
                if ($address) {
                    $attachmentAddressName = "";
                    if ($request->hasFile('attachmentsAddress'.$indexAddress)) {
                        foreach ($request->file('attachmentsAddress'.$indexAddress) as $attachment) {
                            $nameExploded = explode('.', $attachment->getClientOriginalName());

                            if (Storage::exists('public/ClientsAttachmentsAddress/'. $attachment->getClientOriginalName())) {
                                $newName = $this->getNameDifferentAddress($nameExploded);

                            } else {
                                $newName = $nameExploded[0];
                            }

                            $nameFile = $newName . "." . $attachment->extension();
                
                            $upload = $attachment->storeAs("public/ClientsAttachmentsAddress/", $nameFile);

                            $attachmentAddressName .= ($nameFile .";");

                            if (!$upload) {
                                return [
                                    'error' => true,
                                    'msg' => 'Falha ao fazer upload da imagem!'
                                ];
                            }
                        }
                        
                    }

                    ClienteEndereco::create([
                        'cep'           => $request["cep"][$indexAddress] ?? '',
                        'endereco'      => $address ?? '',
                        'bairro'        => $request["bairro"][$indexAddress] ?? '',
                        'cidade'        => $request["cidade"][$indexAddress] ?? '',
                        'estado'        => $request["estado"][$indexAddress] ?? '',
                        'numero'        => $request["numero"][$indexAddress] ?? '',
                        'pais'          => $request["pais"][$indexAddress] ?? '',
                        'cliente_id'    => $clientResponse->id,
                        'attachments'   => $attachmentAddressName ?? ''
                    ]);
                }
            }
            
            return [
                'error' => false,
                'msg' => 'Registro salvo com sucesso!'
            ];

        } catch(\Exception $error) {
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o registro tente novamente',
                'error_message' => $error->getMessage()
            ];
        }
        
    }

    public function edit($id){
        $cliente = new Cliente;

        return view('clientes.edit', [
            'cliente' => $cliente->getClienteByID($id)
        ]);
    }

    private static function getNameDifferent($nameExploded)
    {
        for ($index = 1; $index <= 100; $index++) {
            if (Storage::exists('public/ClientsAttachments/'. $nameExploded[0] . ' ('.$index.').'. $nameExploded[1])) {
                continue;
        
            } else {
                return $nameExploded[0] . ' ('.$index.')';
            }
        }        
    }

    private static function getNameDifferentAddress($nameExploded)
    {
        for ($index = 1; $index <= 100; $index++) {
            if (Storage::exists('public/ClientsAttachmentsAddress/'. $nameExploded[0] . ' ('.$index.').'. $nameExploded[1])) {
                continue;
        
            } else {
                return $nameExploded[0] . ' ('.$index.')';
            }
        }        
    }

    public function update($id, ClientesRequest $request){

        try {                
            $attachmentName = DB::table('clientes')->where('id', $id)->pluck('attachments')->first();
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    $nameExploded = explode('.', $attachment->getClientOriginalName());

                    if (Storage::exists('public/ClientsAttachments/'. $attachment->getClientOriginalName())) {
                        $newName = $this->getNameDifferent($nameExploded);

                    } else {
                        $newName = $nameExploded[0];
                    }

                    $nameFile = $newName . "." . $nameExploded[1];
        
                    $upload = $attachment->storeAs("public/ClientsAttachments/", $nameFile);

                    $attachmentName .= ($nameFile .";");

                    if (!$upload) {
                        return [
                            'error' => true,
                            'msg' => 'Falha ao fazer upload da imagem!'
                        ];
                    }
                }
                
            }
    
            Cliente::where('id', $id)->update([
                'tipo_pessoa'           => $request["tipo_pessoa"] ?? '',
                'cnpjcpf'               => $request["cnpjcpf"] ?? '',
                'razao_social'          => $request["razao_social"] ?? '',
                'nome_fantasia'         => $request["nome_fantasia"] ?? '',
                'inscricao_municipal'   => $request["inscricao_municipal"] ?? '',
                'inscricao_estadual'    => $request["inscricao_estadual"] ?? '',
                'email'                 => $request["email"] ?? '',
                'phone'                 => $request["phone"] ?? '',
                'celular'               => $request["celular"] ?? '',
                'attachments'           => $attachmentName ?? NULL
            ]);

            if ($request["endereco"]) {
                foreach ($request["endereco"] as $indexAddress => $address) {
                    if ($address) {
                        $attachmentAddressName = (isset($request["idAddress".$indexAddress])) 
                                                 ? DB::table('cliente_endereco')
                                                    ->where('address_id', $request["idAddress".$indexAddress])
                                                    ->pluck('attachments')
                                                    ->first() : "";
    
                        // dd($attachmentAddressName, isset($request["idAddress".$indexAddress]), $request["idAddress".$indexAddress], $request);
    
                        if ($request->hasFile('attachmentsAddress'.$indexAddress)) {
                            foreach ($request->file('attachmentsAddress'.$indexAddress) as $attachment) {
                                $nameExploded = explode('.', $attachment->getClientOriginalName());
    
                                if (Storage::exists('public/ClientsAttachmentsAddress/'. $attachment->getClientOriginalName())) {
                                    $newName = $this->getNameDifferentAddress($nameExploded);
    
                                } else {
                                    $newName = $nameExploded[0];
                                }
    
                                $nameFile = $newName . "." . $attachment->extension();
                    
                                $upload = $attachment->storeAs("public/ClientsAttachmentsAddress/", $nameFile);
    
                                $attachmentAddressName .= ($nameFile .";");
    
                                if (!$upload) {
                                    return [
                                        'error' => true,
                                        'msg' => 'Falha ao fazer upload da imagem!'
                                    ];
                                }
                            }
                            
                        }
                        
                        if (isset($request["idAddress".$indexAddress])) {
                            
                            ClienteEndereco::where('address_id', $request["idAddress".$indexAddress])->update([
                                'cep'           => $request["cep"][$indexAddress] ?? '',
                                'endereco'      => $address ?? '',
                                'bairro'        => $request["bairro"][$indexAddress] ?? '',
                                'cidade'        => $request["cidade"][$indexAddress] ?? '',
                                'numero'        => $request["numero"][$indexAddress] ?? '',
                                'estado'        => $request["estado"][$indexAddress] ?? '',
                                'pais'          => $request["pais"][$indexAddress] ?? '',
                                'cliente_id'    => $id,
                                'attachments'   => $attachmentAddressName ?? ''
                            ]);
    
                        } else {
                            ClienteEndereco::create([
                                'cep'           => $request["cep"][$indexAddress] ?? '',
                                'endereco'      => $address ?? '',
                                'bairro'        => $request["bairro"][$indexAddress] ?? '',
                                'cidade'        => $request["cidade"][$indexAddress] ?? '',
                                'numero'        => $request["numero"][$indexAddress] ?? '',
                                'estado'        => $request["estado"][$indexAddress] ?? '',
                                'pais'          => $request["pais"][$indexAddress] ?? '',
                                'cliente_id'    => $id,
                                'attachments'   => $attachmentAddressName ?? ''
                            ]);
                        } 
    
                    }
                }
            }
            
            
            return [
                'error' => false,
                'msg' => 'Registro salvo com sucesso!'
            ];

        } catch(\Exception $error) {
            DB::rollback();
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o registro tente novamente',
                'error_message' => $error->getMessage()
            ];
        }
    }

    public function destroy($id){
        $cliente = new Cliente;

        $data = $cliente->deleteCliente($id);
        return response()->json($data);
    }

    public function getClientesJSON(){
        $cliente = new Cliente;

        $data = $cliente->getOptionsClientes();
        return response()->json($data);
    }

    public function deleteAttachment(Request $request, $id)
    {
        $attachments = DB::table('clientes')->where('id', $id)->pluck('attachments')->first();

        $newAttachments = str_replace($request["attachment"].";", '', $attachments);
        
        // Deletando anexo do Storage
        Storage::delete("ClientsAttachments/".$request["attachment"]);

        Cliente::where('id', $id)->update([
            'attachments' => $newAttachments ?? NULL
        ]);
    }

    public function deleteAttachmentAddress(Request $request, $id)
    {
        $attachments = DB::table('cliente_endereco')->where('address_id', $id)->pluck('attachments')->first();

        $newAttachments = str_replace($request["attachment"].";", '', $attachments);
        
        // Deletando anexo do Storage
        Storage::delete("ClientsAttachmentsAddress/".$request["attachment"]);

        ClienteEndereco::where('address_id', $id)->update([
            'attachments' => $newAttachments ?? NULL
        ]);
    }

    public function deleteAddress($id)
    {
        $address = DB::table('cliente_endereco')->where('address_id', $id)->first();

        if ($address->attachments) {
            foreach (explode(';', $address->attachments) as $attachment) {
                if($attachment) {
                    // Deletando anexo do Storage
                    Storage::delete("ClientsAttachmentsAddress/".$attachment);
                }
            }
        }
        
        DB::table('cliente_endereco')->where('address_id', $id)->delete();
    }
}
