@if(auth()->check())
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">

            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">
                    <span>Clinique IA</span>
                </li>

                <!-- Dashboard Commun -->
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                    <!-- ==================== ADMIN ==================== -->
                    <li class="menu-title">Gestion</li>
                    <li>
                        <a href="#" class="waves-effect has-arrow">
                            <i class="ri-team-line"></i>
                            <span>Utilisateurs</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="#">Liste des utilisateurs</a></li>
                            <li><a href="#">Ajouter un utilisateur</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="waves-effect">
                            <i class="ri-user-heart-line"></i>
                            <span>Médecins</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect">
                            <i class="ri-bar-chart-box-line"></i>
                            <span>Statistiques</span>
                        </a>
                    </li>

                @elseif(auth()->user()->isSecretaire())
                    <!-- ==================== SECRÉTAIRE ==================== -->
                    <li class="menu-title">Opérations</li>
                    <li>
                        <a href="{{ route('secretaire.patients.index') }}" class="waves-effect">
                            <i class="ri-user-line"></i>
                            <span>Gestion des Patients</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="waves-effect">
                            <i class="ri-user-line"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="" class="waves-effect">
                            <i class="ri-calendar-check-line"></i>
                            <span>Rendez-vous</span>
                        </a>
                    </li>

                @elseif(auth()->user()->isMedecin())
                    <!-- ==================== MÉDECIN ==================== -->
                    <li class="menu-title">Mon Travail</li>
                    <li>
                        <a href="#" class="waves-effect">
                            <i class="ri-user-line"></i>
                            <span>Mes Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('medecin.rendez-vous.index') }}" class="waves-effect">
                            <i class="ri-calendar-event-line"></i>
                            <span>Mon Agenda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('medecin.consultations.index') }}" class="waves-effect">
                            <i class="ri-file-medical-line"></i>
                            <span>Mes Consultations</span>
                        </a>
                    </li>

                @elseif(auth()->user()->isPatient())
                    <!-- ==================== PATIENT / RESPONSABLE ==================== -->
                    <li class="menu-title">Mon Espace</li>

                    <!-- Mes Informations -->
                    <li>
                        <a href="{{ route('patient.dashboard') }}" class="waves-effect">
                            <i class="ri-dashboard-line"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>

                    <!-- Mes Rendez-vous -->
                    <li>
                        <a href="{{ route('patient.rendezvous') }}" class="waves-effect">
                            <i class="ri-calendar-check-line"></i>
                            <span>Mes Rendez-vous</span>
                        </a>
                    </li>

                    <!-- Mes Consultations -->
                    <li>
                        <a href="{{ route('patient.consultations') }}" class="waves-effect">
                            <i class="ri-file-medical-line"></i>
                            <span>Mes Consultations</span>
                        </a>
                    </li>

                    <!-- Mes Demandes -->
                    <li>
                        <a href="{{ route('patient.demandes.index') }}" class="waves-effect">
                            <i class="ri-file-list-3-line"></i>
                            <span>Mes Demandes</span>
                        </a>
                    </li>

                    <!-- Mon Dossier Médical -->
                    <li>
                        <a href="{{route('patient.dossier.index')}}" class="waves-effect">
                            <i class="ri-folder-user-line"></i>
                            <span>Mon Dossier Médical</span>
                        </a>
                    </li>

                @endif

                <!-- Menu Commun -->
                <li class="menu-title">Outils</li>
                <li>
                    <a href="#" class="waves-effect">
                        <i class="ri-chat-3-line"></i>
                        <span>Chat Intelligent IA</span>
                    </a>
                </li>

            </ul>

        </div>
    </div>
</div>
@endif
