@extends('layouts.master')
@section('title', 'Laudos')
@section('page-title')
    <h4> Laudos </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item"> <a href="/laudos/index"> Laudos </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Novo Laudo </a> </li>
    </ol>
@endsection
@section('content')
    @if($errors->any())
        <div class="row form-row">
            <div class="alert alert-danger">
                @if($errors->has('laudo_modelo_id'))
                    <h4> Verifique o campo modelo de laudo </h4>
                    @foreach($errors->get('laudo_modelo_id') as $error)
                        {{ $error }}
                    @endforeach
                @endif
                @if($errors->has('cliente_id'))
                <h4> Verifique o campo cliente </h4>
                    @foreach($errors->get('cliente_id') as $error)
                    {{ $error }}
                    @endforeach
                @endif
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a
                            class="nav-link active"
                            data-bs-toggle="tab"
                            href="#cliente"
                            role="tab"
                            aria-selected="true"
                            id="btnTabCliente"
                            >
                                <span class="d-none d-sm-block"> Cliente </span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a
                            class="nav-link"
                            data-bs-toggle="tab"
                            href="#tipoLaudo"
                            role="tab"
                            aria-selected="false"
                            id="btnTabTipoLaudo"
                            >
                                <span class="d-none d-sm-block"> Tipo </span>
                            </a>
                        </li>
                    </ul>
                    <form id="formCreateLaudo" action="/laudos/createEditor" method="GET">
                        <div class="tab-content p-3 text-muted">
                            <div
                                class="tab-pane active"
                                id="cliente"
                                role="tabpanel"
                            >
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label> Selecione o Cliente </label>
                                            <select name="cliente_id" class="form-control" id="optionsCliente">
                                                <option value=""> Selecione o cliente </option>
                                                @foreach($optionsCliente as $k => $v)
                                                    <option value="{{ $k }}">
                                                        {{$v}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <a id="addNewCliente" href="#" class="float-start">
                                            Adicionar novo cliente
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary float-end" id="step1">
                                            Avançar <i class="fa fa-arrow-right"> </i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                             class="tab-pane"
                             id="tipoLaudo"
                             role="tabpanel"
                            >
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label> Selecione o Modelo de Laudo </label>
                                            <select
                                                id="optionsLaudoModelo"
                                                name="laudo_modelo_id"
                                                class="form-control"
                                            >
                                                <option value=""> Selecione </option>
                                                @foreach($optionsTiposLaudos as $k => $v)
                                                    <option value="{{ $k }}">
                                                        {{ $v }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <a
                                        id="optionsLaudoModeloEdit"
                                        href="#"
                                        class="float-start newChapter d-none"
                                        >
                                            Adicionar um novo capítulo a este modelo de laudo
                                        </a>
                                    </div>
                                </div>
                                <div class="row form-row" id="boxSelectCapitulos"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-default float-start" id="backStep1">
                                            <i class="fa fa-arrow-left"> </i> Voltar
                                        </button>
                                        <button type="submit" class="btn btn-primary float-end" id="step2">
                                            Avançar <i class="fa fa-arrow-right"> </i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
