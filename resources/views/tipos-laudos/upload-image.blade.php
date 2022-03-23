@extends('layouts.modal')
@section('modal-form', 'uploadImageTiposLaudos')
@section('modal-header', 'Adicionar Nova Imagem')
@section('modal-content')
    <div class="dropzone" name="fileDropzone" id="upload-img-tipos-laudos"><div class="dz-message" data-dz-message><span>Arraste figuras e solte aqui</span></div> </div>
@endsection
@section('btnClose')
    Fechar
@endsection
@section('btnSubmit')
    Salvar
@endsection
