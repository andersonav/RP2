@extends('layouts.modal')
@section('modal-form', 'createPersonalizado')
@section('modal-header', 'Novo personalizado')
@section('modal-content')
    <div class="row form-row">
        <div class="col-md-12">
            <div class="form-group">
                <label> Nome Personalizado </label>
                <input type="text" class="form-control" name="nome_personalizado" />
            </div>
        </div>
    </div>
    <div class="row form-row">
        <div class="col-md-8">
            <div class="form-group">
                <label> Tipo de construção </label>
                <input type="text" class="form-control" name="personalizado_tipo" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label> Número de unidades </label>
                <input type="number" name="personalizado_numero_unidades" class="form-control"/>
            </div>
        </div>
    </div>
    <div id="boxPavimentos">
        <div class="row form-row rowPavimentos" key="0" id="rowInitPavimento">
            <div class="col-md-12">
                <div class="form-group">
                    <label> Número de Pavimentos </label>
                    <input
                    type="text"
                    class="form-control numPavimentos"
                    name="personalizado_numero_pavimentos[0][nome_pavimento]"
                    placeholder="1 Pavimento - 4 Apartamentos Tipo"
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <a href="javascript:void(0)" id="newNumeroPavimentos"> <i class="fa fa-plus-square"> </i> </a>
        </div>
    </div>
@endsection
@section('btnClose')
    Fechar
@endsection
@section('btnSubmit')
    Salvar
@endsection
