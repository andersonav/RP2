@extends('layouts.master')
@section('title', 'Usuários')
@section('page-title')
    <h4> Usuários </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Usuários </a> </li>
    </ol>
@endsection
@section('content')
    {{-- FILTRO DE PESQUISA --}}
    @component('components.filter')
        <form id="searchFilterUsers">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label> Nome </label>
                        <input class="form-control" name="name" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label> E-mail </label>
                        <input class="form-control" name="email" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label> Ativo </label>
                        <select name="active" class="form-select">
                            <option value=""> Selecione </option>
                            <option value="y"> Sim </option>
                            <option value="n"> Não </option>
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
    {{-- FILTRO DE PESQUISA --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" id="gridUsers">
                    <div class="row">
                        <div class="col-md-12">
                            <span> Total de registros: {{ count($dataUsers) }} </span>
                            <button class="btn btn-primary float-end" id="addUser">
                                <i class="fa fa-plus-square"> </i> Novo
                            </button>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-12" class="table-responsive">
                            @if (count($dataUsers) > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> Nome </th>
                                            <th> Data Nascimento </th>
                                            <th width="30%"> E-mail </th>
                                            <th> Celular </th>
                                            <th> Ativo </th>
                                            <th width="5%"> Ações </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataUsers as $user)
                                            <tr>
                                                <td> {{ !empty($user->name) ? $user->name : 'Não informado' }} </td>
                                                <td> {{ !empty($user->data_nascimento) ? date('d/m/Y', strtotime($user->data_nascimento)) : "Não informado"}}  </td>
                                                <td> {{ !empty($user->email) ? $user->email : 'Não informado' }} </td>
                                                <td> {{ !empty($user->cell) ? $user->cell : 'Não informado' }} </td>
                                                <td>
                                                    <span
                                                        class="badge rounded-pill bg-{{ $user->active == 'y' ? 'success' : 'danger' }}">
                                                        {{ $user->active == 'y' ? 'Sim' : 'Não' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group dropstart">
                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-sm btn-xs dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item btnEditUser"
                                                                    href="javascript:void(0)" id="{{ $user->id }}">
                                                                    <i class="fa fa-edit"> </i> Editar
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a style="color:red;" class="dropdown-item btnDeleteUser"
                                                                    href="javascript:void(0)" id="{{ $user->id }}">
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
