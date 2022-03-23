@extends('layouts.master')
@section('title', 'Tipos de Laudos')
@section('page-title')
    <h4> Alterar Tipo de Laudos </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item"> <a href="/tiposLaudos/index"> Tipos de Laudos </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Editar tipo de laudo </a> </li>
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

        #box-imagens .checkSelectTipoLaudoImages {
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
</style>
    <input type="hidden" id="idTipoLaudo" value="{{ $laudoModelo->id }}" />
    <div class="row tiposLaudosPage">
        {{-- EDITOR / PRINCIPAL CONTENT --}}
        <div class="col-md-8">
            <div class="card" id="contentEditor">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a id="btnInfoTipoLaudo" class="nav-link active" data-bs-toggle="tab" href="#addGeral"
                                        role="tab" aria-selected="true">
                                        <p> Geral </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="btnCriarCapaTab" class="nav-link " data-bs-toggle="tab" href="#addCapa"
                                        role="tab" aria-selected="false">
                                        <p> Capa </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="btnCriarHeaderFooterTab" class="nav-link " data-bs-toggle="tab" href="#addHeaderFooter"
                                        role="tab" aria-selected="false">
                                        <p> Adicionar Cabeçalho / Rodapé </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="btnCaptiulosTab" class="nav-link" data-bs-toggle="tab" href="#addCapitulos"
                                        role="tab" aria-selected="false">
                                        <p> Capitulos </p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <form id="editTipoLaudo">
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="addGeral" role="tabpanel">
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Nome do modelo </label>
                                            <input type="text" class="form-control" name="nome_modelo"
                                                value="{{ $laudoModelo->nome_modelo }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Descrição do modelo </label>
                                            <textarea
                                             type="text"
                                             class="form-control"
                                             name="descricao_modelo"
                                             rows="5"
                                            >{{ $laudoModelo->descricao_modelo}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary float-end" id="avanceStep">
                                            <i class="fa fa-arrow-right"> </i> Avançar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="addCapa" role="tabpanel">
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="box-content-laudo">
                                            <textarea id="textAreaContent" name="data_html">
                                                {{str_replace('../storage/','../../storage/', $laudoModelo->data_html)}}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <a id="gerarCapa" class="btn btn-primary pull-right float-end" type="button">
                                            <i class="fa fa-arrow-right"> </i> Alterar capa
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="addHeaderFooter" role="tabpanel">
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <h3>Cabeçalho</h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box-content-laudo-small">
                                            <textarea id="textAreaContentHeader" name="data_html_header">
                                                {{str_replace('../storage/','../../storage/', $laudoModelo->data_html_header)}}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <h3>Rodapé</h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box-content-laudo-small">
                                            <textarea id="textAreaContentFooter" name="data_html_footer">
                                                {{str_replace('../storage/','../../storage/', $laudoModelo->data_html_footer)}}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <a id="gerarHeaderFooter" class="btn btn-primary pull-right float-end" type="button">
                                            <i class="fa fa-arrow-right"> </i> Avançar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="addCapitulos" role="tabpanel">
                                <div class="row form-row">
                                    <div class="col-md-6 d-flex justify-content-start">
                                        <button class="btn btn-primary addCapitulo" key="-1" type="button">
                                            <i class="fa fa-plus-square"> </i> Novo Capítulo
                                        </button>
                                    </div>
                                    {{-- <div class="col-md-6 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success" id="btnSaveTipoLaudo">
                                            <i class="far fa-save"></i> Salvar
                                        </button>
                                    </div> --}}
                                </div>
                                <div id="boxCaps" lastKey="{{ count($laudoModelo->laudoCapitulos) }}">
                                    @foreach ($laudoModelo->laudoCapitulos as $k => $v)
                                        <div class="row rowCaps" key="{{ $v['position'] }}" id="rowCaps">
                                            <div class="col-md-12">
                                                <div class="card border border-primary">
                                                    <div class="card-header bg-transparent border-primary">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button
                                                                class="btn btn-danger btn-sm float-end deleteCapitulo"
                                                                type="button"
                                                                style="{{ $k > 0 ? "display:block;" : "display:none;" }}"
                                                                id="{{$v['id']}}"
                                                                >
                                                                    <i class="fa fa-trash"> </i>
                                                                </button>

                                                                <div class="form-group">
                                                                    <label class="text--primary">
                                                                        <h4 class="text-primary">
                                                                            Capítulo (nível 1)
                                                                        </h4>
                                                                    </label>
                                                                    <input type="hidden" class="capId"
                                                                        name="capitulos[{{ $k }}][id]"
                                                                        value="{{ $v->id }}" />
                                                                    <input type="text" class="form-control capName"
                                                                        name="capitulos[{{ $k }}][nome_capitulo]"
                                                                        value="{{ $v->nome_capitulo }}" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label> Texto Padrão </label>
                                                                    <textarea
                                                                    class="form-control capText"
                                                                    name="capitulos[{{ $k }}][texto_padrao]"
                                                                    rows="6"
                                                                    >{{ $v->texto_padrao ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div class="card-body" id="boxSubCapitulos" key="{{ count($v->laudoModeloSubcapitulos) }}">
                                                        @if ($v->laudoModeloSubcapitulos->count() > 0)
                                                            @foreach($v->laudoModeloSubcapitulos as $key => $value)
                                                                <div class="row rowKey" @if($key > 0) id="cloneRow" @endif rowKey="{{$key}}">
                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            <label> Subcapítulo (nível 2) </label>
                                                                            <input type="hidden" class="subCapId"
                                                                                name="capitulos[{{ $k }}][subcapitulos][{{ $key }}][id]"
                                                                                value="{{ $value->id }}" />
                                                                            <input type="text" class="form-control subCapName"
                                                                                name="capitulos[{{ $k }}][subcapitulos][{{ $key }}][nome_subcapitulo]"
                                                                                value="{{ $value->nome_subcapitulo }}" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            <label> Texto Padrão </label>
                                                                            <textarea
                                                                            class="form-control subCapText"
                                                                            name="capitulos[{{ $k }}][subcapitulos][{{ $key }}][texto_padrao]"
                                                                            rows="6"
                                                                            >{{$value->texto_padrao}}
                                                                            </textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <button type="button"
                                                                                class="btn btn-danger deleteSubCap btn-sm mt-4"
                                                                                style="{{ $key > 0 ? 'display:block;' : 'display:none;' }}"
                                                                                id="{{ $value->id }}">
                                                                                <i class="fas fa-trash-alt"></i> Remover
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" id="subCapsN3">
                                                                        <div class="col-md-12">
                                                                            <div class="card ms-2 mt-4">
                                                                                <div class="card-header">
                                                                                    <div class="row form-row">
                                                                                        <div class="col-md-6">
                                                                                            <p class="card-title"> Subcapítulos (nível 3) </p>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <button
                                                                                                type="button"
                                                                                                class="btn btn-primary float-end btn-sm n3Button"
                                                                                                data-bs-toggle="collapse"
                                                                                                href="#boxSubCapitulosN3_{{$k}}_{{$key}}"
                                                                                                role="button"
                                                                                            >
                                                                                                <i class="fa fa-angle-down"> </i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                class="collapse @if(count($value->subCapsN3) > 0) show @endif n3Box"
                                                                                id="boxSubCapitulosN3_{{$k}}_{{$key}}"
                                                                                >
                                                                                    <div class="card-body containerN3" totalkey={{ count($value->subCapsN3) }}>
                                                                                        @if(count($value->subCapsN3) > 0)
                                                                                            @foreach($value->subCapsN3 as $i => $j)
                                                                                                <div class="rowN3 @if($i == 0) boxN3 @else boxN3Clone @endif" key="{{$i}}">
                                                                                                    <div
                                                                                                    class="row form-row @if($i > 0) cloneRow @endif"
                                                                                                    key="{{$i}}"
                                                                                                    >
                                                                                                        <div class="col-12">
                                                                                                            <input
                                                                                                                type="hidden"
                                                                                                                class="n3Id"
                                                                                                                name="capitulos[{{$k}}][subcapitulos][{{$key}}][n3][{{$i}}][id]"
                                                                                                                value="{{$j->id}}"
                                                                                                            />
                                                                                                            <div class="form-group">
                                                                                                                <label> Nome subcapitulo n3 </label>
                                                                                                                <input
                                                                                                                type="text"
                                                                                                                class="form-control n3Name"
                                                                                                                name="capitulos[{{$k}}][subcapitulos][{{$key}}][n3][{{$i}}][nome_sub_subcapitulo]"
                                                                                                                value="{{$j->nome_sub_subcapitulo}}"
                                                                                                                />
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-12">
                                                                                                            <div class="form-group">
                                                                                                                <label> Texto padrão </label>
                                                                                                                <textarea
                                                                                                                class="form-control n3Text"
                                                                                                                name="capitulos[{{$k}}][subcapitulos][{{$key}}][n3][{{$i}}][texto_padrao]"
                                                                                                                rows="6"
                                                                                                                >{{$j->texto_padrao}}</textarea>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div
                                                                                                        class="rowRemoveN3"
                                                                                                        style="@if($i > 0) display:block; @else display:none; @endif"
                                                                                                        >
                                                                                                            <div class="col-md-12">
                                                                                                                <a
                                                                                                                class="text-danger deleteRemoveN3"
                                                                                                                href="javascript:void(0)"
                                                                                                                id="{{$j->id}}"
                                                                                                                >
                                                                                                                    Remover
                                                                                                                </a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endforeach
                                                                                        @else
                                                                                            <div class="row rowN3 boxN3 form-row" key="0">
                                                                                                <div class="col-12">
                                                                                                    <div class="form-group">
                                                                                                        <label> Nome subcapitulo n3 </label>
                                                                                                        <input
                                                                                                        type="text"
                                                                                                        class="form-control n3Name"
                                                                                                        name="capitulos[{{$k}}][subcapitulos][{{$key}}][n3][0][nome_sub_subcapitulo]"
                                                                                                        />
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-12">
                                                                                                    <div class="form-group">
                                                                                                        <label> Texto padrão </label>
                                                                                                        <textarea
                                                                                                        class="form-control n3Text"
                                                                                                        name="capitulos[{{$k}}][subcapitulos][{{$key}}][n3][0][texto_padrao]"
                                                                                                        rows="6"
                                                                                                        ></textarea>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="row footerN3">
                                                                                        <div class="col-12">
                                                                                            <a
                                                                                            href="javascript:void(0)"
                                                                                            class="float-end addSubCapN3"
                                                                                            >
                                                                                                Adicionar novo subcapitulo n3
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            @php
                                                                $key = 0;
                                                            @endphp
                                                            <div class="row rowKey" @if($key > 0) id="cloneRow" @endif rowKey="{{$key}}" style="display: none;">
                                                                <input type="hidden" class="subCapClone"
                                                                            name="capitulos[{{ $k }}][subcapitulos][{{ $key }}][clone]"
                                                                            value="1"/>
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label> Subcapítulo (nível 2) </label>
                                                                        <input type="hidden" class="subCapId"
                                                                            name="capitulos[{{ $k }}][subcapitulos][{{ $key }}][id]" />
                                                                        <input type="text" class="form-control subCapName"
                                                                            name="capitulos[{{ $k }}][subcapitulos][{{ $key }}][nome_subcapitulo]" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label> Texto Padrão </label>
                                                                        <textarea
                                                                        class="form-control subCapText"
                                                                        name="capitulos[{{ $k }}][subcapitulos][{{ $key }}][texto_padrao]"
                                                                        rows="6"
                                                                        >
                                                                        </textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <button type="button"
                                                                            class="btn btn-danger deleteSubCap btn-sm mt-4"
                                                                            style="{{ $key > 0 ? 'display:block;' : 'display:none;' }}">
                                                                            <i class="fas fa-trash-alt"></i> Remover
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="row" id="subCapsN3">
                                                                    <div class="col-md-12">
                                                                        <div class="card ms-2 mt-4">
                                                                            <div class="card-header">
                                                                                <div class="row form-row">
                                                                                    <div class="col-md-6">
                                                                                        <p class="card-title"> Subcapítulos (nível 3) </p>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <button
                                                                                            type="button"
                                                                                            class="btn btn-primary float-end btn-sm n3Button"
                                                                                            data-bs-toggle="collapse"
                                                                                            href="#boxSubCapitulosN3_{{$k}}_{{$key}}"
                                                                                            role="button"
                                                                                        >
                                                                                            <i class="fa fa-angle-down"> </i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                            class="collapse n3Box"
                                                                            id="boxSubCapitulosN3_{{$k}}_{{$key}}"
                                                                            >
                                                                                <div class="card-body containerN3" totalkey={{ 0 }}>

                                                                                    <div class="row rowN3 boxN3 form-row" key="0">
                                                                                        <div class="col-12">
                                                                                            <div class="form-group">
                                                                                                <label> Nome subcapitulo n3 </label>
                                                                                                <input
                                                                                                type="text"
                                                                                                class="form-control n3Name"
                                                                                                name="capitulos[{{$k}}][subcapitulos][{{$key}}][n3][0][nome_sub_subcapitulo]"
                                                                                                />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <div class="form-group">
                                                                                                <label> Texto padrão </label>
                                                                                                <textarea
                                                                                                class="form-control n3Text"
                                                                                                name="capitulos[{{$k}}][subcapitulos][{{$key}}][n3][0][texto_padrao]"
                                                                                                rows="6"
                                                                                                ></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row footerN3">
                                                                                    <div class="col-12">
                                                                                        <a
                                                                                        href="javascript:void(0)"
                                                                                        class="float-end addSubCapN3"
                                                                                        >
                                                                                            Adicionar novo subcapitulo n3
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="card-footer">
                                                        <a href="javascript:void(0)"
                                                            class="float-end duplicateRowSubcaptiulo">
                                                            <i class="fa fa-plus-square"> </i> Adicionar subcapítulo
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-row">
                                            <div class="col-md-6 d-flex justify-content-start">
                                                <button class="btn btn-primary addCapitulo" key="{{ $v['position'] }}" type="button">
                                                    <i class="fa fa-plus-square"> </i> Novo Capítulo
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- END PRINCIPAL CONTENT --}}

        {{-- INIT SIDEBAR TOOLS --}}
        <div class="col-md-4">
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
                    <div class="box-forms collapse show" id="box-formas">
                        <h1 class="item-draggable cursorPointer tipography capituloText"> Título N1 </h1>
                        <h4 class="item-draggable cursorPointer tipography subcapituloText"> Subtítulo N2 </h4>
                        <h6 class="item-draggable cursorPointer tipography n3Text" style="font-weight: bold"> Subtítulo N3 </h6>
                        <p class="item-draggable cursorPointer tipography">  Parágrafo </p>
                    </div>
                </div>
            </div>
            <div class="card" id="cardImagens">
                <input type="hidden" name="id_tipolaudo" id="idTipoLaudo" value="{{ $laudoModelo->id }}"/>
                <input type="hidden" name="cod_storage" id="cod_storage" value="{{ $laudoModelo->cod_storage_tipo_laudo }}"/>

                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label> Imagens </label>
                        </div>
                        <div class="col-6">
                            <a href="#box-imagens" class="btn btn-primary btn-sm float-end" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="box-imagens">
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
                    <hr />
                    <div class="box-forms collapse show" id="box-imagens">
                        @if (count($dataFilesTMP) > 0)
                            <div class="row mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="checkAllTipoLaudoImages">
                                        <input id="checkAllTipoLaudoImages" type="checkbox" class="form-check-input me-2">
                                        Selecionar todas imagens
                                    </label>
                                    <button type="button" class="addTipoLaudoImages btn btn-sm btn-primary d-none">Adicionar</button>
                                </div>
                            </div>

                            <div class="row" id="gridImagens">
                                @foreach ($dataFilesTMP as $index => $value)
                                    <div class="col-md-6">
                                        <label for="check{{ $index }}" style="cursor: pointer; position: relative">
                                            <input id="check{{ $index }}" type="checkbox" class="form-check-input checkSelectTipoLaudoImages m-0" data-index="{{ $index }}">
                                            <img
                                            id="image{{ $index }}"
                                            src="{{$value}}"
                                            alt="laudo figure"
                                            class="item-draggable pictures-laudo"
                                            style="padding:2%;"
                                            >
                                            <a href="javascript:void(0)" id="{{$value}}" class="btnRemoveImg" style="color:red; font-size: 20px">
                                                <i class="bx bxs-trash"></i>
                                            </a>
                                        </label>

                                    </div>
                                @endforeach
                            </div>
                            <hr />
                        @else
                            <p class="text-center"> Sem imagens </p>
                        @endif
                        <a href="javascript:void(0)" id="uploadImageLaudo">
                            Adicionar Nova Imagem
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- END SIDEBAR TOOLS --}}
    </div>
    <div class="card fixed-bottom my-0 mb-5 mr-0" id="btnSaveTipoLaudoWrapper" style="width: 200px; left: unset!important; display: none;">
        <div class="card-body">
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="form-control btn btn-success" id="btnSaveTipoLaudo">
                    <i class="far fa-save"></i> Salvar
                </button>
            </div>
        </div>
    </div>
@endsection
