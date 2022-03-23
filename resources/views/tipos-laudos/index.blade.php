@extends('layouts.master')
@section('title', 'Tipos de Laudos')
@section('page-title')
    <h4> Tipos de Laudos </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Tipos de Laudos </a> </li>
    </ol>
@endsection
@section('content')
@component('components.filter')
    <form id="searchFilterTiposLaudos">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label> Nome Modelo </label>
                    <input class="form-control" name="nome_modelo" />
                </div>
            </div>
            <div class="col-md-9" style="margin-top:2%;">
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
            <div class="card-body" id="gridTiposLaudos">
                <div class="row">
                    <div class="col-md-12">
                        <span> Total de registros: {{ count($dataTiposLaudos) }} </span>
                        <a class="btn btn-primary float-end" href="/tiposLaudos/create">
                            <i class="fa fa-plus-square"> </i> Novo
                        </a>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12" class="table-responsive">
                        @if (count($dataTiposLaudos) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> Nome </th>
                                        <th> Descrição modelo </th>
                                        <th> Alterado por </th>
                                        <th width="5%"> Ações </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataTiposLaudos as $tiposLaudos)
                                        <tr>
                                            <td> {{ !empty($tiposLaudos->nome_modelo) ? $tiposLaudos->nome_modelo : 'Não informado' }} </td>
                                            <td> {{ !empty($tiposLaudos->descricao_modelo) ? $tiposLaudos->descricao_modelo : 'Não informado' }} </td>
                                            <td> {{ !empty($tiposLaudos->user->name) ? $tiposLaudos->user->name : 'Não informado' }} </td>
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
                                                            href="/tiposLaudos/editTiposLaudos/{{$tiposLaudos->id}}"
                                                            >
                                                                <i class="fa fa-edit"> </i> Editar
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a
                                                            style="color:red;"
                                                            class="dropdown-item btnDeleteTipoLaudo"
                                                            href="javascript:void(0)" id="{{ $tiposLaudos->id }}"
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
