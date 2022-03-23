@extends('layouts.modal')
@section('modal-form', 'createPersonalizadoImoveis')
@section('modal-header', 'Novo personalizado')
@section('modal-content')
    <div class="row form-row">
        <div class="col-md-12">
            <div class="form-group">
                <label> Nome Personalizado 2 </label>
                <input type="text" class="form-control" name="widget2_name" />
            </div>
        </div>
    </div>
    <div class="row form-row">
        <div class="col-md-4">
            <div class="form-group">
                <label> Quantidade de Imóveis </label>
                <input type="number" name="widget2_property_num" id="widget2_property_num" class="form-control" value="0" />
            </div>
        </div>
    </div>
    <div id="boxProperties">

    </div>
@endsection
@section('btnClose')
    Fechar
@endsection
@section('btnSubmit')
    Salvar
@endsection
