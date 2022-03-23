@extends('layouts.master')
@section('title', 'Tipos de Laudos')
@section('page-title')
    <h4> Modelos de Laudo  </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item"> <a href="/tiposLaudos/index"> Tipos de Laudos </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Novo tipo de laudo </a> </li>
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
                                        <p> Adicionar Capa </p>
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
                    <form id="addTipoLaudo">
                    <input type="hidden" name="cod_storage" id="codStorage"/>

                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="addGeral" role="tabpanel">
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Nome do modelo </label>
                                            <input type="text" class="form-control" name="nome_modelo" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Descrição do modelo </label>
                                            <textarea class="form-control" name="descricao_modelo" rows="5"> </textarea>
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
                                            <textarea id="textAreaContent" name="data_html"> </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <a id="gerarCapa" class="btn btn-primary pull-right float-end" type="button">
                                            <i class="fa fa-arrow-right"> </i> Criar capa
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
                                            <textarea id="textAreaContentHeader" name="data_html_header"> </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <h3>Rodapé</h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box-content-laudo-small">
                                            <textarea id="textAreaContentFooter" name="data_html_footer"> </textarea>
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
                                    <div class="col-md-12">
                                        <button class="btn btn-primary addCapitulo" key="-1" type="button">
                                            <i class="fa fa-plus-square"> </i> Novo Capítulo
                                        </button>
                                    </div>
                                </div>
                                <div id="boxCaps" lastKey="0">
                                    <div class="row rowCaps" key="0" id="rowCaps">
                                        <div class="col-md-12">
                                            <div class="card border border-primary">
                                                <div class="card-header bg-transparent border-primary">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button
                                                            class="btn btn-danger btn-sm float-end removeCapitulo"
                                                            type="button"
                                                            style="display:none;"
                                                            >
                                                                <i class="fa fa-trash"> </i>
                                                            </button>

                                                            <div class="form-group">
                                                                <label class="text-primary">
                                                                    <h4 class="text-primary" style="display:inline-block;">
                                                                        Capítulo (nível 1)
                                                                    </h4>
                                                                </label>
                                                                <input type="text" class="form-control capName"
                                                                    name="capitulos[0][nome_capitulo]" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label> Texto Padrão </label>
                                                                <textarea
                                                                class="form-control capText"
                                                                name="capitulos[0][texto_padrao]"
                                                                rows="6"
                                                                ></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="card-body" id="boxSubCapitulos" key="0">
                                                    <div class="row form-row rowKey" rowKey="0" style="display: none;">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label> Subcapítulo (nível 2) </label>
                                                                <input type="text" class="form-control subCapName"
                                                                    name="capitulos[0][subcapitulos][0][nome_subcapitulo]" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <button type="button"
                                                                    class="btn btn-danger removeSubCap btn-sm mt-4"
                                                                    style="display:none;">
                                                                    <i class="fas fa-trash-alt"></i> Remover
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label> Texto Padrão </label>
                                                                <textarea
                                                                class="form-control subCapText"
                                                                name="capitulos[0][subcapitulos][0][texto_padrao]"
                                                                rows="6"
                                                                ></textarea>
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
                                                                                    href="#boxSubCapitulosN3_0_0"
                                                                                    role="button"

                                                                                >
                                                                                    <i class="fa fa-angle-down"> </i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="collapse n3Box" id="boxSubCapitulosN3_0_0">
                                                                        <div class="card-body containerN3" totalkey="0">
                                                                            <div class="boxN3 rowN3" key="0">
                                                                                <div class="row form-row">
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <label> Nome subcapitulo n3 </label>
                                                                                            <input
                                                                                            type="text"
                                                                                            class="form-control n3Name"
                                                                                            name="capitulos[0][subcapitulos][0][n3][0][nome_sub_subcapitulo]"
                                                                                            />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row form-row">
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <label> Texto padrão </label>
                                                                                            <textarea
                                                                                            class="form-control n3Text"
                                                                                            name="capitulos[0][subcapitulos][0][n3][0][texto_padrao]"
                                                                                            rows="6"
                                                                                            ></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row rowRemoveN3" style="display:none;">
                                                                                    <div class="col-md-12">
                                                                                        <a
                                                                                        class="text-danger btnRemoveN3"
                                                                                        href="#"
                                                                                        >
                                                                                            Remover
                                                                                        </a>
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
                                                </div>
                                                <div class="card-footer">
                                                    <a href="javascript:void(0)" class="float-end duplicateRowSubcaptiulo">
                                                        <i class="fa fa-plus-square"> </i> Adicionar subcapítulo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-row">
                                        <div class="col-md-6 d-flex justify-content-start">
                                            <button class="btn btn-primary addCapitulo" key="0" type="button">
                                                <i class="fa fa-plus-square"> </i> Novo Capítulo
                                            </button>
                                        </div>
                                    </div>
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
                        <h1 class="item-draggable cursorPointer tipography capituloText"> Título N1 </h2>
                        <h4 class="item-draggable cursorPointer tipography subcapituloText"> Subtítulo N2 </h4>
                        <h6 class="item-draggable cursorPointer tipography n3Text" style="font-weight: bold"> Subtítulo N3 </h6>
                        <p class="item-draggable cursorPointer tipography">  Parágrafo </p>
                    </div>
                </div>
            </div>
            <div class="card" id="cardImagens">
                <div class="card-body">
                    <div class="row form-row">
                        <div class="col-6">
                            <label> Imagens </label>
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
                    <hr />
                    <div class="box-forms collapse show" id="box-imagens">
                        <div class="row mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <label for="checkAllImages">
                                    <input id="checkAllImages" type="checkbox" class="form-check-input me-2">
                                    Selecionar todas imagens
                                </label>
                                <button type="button" class="addTipoLaudoImages btn btn-sm btn-primary d-none">Adicionar</button>
                            </div>
                        </div>
                        <div class="row" id="gridImagens"></div>
                        <p class="text-center"> Sem imagens </p>
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
