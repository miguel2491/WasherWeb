<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <a href="{{ route('home') }}">
                            <img alt="image" class="img-circle" width="48px" height="48px" src="{{ url('usuarios/fotos/'.Auth::user()->id) }}"/>
                        </a>
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong>
                         </span> <span class="text-muted text-xs block"><b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Perfil</a></li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">
                    W
                </div>
            </li>
            <!-- <li>
                <a href="{{ URL::to('configuracion') }}">
                    <i class="fa fa-gear" aria-hidden="true"></i> Configuración
                </a>
            </li> -->
            @php
              use App\Models\RolesUser;
              $idU = Auth::user()->id;
              $roles = RolesUser::where('id_user', $idU)->first();
            @endphp
            <input type="hidden" id="rol" value="{{ $roles->id_rol }}">
            @php
                if($roles->id_rol==1){
            @endphp
            <li {{{ (Request::is('usuario','washer','paquetes') ? 'class=active' : '') }}}>
                <a href="#"><i class="fa fa-book"></i> <span class="nav-label">Catálogos</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level" collapse {{{ (Request::is('usuario','washer','paquetes') ? 'in' : '') }}}">
                    <li>
                        <a href="{{ URL::to('usuario') }}">
                            <i class="fa fa-user" aria-hidden="true"></i> Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('washer') }}">
                            <i class="fa fa-male" aria-hidden="true"></i> Washers
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('paquetes') }}">
                            <i class="fa fa-box" aria-hidden="true"></i> Paquetes de Lavado
                        </a>
                    </li>
                </ul>
            </li>
            @php
                }
            @endphp
            <li {{{ (Request::is('pagos','solicitudes') ? 'class=active' : '') }}}>
                <a href="#"><i class="fa fa-compress-arrows-alt"></i> <span class="nav-label">Movimientos</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level" collapse {{{ (Request::is('pagos','solicitudes') ? 'in' : '') }}}">
                    <li>
                        <a href="{{ URL::to('pagos') }}">
                            <i class="fa fa-receipt" aria-hidden="true"></i> Pagos
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('solicitudes') }}">
                            <i class="fa fa-inbox" aria-hidden="true"></i> Solicitudes
                        </a>
                    </li>
                </ul>
            </li>
            <li {{{ (Request::is('bitacoras') ? 'class=active' : '') }}}>
                <a href="#"><i class="fa fa-history"></i> <span class="nav-label">Historial</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level" collapse {{{ (Request::is('bitacoras') ? 'in' : '') }}}">
                    <li>
                        <a href="{{ URL::to('bitacoras') }}">
                            <i class="fa fa-monument" aria-hidden="true"></i> Bitacoras
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>