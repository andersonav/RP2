@extends('layouts.modal')
@section('modal-form', 'editFormCliente')
@section('modal-header', 'Alterar Cliente')
@section('modal-content')
    <h5 class="font-size-14 mb-4">
        <i class="mdi mdi-arrow-right text-primary me-1"> </i>
        Informações Gerais
    </h5>
    <div class="card border border-primary">
        <div class="card-body">
            <div class="row form-row">
                <div class="col-12">
                    <div class="form-group">
                        <label> Razão Social </label>
                        <input
                            name="razao_social"
                            class="form-control"
                            type="text"
                            value="{{ $cliente->razao_social }}"
                        />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-8">
                    <div class="form-group">
                        <label> Nome Fantasia </label>
                        <input
                            name="nome_fantasia"
                            class="form-control"
                            type="text"
                            value="{{ $cliente->nome_fantasia }}"
                        />
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label> Tipo de Pessoa  </label>
                        <select name="tipo_pessoa" class="form-select select2">
                            <option value="" selected> Selecione </option>
                            <option
                                value="J"
                                @if($cliente->tipo_pessoa == "J") selected @endif
                            >
                                Jurídica
                            </option>
                            <option
                                value="F"
                                @if($cliente->tipo_pessoa == "F") selected @endif
                            >
                                Física
                            </option>
                        </select>

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cnpjcpf"> CNPJ/CPF </label>
                        <input
                            name="cnpjcpf"
                            class="form-control"
                            type="text"
                            id="inputCnpjCpf"
                            value={{ $cliente->cnpjcpf }}
                        />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> E-mail </label>
                        <input name="email" class="form-control" type="email" value={{ $cliente->email}} />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> Inscrição Munícipal   </label>
                        <input
                            name="inscricao_municipal"
                            class="form-control"
                            type="text"
                            value="{{$cliente->inscricao_municipal}}"
                        />

                        <div class="error_feedback"> </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Inscrição Estadual </label>
                        <input
                            name="inscricao_estadual"
                            class="form-control"
                            type="text"
                            value="{{$cliente->inscricao_estadual}}"
                        />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label> Telefone  </label>
                        <input
                            name="phone"
                            class="form-control phone"
                            type="text"
                            value="{{ $cliente->phone}}"
                        />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label> Celular  </label>
                        <input
                            name="celular"
                            class="form-control phone"
                            type="text"
                            value="{{ $cliente->celular}}"
                         />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-12">
                    <label> Anexos </label>
                    <input type="file" name="attachments" id="attachments" class="form-control" multiple>

                    <div class="error_feedback"> </div>
                </div>
            </div>
            @if ($cliente->attachments)
                <div class="row form-row">
                    <div class="col-12">
                        <ul class="list-group">
                            <li class="list-group-item disabled" aria-disabled="true">Anexos já adicionados</li>
                            @foreach (explode(';', $cliente->attachments) as $attachment)
                                @if ($attachment)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="uil uil-paperclip p-0 mr-3"></i> {{ $attachment }}
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <a href="{{ '/storage/ClientsAttachments/'. $attachment }}" class="btn btn-primary btn-soft-primary me-3" download><i class="bx bx-download"></i></a>
                                            <button type="button" class="btn btn-danger btn-soft-danger btnDeleteAttachment" data-attachment="{{ $attachment }}"><i class="bx bxs-trash"></i></button>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif            
        </div>
    </div>

    <h5 class="font-size-14 mb-4">
        <i class="mdi mdi-arrow-right text-primary me-1"> </i>
        Endereço
        <button type="button" class="btn btn-primary float-end" id="addAddress">
            <i class="fa fa-plus-square"> </i> Novo
        </button>
    </h5>

    <div id="customer-address-wrapper">
        @foreach ($cliente->clienteEndereco as $indexAddress => $address)
            <div class="card border border-primary customer-address-single">
                <input type="hidden" name="idAddress{{ $indexAddress }}" value="{{ $address->address_id }}">
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <button id="btnDeleteAddress" data-id="{{ $address->address_id }}" type="button" class="btn btn-danger float-end customer-address-remove">
                                <i class="fa fa-times"> </i> Remover
                            </button>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> CEP </label>
                                <input 
                                    id="cep{{ $indexAddress }}" 
                                    name="cep[]" 
                                    class="form-control cep" 
                                    type="text" 
                                    data-index="{{ $indexAddress }}"
                                    value="{{ $address->cep }}" />
    
                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label> Endereço </label>
                                <input
                                    id="endereco{{ $indexAddress }}"
                                    name="endereco[]"
                                    class="form-control"
                                    type="text"
                                    value="{{ $address->endereco}}"
                                />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label> Bairro </label>
                                <input
                                    id="bairro{{ $indexAddress }}"
                                    name="bairro[]"
                                    class="form-control"
                                    type="text"
                                    value="{{ $address->bairro}}"
                                />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Cidade  </label>
                                <input
                                    id="cidade{{ $indexAddress }}"
                                    name="cidade[]"
                                    class="form-control"
                                    type="text"
                                    value="{{ $address->cidade}}"
                                />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label> Número </label>
                                <input 
                                    id="numero{{ $indexAddress }}"
                                    name="numero[]"
                                    class="form-control"
                                    type="text"
                                    value="{{ $address->numero }}">

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> Estado  </label>
                                <input
                                    id="estado{{ $indexAddress }}"
                                    name="estado[]"
                                    class="form-control"
                                    type="text"
                                    value="{{ $address->estado}}"
                                />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> País  </label>
                                <input
                                    id="pais{{ $indexAddress }}"
                                    name="pais[]"
                                    class="form-control"
                                    type="text"
                                    value="{{ $address->pais}}"
                                />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-12">
                            <label> Anexos </label>
                            <input type="file" name="attachmentsAddress{{ $indexAddress }}" id="attachmentsAddress" class="form-control" multiple>
        
                            <div class="error_feedback"> </div>
                        </div>
                    </div>
                    @if ($address->attachments)
                        <div class="row form-row">
                            <div class="col-12">
                                <ul class="list-group">
                                    <li class="list-group-item disabled" aria-disabled="true">Anexos já adicionados</li>
                                    @foreach (explode(';', $address->attachments) as $attachment)
                                        @if ($attachment)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <i class="uil uil-paperclip p-0 mr-3"></i> {{ $attachment }}
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <a href="{{ '/storage/ClientsAttachmentsAddress/'. $attachment }}" class="btn btn-primary btn-soft-primary me-3" download><i class="bx bx-download"></i></a>
                                                    <button type="button" class="btn btn-danger btn-soft-danger btnDeleteAttachmentAddress" data-attachment="{{ $attachment }}" data-id-address="{{ $address->address_id }}"><i class="bx bxs-trash"></i></button>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif  
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('btnClose')
    Fechar
@endsection
@section('btnSubmit')
    Salvar
@endsection


