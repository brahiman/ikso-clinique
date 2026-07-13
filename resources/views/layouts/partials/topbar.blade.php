@php use Illuminate\Support\Facades\Auth; @endphp
@if(auth()->check())
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm-dark.png') }}" alt="Clinique IA" height="26">
                    </span>
                        <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="Clinique IA" height="24">
                    </span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/IKSo-Logo-12.png') }}" alt="Clinique IA" height="26">
                    </span>
                        <span class="logo-lg">
                        <img src="{{ asset('assets/images/IKSo-Logo-12.png') }}" alt="Clinique IA" height="24">
                    </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect"
                        id="vertical-menu-btn">
                    <i class="ri-menu-2-line align-middle"></i>
                </button>

                <!-- Search -->
                <form class="app-search d-none d-lg-block" action="#">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Rechercher un patient, RDV...">
                        <span class="ri-search-line"></span>
                    </div>
                </form>
            </div>

            <div class="d-flex">
                <!-- Notifications -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon waves-effect"
                            id="page-header-notifications-dropdown" data-bs-toggle="dropdown">
                        <i class="ri-notification-3-line"></i>
                        <span class="noti-dot"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                        <!-- Notifications content ici (tu peux le personnaliser plus tard) -->
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0">Notifications</h6>
                                </div>
                            </div>
                        </div>
                        <!-- Ajoute tes notifications dynamiques ici -->
                    </div>
                </div>

                <!-- User Profile -->
                <div class="dropdown d-inline-block user-dropdown">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown">
                        <img class="rounded-circle header-profile-user"
                             src="{{ asset('assets/images/users/avatar-2.jpg') }}" alt="Avatar">
                        <span class="d-none d-xl-inline-block ms-1">{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{route('users.profil', Auth::user()->id)}}"><i
                                class="ri-user-line align-middle me-1"></i> Profil</a>
                        <a class="dropdown-item" href="{{ route('users.passwordChange') }}"><i
                                class="ri-key-fill align-middle me-1"></i> Mot de
                            passe</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ri-shut-down-line align-middle me-1 text-danger"></i> Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

                <!-- Settings -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                        <i class="ri-settings-2-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
@endif
