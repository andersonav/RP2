@extends('layouts.master')
@section('layout-modal')
    @hasSection('modal-form')
     <form id="@yield('modal-form')">
    @endif
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel"> @yield('modal-header') </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                @yield('modal-content')
            </div>
            <div class="modal-footer" style="display:flex; justify-content:space-between;">
                @hasSection('btnClose')
                    <div class="float-start">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"> @yield('btnClose') </button>
                    </div>
                @endif
                @hasSection('btnSubmit')
                    <button type="submit" class="btn btn-primary" id="btnSubmit"> @yield('btnSubmit') </button>
                @endif
            </div>
        </div><!-- /.modal-content -->
    @hasSection('modal-form')
    </form>
    @endif
@endsection
