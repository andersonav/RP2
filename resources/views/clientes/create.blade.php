@extends('layouts.modal')
@section('modal-form', 'addFormCliente')
@section('modal-header', 'Adicionar Cliente')
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
                        <input name="razao_social" class="form-control" type="text" />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-8">
                    <div class="form-group">
                        <label> Nome Fantasia </label>
                        <input name="nome_fantasia" class="form-control" type="text" />
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label> Tipo de Pessoa  </label>
                        <select name="tipo_pessoa" class="form-select select2">
                            <option value="" disabled> Selecione </option>
                            <option value="J" selected> Jurídica </option>
                            <option value="F"> Física </option>
                        </select>

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cnpjcpf"> CNPJ </label>
                        <input id="inputCnpjCpf" type="text" name="cnpjcpf" class="form-control">

                        <div class="error_feedback"> </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> E-mail </label>
                        <input name="email" class="form-control" type="email" />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> Inscrição Munícipal   </label>
                        <input name="inscricao_municipal" class="form-control" type="text" />

                        <div class="error_feedback"> </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Inscrição Estadual </label>
                        <input name="inscricao_estadual" class="form-control" type="text" />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label> Telefone  </label>
                        <input name="phone" class="form-control phone" type="text" />

                        <div class="error_feedback"> </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label> Celular  </label>
                        <input name="celular" class="form-control phone" type="text" />

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
        </div>
    </div>

    <h5 class="font-size-14 mb-4">
        <i class="mdi mdi-arrow-right text-primary me-1"> </i>
        Endereços
        <button type="button" class="btn btn-primary float-end" id="addAddress">
            <i class="fa fa-plus-square"> </i> Novo
        </button>
    </h5>

    <div id="customer-address-wrapper">
        <div class="card border border-primary customer-address-single">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-danger float-end customer-address-remove">
                            <i class="fa fa-times"> </i> Remover
                        </button>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> CEP </label>
                            <input id="cep0" name="cep[]" class="form-control cep" type="text" data-index="0" />
                            

                            <div class="error_feedback"> </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label> Endereço </label>
                            <input id="address0" name="endereco[]" class="form-control" type="text" />

                            <div class="error_feedback"> </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <div class="form-group">
                            <label> Bairro </label>
                            <input id="neighborhood0" name="bairro[]" class="form-control" type="text" />

                            <div class="error_feedback"> </div>
                        </div>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label> Cidade  </label>
                            <input id="city0" name="cidade[]" class="form-control" type="text" />

                            <div class="error_feedback"> </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label> Número </label>
                            <input id="number0" name="numero[]" class="form-control" type="number">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> Estado  </label>
                            <input id="state0" name="estado[]" class="form-control" type="text" />

                            <div class="error_feedback"> </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> País  </label>
                            <input id="country0" name="pais[]" class="form-control" type="text" />

                            <div class="error_feedback"> </div>
                        </div>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-12">
                        <label> Anexos </label>
                        <input type="file" name="attachmentsAddress0" id="attachmentsAddress" class="form-control" multiple>
    
                        <div class="error_feedback"> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('btnClose')
    Fechar
@endsection
@section('btnSubmit')
    Salvar
@endsection


