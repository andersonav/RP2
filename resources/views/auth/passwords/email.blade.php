@extends('layouts.master-without-nav')
@section('title')
    Redefinir senha
@endsection
@section('content')

    <div class="home-btn d-none d-sm-block">
        <a href="{{ url('index') }}" class="text-dark"><i class="mdi mdi-home-variant h2"></i></a>
    </div>
    <div class="account-pages my-5  pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div>
                        <a href="{{ url('index') }}" class="mb-5 d-block auth-logo">
                            <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="22"
                                class="logo logo-dark">
                            <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="22"
                                class="logo logo-light">
                        </a>
                        <div class="card">

                            <div class="card-body p-4">

                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Redefinir senha</h5>
                                    <p class="text-muted">Redefinir senha para RP2.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    @if (session('status'))
                                        <div class="alert alert-success mb-4" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" id="email"
                                                placeholder="Digite seu e-mail">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light"
                                                type="submit">Redefinir</button>
                                        </div>


                                        <div class="mt-4 text-center">
                                            <p class="mb-0">Lembrou? <a href="{{ url('login') }}"
                                                    class="fw-medium text-primary"> Entrar </a></p>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <p>©
                                <script>
                                    document.write(new Date().getFullYear())

                                </script>
                                RP2. <br/> Desenvolvido com <i class="mdi mdi-heart text-danger"></i> por DC Tecnologia
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
@endsection
