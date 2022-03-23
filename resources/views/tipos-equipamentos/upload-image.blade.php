@extends('layouts.modal')
@section('modal-form', 'uploadImageTiposEquipamentos')
@section('modal-header', 'Adicionar Nova Imagem')
@section('modal-content')
    <div class="dropzone" name="fileDropzone" id="uploadImgTiposEquipamento"> </div>
@endsection
@section('btnClose')
    Fechar
@endsection
@section('btnSubmit')
    Salvar
@endsection
