@extends('layouts.modal')
@section('modal-form', 'createLaudoFiguras')
@section('modal-header', 'Adicionar pacote de figuras')
@section('modal-content')
    <div class="dropzone" name="fileDropzone" id="upload-figuras-laudo"><div class="dz-message" data-dz-message><span>Arraste figuras e solte aqui</span></div> </div>
@endsection
@section('btnClose')
    Fechar
@endsection
@section('btnSubmit')
    Salvar
@endsection
