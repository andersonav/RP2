@extends('layouts.master')
@section('title', 'Tipos de Laudos')
@section('page-title')
    <h4>
    <a href="/laudos/create"> <i class="fa fa-arrow-left"> </i> </a> &nbsp;
        Laudos - EDITOR
    </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item"> <a href="/laudos/index"> Laudos </a> </li>
        <li class="breadcrumb-item"> <a href="/laudos/create"> Novo Laudo </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Editor </a> </li>
    </ol>
@endsection
@section('content')
<style>
    #cardTipografia .cursorPointer {
        cursor: pointer;
    }

    #tinymce img{
        width: 50%!important;
    }

    #box-imagens img {
        width: 150px;
    }

    #box-imagens .checkSelectLaudoImages {
        position: absolute;
        top: 5px;
        left: 5px;
        z-index: 99;
    }

    #box-imagens .removeImg {
        position: absolute;
        top: 2px;
        right: 5px;
        z-index: 99;
    }

    #box-imagens .editImg {
        position: absolute;
        top: 2px;
        right: 5px;
        z-index: 99;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="pixieModalLong" tabindex="-1" role="dialog" aria-labelledby="pixieModalLongTitle"
aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="max-width: 75%;" role="document">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="pixieModalLongTitle">Editor de imagem - Pixie</h5>
            </div> --}}
            <div class="modal-body">
                <pixie-editor></pixie-editor>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" onclick="$('#pixieModalLong').modal('hide');" class="btn btn-secondary"
                    data-dismiss="modal">Fechar</button>
            </div> --}}
        </div>
    </div>
</div>

<form id="addLaudo">
    <div class="row form-row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="card-title"> Cliente:  </label>
                                <p> {{ $dataCliente->nome_fantasia }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="card-title"> Tipo de Laudo: </label>
                                <p> {{ $dataModeloLaudo->nome_modelo }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-primary waves-effect waves-light float-end addPacoteFiguras">
                                <i class="fa fa-plus-square"> </i>  Pacote de Figuras
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            {{-- CONTENT EDITOR --}}
            <input type="hidden" name="cliente_id" value="{{$dataCliente->id}}" />
            <input type="hidden" name="laudo_modelo_id" value="{{$dataModeloLaudo->id}}" />
            <input type="hidden" name="cod_storage" id="codStorage"/>
            <div id="customWidget1"></div>
            <div id="customWidget2"></div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row form-row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary btn-sm pull-right" id="refreshKeys">
                                    <i class="fas fa-list-ol" style="margin-right: 5px;"></i> Atualizar índice
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button
                                type="submit"
                                class="btn btn-primary btn-sm pull-right float-end btnSubmit"
                                >
                                    Salvar
                                </button>
                            </div>
                        </div>
                        <div class="box-content-laudo" id="laudo-wrapper">
                            <textarea id="textAreaContent" name="data_html">
                                @if(count($dataModeloLaudo->laudoCapitulos) > 0)
                                    @foreach($dataModeloLaudo->laudoCapitulos as $k => $laudoCapitulos)
                                        <h1 style="text-align: center" class="capituloText"> {{$laudoCapitulos->nome_capitulo}} </h1>
                                        <p style="text-align:center;">
                                            {{ nl2br($laudoCapitulos->texto_padrao) }}
                                        </p>
                                        @if(count($laudoCapitulos->laudoModeloSubcapitulos) > 0)
                                            @foreach($laudoCapitulos->laudoModeloSubcapitulos as $laudoSubcapitulos)
                                                @if ($laudoSubcapitulos->nome_subcapitulo)
                                                    <h4 style="text-align:center;" class="subcapituloText">
                                                        {{ $laudoSubcapitulos->nome_subcapitulo}}
                                                    </h4>
                                                @endif
                                                @if(!empty($laudoSubcapitulos->texto_padrao))
                                                    <p style="text-align:center;">
                                                        {{ $laudoSubcapitulos->texto_padrao}}
                                                    </p>
                                                @endif
                                                @if(count($laudoSubcapitulos->subCapsN3) > 0)
                                                    @foreach($laudoSubcapitulos->subCapsN3 as $i => $j)
                                                        @if ($j->nome_sub_subcapitulo)
                                                            <h6 style="text-align:center; font-weight: bold" class="n3Text">
                                                                {{ $j->nome_sub_subcapitulo }}
                                                            </h6>
                                                        @endif
                                                        @if(!empty($j->texto_padrao))
                                                            <p style="text-align:center;"> {{ $j->texto_padrao }} </p>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            </textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary pull-right float-end btnSubmit">
                            Salvar
                        </button>
                    </div>
                </div>
            </div>
        {{-- END CONTENT EDITOR --}}
        {{-- WIDGETS --}}
        <div class="col-3">
            <div class="card" id="cardNavPanel">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <label> Painel de navegação </label>
                        </div>
                        <div class="col-3">
                            <a href="#navPanelCollapse" class="btn btn-primary btn-sm float-end" data-bs-toggle="collapse"
                                role="button" aria-expanded="true" aria-controls="navPanelCollapse">
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="box-forms collapse" id="navPanelCollapse">
                        Atualize os indices para visualizar o painel
                    </div>
                </div>
            </div>
            <div class="card" id="cardTipografia">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label> Tipografia </label>
                        </div>
                        <div class="col-6">
                            <a href="#box-formas" class="btn btn-primary btn-sm float-end" data-bs-toggle="collapse"
                                role="button" aria-expanded="true" aria-controls="box-formas">
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="box-forms collapse" id="box-formas">
                        <h1 class="item-draggable cursorPointer tipography capituloText"> Título N1 </h1>
                        <h4 class="item-draggable cursorPointer tipography subcapituloText"> Subtítulo N2 </h4>
                        <h6 class="item-draggable cursorPointer tipography n3Text" style="font-weight: bold"> Subtítulo N3 </h6>
                        <p class="item-draggable cursorPointer tipography">  Parágrafo </p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" id="cardImagens">
                    <div class="row">
                        <div class="col-6">
                            <label> Figuras </label>
                        </div>
                        <div class="col-6">
                            <a href="#box-imagens" class="btn btn-primary btn-sm float-end" data-bs-toggle="collapse"
                                role="button" aria-expanded="true" aria-controls="box-imagens">
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 d-flex flex-column">
                            <label> Forma de exibição </label>

                            <select name="typeDisplay" id="typeDisplay" class="select2">
                                <option value="" disabled>Selecione</option>
                                <option value="row" selected>Vertical</option>
                                <option value="column">Horizontal</option>
                            </select>
                        </div>
                        <div class="col-4 d-flex flex-column">
                            <label> Descrição Imagem </label>

                            <select name="typeDescription" id="typeDescription" class="select2">
                                <option value="" disabled>Selecione</option>
                                <option value="group" selected>Grupo</option>
                                <option value="individual">Individual</option>
                            </select>
                        </div>
                        <div id="divImagesPerColumn" class="col-4 d-flex flex-column d-none">
                            <label> Imagens por linha </label>

                            <select name="imagensPerColumn" id="imagensPerColumn" class="select2">
                                <option value="" disabled>Selecione</option>
                                <option value="1">1 Imagem</option>
                                <option value="2">2 Imagens</option>
                                <option value="3">3 Imagens</option>
                                <option value="4">4 Imagens</option>
                            </select>
                        </div>
                        <div id="divImagesPerPage" class="col-4 d-flex flex-column d-none">
                            <label> Imagens por grupo </label>

                            <input type="number" name="imagensPerPage" id="imagensPerPage" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="box-forms collapse show" id="box-imagens">
                        <p> Ainda não foi adicionado nenhuma figura. </p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="javascript:void(0)" class="addPacoteFiguras">  Carregar figuras </a>
                        <button type="button" class="addLaudoImages btn btn-sm btn-primary d-none">Adicionar</button>
                    </div>
                </div>
            </div>
            <div class="card" id="cardPersonalizado">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label> Personalizado 1 </label>
                        </div>
                        <div class="col-6">
                            <a
                            href="#box-personalizado"
                            class="btn btn-primary btn-sm float-end"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="true"
                            aria-controls="box-personalizado"
                            >
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="box-forms collapse show" id="box-personalizado">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="gridPersonalizados">
                                    <span id="emptyWidget1"> Adicione um novo widget personalizado </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a
                    href="javascript:void(0)"
                    id="addNewPersonalizado"
                    >
                        Novo personalizado
                    </a>
                </div>
            </div>
            <div class="card" id="cardPersonalizadoImoveis">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label> Personalizado 2 </label>
                        </div>
                        <div class="col-6">
                            <a
                            href="#box-personalizado-imoveis"
                            class="btn btn-primary btn-sm float-end"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="true"
                            aria-controls="box-personalizado-imoveis"
                            >
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                        </div>
                        <div class="box-forms collapse show" id="box-personalizado-imoveis">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="gridPersonalizadoImoveis">
                                        <span id="emptyWidget2"> Adicione um novo widget personalizado </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a
                    href="javascript:void(0)"
                    id="addNewPersonalizadoImoveis"
                    >
                        Novo personalizado 2
                    </a>
                </div>
            </div>
            <div class="card" id="cardPersonalizado3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label> Personalizado 3 </label>
                        </div>
                        <div class="col-6">
                            <a
                            href="#box-personalizado-3"
                            class="btn btn-primary btn-sm float-end"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="true"
                            aria-controls="box-personalizado-3"
                            >
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                        </div>
                        <div class="box-forms collapse show" id="box-personalizado-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="">
                                        {{-- <span id="emptyWidget3"> Selecione o tipo (Referencia ou Equipamento) </span> --}}
                                        <label for="">Selecione o tipo</label>
                                        <select id="widget3_tipo" class="form-control">
                                            <option value="" class="d-none">Selecione</option>
                                            <option value="equipment">Equipamento</option>
                                            <option value="reference">Referência</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div id="gridPersonalizado3">
                                        <span id="emptyWidget3"> Nenhum widget encontrado </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ENDO WIDGETS --}}
    </div>
</form>
@endsection
