@extends('layouts.modal')
@section('modal-form', 'editFormModalTipoLaudo')
@section('modal-header', 'Editar Modelo Laudo')
@section('modal-content')
    <div class="row form-row tiposLaudosPage">
        <div class="col-md-6 d-flex justify-content-start">
            <button class="btn btn-primary addCapitulo" key="-1" type="button">
                <i class="fa fa-plus-square"> </i> Novo Capítulo
            </button>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"> Salvar </button>
        </div>
        {{-- <div class="col-md-6 d-flex justify-content-end">
            <button type="submit" class="btn btn-success" id="btnSaveTipoLaudo">
                <i class="far fa-save"></i> Salvar
            </button>
        </div> --}}
    </div>
    <div class="tiposLaudosPage" id="boxCaps" lastKey="{{ count($laudoModelo->laudoCapitulos) }}">
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
@endsection
@section('btnClose')
    Fechar
@endsection
@section('btnSubmit')
    Salvar
@endsection
