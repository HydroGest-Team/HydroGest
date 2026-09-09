<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            @php $rol = auth()->user()->role->nombre_rol; @endphp

            @if (in_array($rol, ['Administrador', 'Secretaria', 'Empleado']))
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{ route('dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            @endif

            <div class="sb-sidenav-menu-heading">Gestión</div>

            @if (in_array($rol, ['Administrador', 'Secretaria']))
            <a class="nav-link" href="{{ route('clientes.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Clientes
            </a>
            @endif

            @if (in_array($rol, ['Administrador', 'Secretaria', 'Empleado']))
            <a class="nav-link" href="{{ route('contadores.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Contadores
            </a>
            @endif

            @if (in_array($rol, ['Administrador', 'Empleado']))
            <a class="nav-link" href="{{ route('tarifas.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                Tarifas
            </a>
            @endif

            @if (in_array($rol, ['Administrador', 'Secretaria', 'Empleado']))
            <a class="nav-link" href="{{ route('lecturas.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                Lecturas
            </a>
            @endif

            @if (in_array($rol, ['Administrador', 'Secretaria']))
            <a class="nav-link" href="{{ route('pagos.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                Pagos
            </a>
            @endif
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ auth()->user()->name ?? 'Invitado' }}
    </div>
</nav>