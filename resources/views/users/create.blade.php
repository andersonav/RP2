@extends('layouts.modal')
@section('modal-form', 'addFormUsuario')
@section('modal-header', 'Adicionar Usuário')
@section('modal-content')
    <div class="row form-row">
        <div class="col-md-3">
            <div class="form-group">
                <label> Nome <span class="required-label"> * </span> </label>
                <input type="text" name="name" class="form-control" />
               
                <div class="error_feedback"> </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label> Sobrenome <span class="required-label"> * </span> </label>
                <input type="text" name="last_name" class="form-control" />

                <div class="error_feedback"> </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label> Data Nascimento <span class="required-label"> * </label> </label>    
                <input type="date" name="data_nascimento" class="form-control"/>                
                
                <div class="error_feedback"> </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label> Ativo  <span class="required-label"> * </span>  </label>
                <select name="active" class="form-select select2">
                    <option value="y"> Sim </option>
                    <option value="n"> Não </option>
                </select>
                 
                <div class="error_feedback"> </div>
            </div>
        </div>
    </div>
    <div class="row form-row">
        <div class="col-md-6">
            <div class="form-group">
                <label> E-mail <span class="required-label"> * </span> </label>
                <input type="text" name="email" class="form-control" />

                <div class="error_feedback"> </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label> Telefone (Celular) <span class="required-label"> * </label> </label>
                <input type="text" name="cell" class="form-control phone" />
            </div>
        </div>
    </div> 
    <div class="row form-row">
        <div class="col-md-6">
            <div class="form-group">
                <label> Senha <span class="required-label"> * </span> </label>
                <input type="password" name="password" class="form-control" />

                <div class="error_feedback"> </div>
            </div>
        </div>
        <div class="col-md-6">
            <label> Confirmação de Senha <span class="required-label"> * </span> </label>
            <input type="password" name="password_confirmation" class="form-control" />

            <div class="error_feedback"> </div>
        </div>
    </div>
    <div class="row form-row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Foto</label>
                <input type="file" name="photo" class="form-control">

                <div class="error_feedback"> </div>
            </div>
        </div>
    </div>
@endsection
@section('btnClose')
    Fechar 
@endsection
@section('btnSubmit')   
    Salvar
@endsection


