@extends('layouts.master')
@section('title', 'Laudos')
@section('page-title')
    <h4> Laudos </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Laudos </a> </li>
    </ol>
@endsection
@section('content')
    @component('components.filter')
        <form id="searchFilterLaudos">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label> Nome Laudo </label>
                        <input class="form-control" name="nome_laudo" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label> CNPJ/CPF Cliente </label>
                        <input class="form-control" name="cnpj_cliente" />
                    </div>
                </div>
                <div class="col-md-6" style="margin-top:2%;">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary float-end">
                            <i class="fa fa-search"> </i> Pesquisar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @endcomponent

    @if(session('error'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    {{session('error')}}
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" id="gridLaudos">
                    <div class="row">
                        <div class="col-md-12">
                            <span> Total de registros: {{ count($dataLaudos) }} </span>
                            <a class="btn btn-primary float-end" href="/laudos/create">
                                <i class="fa fa-plus-square"> </i> Novo
                            </a>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-12" class="table-responsive">
                            @if (count($dataLaudos) > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> Nome do cliente </th>
                                            <th> CNPJ/CPF Cliente </th>
                                            <th> Tipo do Laudo </th>
                                            <th width="5%"> Ações </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataLaudos as $laudo)
                                            <tr>
                                                <td> {{ !empty($laudo->cliente->nome_fantasia) ? $laudo->cliente->nome_fantasia : 'N/A' }} </td>
                                                <td> {{ !empty($laudo->cliente->cnpjcpf) ? $laudo->cliente->cnpjcpf : 'N/A' }} </td>
                                                <td> {{ !empty($laudo->laudoModelo->nome_modelo) ? $laudo->laudoModelo->nome_modelo : 'N/A' }} </td>
                                                <td>
                                                    <div class="btn-group dropstart">
                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-sm btn-xs dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a
                                                                class="dropdown-item btnEditTipoLaudo"
                                                                href="/laudos/edit/{{$laudo->id}}"
                                                                >
                                                                    <i class="fa fa-edit"> </i> Editar
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a
                                                                    class="dropdown-item"
                                                                    href="{{ route('laudos.duplicate', $laudo->id) }}"
                                                                >
                                                                    <i class="fas fa-copy"></i> Duplicar
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a
                                                                class="dropdown-item"
                                                                href="/laudos/downloadPDF/{{$laudo->id}}" id="{{ $laudo->id }}"
                                                                >
                                                                    <i class="fas fa-file-pdf"></i> PDF
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a
                                                                style="color:red;"
                                                                class="dropdown-item btnDeleteLaudo"
                                                                href="javascript:void(0)" id="{{ $laudo->id }}"
                                                                >
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
