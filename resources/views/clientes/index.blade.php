@extends('layouts.master')
@section('title', 'Clientes')
@section('page-title')
    <h4> Clientes </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Clientes </a> </li>
    </ol>
@endsection
@section('content')
    {{-- FILTRO DE PESQUISA --}}
    @component('components.filter')
        <form id="searchFilterClientes">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label> Razão Social </label>
                        <input class="form-control" name="razao_social" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label> CNPJ </label>
                        <input class="form-control" name="cnpjcpf" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label> Tipo Pessoa </label>
                        <select name="tipo_pessoa" class="form-select">
                            <option value="" class="d-none"> Selecione </option>
                            <option value="J"> Jurídica </option>
                            <option value="F"> Física </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3" style="margin-top:2%;">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary float-end">
                            <i class="fa fa-search"> </i> Pesquisar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @endcomponent
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" id="gridClientes">
                    <div class="row">
                        <div class="col-md-12">
                            <span> Total de registros: {{ count($dataClientes) }} </span>
                            <button class="btn btn-primary float-end" id="addCliente">
                                <i class="fa fa-plus-square"> </i> Novo
                            </button>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-12">
                            @if (count($dataClientes) > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                       <tr>
                                            <th> Razão Social  </th>
                                            <th> CNPJ/CPF </th>
                                            <th width="30%"> Inscrição Municipal </th>
                                            <th> Celular </th>
                                            <th width="5%"> Ações </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataClientes as $cliente)
                                            <tr>
                                                <td> {{ !empty($cliente->razao_social) ? $cliente->razao_social : 'Não informado' }} </td>
                                                <td> {{ !empty($cliente->cnpjcpf) ? $cliente->cnpjcpf : "Não informado"}}  </td>
                                                <td> {{ !empty($cliente->inscricao_municipal) ? $cliente->inscricao_municipal : 'Não informado' }} </td>
                                                <td> {{ !empty($cliente->celular) ? $cliente->celular : 'Não informado' }} </td>
                                                <td>
                                                    <div class="btn-group dropstart">
                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-sm btn-xs dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item btnEditCliente"
                                                                    href="javascript:void(0)" id="{{ $cliente->id }}">
                                                                    <i class="fa fa-edit"> </i> Editar
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a style="color:red;" class="dropdown-item btnDeleteCliente"
                                                                    href="javascript:void(0)" id="{{ $cliente->id }}">
                                                                    <i class="fa fa-trash"> </i> Excluir
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="alert alert-warning text-center" role="alert">
                                    <span> Sem registros </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
