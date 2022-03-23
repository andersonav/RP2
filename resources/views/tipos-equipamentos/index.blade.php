@extends('layouts.master')
@section('title', 'Tipos de Equipamentos')
@section('page-title')
    <h4> Equipamentos/Referências </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Equipamentos/Referências </a> </li>
    </ol>
@endsection
@section('content')
@component('components.filter')
    <form id="searchFilterTiposEquipamentos">
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
            <div class="card-body" id="gridTiposEquipamentos">
                <div class="row">
                    <div class="col-md-12">
                        <span> Total de registros: 1 </span>
                        <a class="btn btn-primary float-end" href="/tiposEquipamentos/create">
                            <i class="fa fa-plus-square"> </i> Novo
                        </a>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12" class="table-responsive">
                        @if (count($dataTiposEquipamentos) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> Nome </th>
                                        <th> Descrição </th>
                                        <th> Tipo </th>
                                        <th> Alterado por </th>
                                        <th width="5%"> Ações </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataTiposEquipamentos as $tiposEquipamentos)
                                        <tr>
                                            <td> {{ !empty($tiposEquipamentos->nome_modelo) ? $tiposEquipamentos->nome_modelo : 'Não informado' }} </td>
                                            <td> {{ !empty($tiposEquipamentos->descricao_modelo) ? $tiposEquipamentos->descricao_modelo : 'Não informado' }} </td>
                                            <td> {{ $tiposEquipamentos->tipo === 'equipment' ? 'Equipamento' : 'Referência' }}</td>
                                            <td> {{ !empty($tiposEquipamentos->user->name) ? $tiposEquipamentos->user->name : 'Não informado' }} </td>
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
                                                            class="dropdown-item btnEditTipoEquipamento"
                                                            href="/tiposEquipamentos/editTiposEquipamentos/{{$tiposEquipamentos->id}}"
                                                            >
                                                                <i class="fa fa-edit"> </i> Editar
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a
                                                                style="color:red;"
                                                                class="dropdown-item btnDeleteTipoEquipamento"
                                                                href="javascript:void(0)"
                                                                id="{{ $tiposEquipamentos->id }}"
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
