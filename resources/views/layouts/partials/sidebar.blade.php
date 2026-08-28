<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{ route('dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <div class="sb-sidenav-menu-heading">Gestión</div>
            <!-- TODO: cambiar # por route() cuando I2/I3 tengan sus rutas listas (Sprint 2) -->
            <a class="nav-link" href="#">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Clientes
            </a>
            <a class="nav-link" href="#">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Contadores
            </a>
            <a class="nav-link" href="#">
                <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                Tarifas
            </a>
            <a class="nav-link" href="#">
                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                Lecturas
            </a>
            <a class="nav-link" href="#">
                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                Pagos
            </a>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ auth()->user()->name ?? 'Invitado' }}
    </div>
</nav>
