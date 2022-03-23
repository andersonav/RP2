@extends('layouts.master')
@section('title', 'Tipos de Equipamentos')
@section('page-title')
    <h4> Equipamentos/Referências  </h4>
@endsection
@section('page-title-right')
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"> <a href="#"> Início </a> </li>
        <li class="breadcrumb-item"> <a href="/tiposEquipamentos/index"> Equipamentos/Referências </a> </li>
        <li class="breadcrumb-item" class="active"> <a href="#"> Novo Equipamento/Referência </a> </li>
    </ol>
@endsection
@section('content')
<style>
    #cardTipografia .cursorPointer {
        cursor: pointer;
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
</style>
    <div class="row tiposEquipamentosPage">
        {{-- EDITOR / PRINCIPAL CONTENT --}}
        <div class="col-md-8">
            <div class="card" id="contentEditor">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a id="btnInfoTipoEquipamento" class="nav-link active" data-bs-toggle="tab" href="#addGeral"
                                        role="tab" aria-selected="true">
                                        <p> Geral </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="btnCriarConteudoTab" class="nav-link " data-bs-toggle="tab" href="#addConteudo"
                                        role="tab" aria-selected="false">
                                        <p> Adicionar Conteudo </p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <form id="addTipoEquipamento">
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="addGeral" role="tabpanel">
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Tipo </label>
                                            <select class="form-control" name="tipo">
                                                <option value="equipment">Equipamento</option>
                                                <option value="reference">Referência</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Nome </label>
                                            <input type="text" class="form-control" name="nome_modelo" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Descrição </label>
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
                            <div class="tab-pane" id="addConteudo" role="tabpanel">
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="box-content-laudo">
                                            <textarea id="textAreaContent" name="data_html"> </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success" style="width:100%;"
                                            id="">
                                            <i class="far fa-save"></i> Salvar Equipamento
                                        </button>
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
                        <a id="uploadImageEquipamentos" href="javascript:void(0)">  Carregar figuras </a>
                        <button type="button" class="addLaudoImages btn btn-sm btn-primary d-none">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- END SIDEBAR TOOLS --}}
    </div>
@endsection
