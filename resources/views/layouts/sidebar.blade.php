<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{url('index')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="20">
            </span>
        </a>

        <a href="{{url('index')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="20">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title"> Links rápidos </li>
                <li>
                    <a href="/">
                        <i class="uil-home-alt"></i>
                        <span> Início </span>
                    </a>
                </li>
                <li>
                    <a href="/laudos/index">
                        <i class="uil-books"></i>
                        <span> Laudos </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="uil-sitemap">  </i> Modelos
                    </a>
                    <ul class="sub-menu nm-collapse" aria-expanded="true">
                        <li> <a href="/tiposLaudos/index"> Laudos </a> </li>
                        <li> <a href="/tiposEquipamentos/index"> Equipamentos/Referências </a> </li>
                    </ul>
                </li>
                <li>
                    <a href="/users/index">
                        <i class="uil-user"> </i>
                        <span> Usuários </span>
                    </a>
                </li>
                <li>
                    <a href="/clientes/index">
                        <i class="uil-users-alt"> </i>
                        <span> Clientes </span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
