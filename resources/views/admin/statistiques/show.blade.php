@extends('layouts.master')

@section('title', 'Clinique IA - Statistiques détaillées')

@section('styles')
    <style>
        .chart-card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            margin-bottom: 1.5rem;
        }

        .chart-card .card-header {
            background: transparent;
            border-bottom: 1px solid #eef0f2;
            font-weight: 600;
        }

        .chart-wrap {
            position: relative;
            height: 280px;
        }

        .chart-wrap.sm {
            height: 220px;
        }

        .mini-kpi {
            text-align: center;
            padding: 1rem;
        }

        .mini-kpi .value {
            font-size: 2rem;
            font-weight: 700;
            color: #556ee6;
        }

        .mini-kpi .label {
            color: #74788d;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        #section-nav .nav-link {
            cursor: pointer;
        }

        .btn-export {
            border: none;
            background: transparent;
            color: #74788d;
            padding: 2px 6px;
            border-radius: .3rem;
            font-size: .85rem;
        }

        .btn-export:hover {
            background: #eef0f2;
            color: #34c38f;
        }
    </style>
@endsection

@section('content')

    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-0">Statistiques détaillées</h4>
                <p class="text-muted mb-0">Analyse approfondie par catégorie</p>
            </div>
            <a href="{{ url('/admin/statistiques') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left mr-1"></i> Retour au tableau de bord
            </a>
        </div>
    </div>

    <div class="card chart-card">
        <div class="card-body">
            <ul class="nav nav-pills flex-wrap" id="section-nav">
                <li class="nav-item mr-2 mb-2"><a class="nav-link active" data-section="activite">Activité &amp;
                        flux</a></li>
                <li class="nav-item mr-2 mb-2"><a class="nav-link" data-section="demandes">Demandes</a></li>
                <li class="nav-item mr-2 mb-2"><a class="nav-link" data-section="demographie">Démographie</a></li>
                <li class="nav-item mr-2 mb-2"><a class="nav-link" data-section="medecins">Médecins</a></li>
                <li class="nav-item mr-2 mb-2"><a class="nav-link" data-section="examens">Examens</a></li>
                <li class="nav-item mr-2 mb-2"><a class="nav-link" data-section="prescriptions">Prescriptions</a></li>
                <li class="nav-item mr-2 mb-2"><a class="nav-link" data-section="antecedents">Antécédents</a></li>
            </ul>
        </div>
    </div>

    {{-- ================= 1. ACTIVITÉ & FLUX ================= --}}
    <div class="stat-section" id="section-activite">
        <div class="row">
            <div class="col-xl-8 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Consultations par période</span>
                        <div class="d-flex align-items-center">
                            <select id="periode-select" class="form-control form-control-sm mr-2" style="width:auto;">
                                <option value="jour">Jour</option>
                                <option value="semaine">Semaine</option>
                                <option value="mois" selected>Mois</option>
                            </select>
                            <button type="button" class="btn-export" data-export="consultations-par-periode"
                                    title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="chart-consultations-periode"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Urgentes vs normales</span>
                        <button type="button" class="btn-export" data-export="taux-consultations-urgentes"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-urgence"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Statut des consultations</span>
                        <button type="button" class="btn-export" data-export="statut-consultations"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-statut-consultations"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Statut des rendez-vous</span>
                        <button type="button" class="btn-export" data-export="statut-rendez-vous"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-statut-rdv"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Taux d'annulation / report</span>
                        <button type="button" class="btn-export" data-export="taux-no-show" title="Exporter en Excel"><i
                                class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="form-row mb-2">
                            <div class="col"><input type="date" id="noshow-debut" class="form-control form-control-sm">
                            </div>
                            <div class="col"><input type="date" id="noshow-fin" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="mini-kpi p-0">
                            <div class="value" id="noshow-taux">--%</div>
                            <div class="label" id="noshow-detail">annulés/reportés sur le total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= 2. DEMANDES ================= --}}
    <div class="stat-section d-none" id="section-demandes">
        <div class="row">
            <div class="col-xl-6 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Entonnoir des demandes de consultation</span>
                        <button type="button" class="btn-export" data-export="entonnoir-demandes-consultations"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="chart-entonnoir"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Demandes par service souhaité</span>
                        <button type="button" class="btn-export" data-export="demandes-par-service"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="chart-demandes-service"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Délai moyen d'affectation</span>
                        <button type="button" class="btn-export" data-export="delai-moyen-affectation"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="mini-kpi">
                            <div class="value" id="delai-affectation">--</div>
                            <div class="label">heures en moyenne</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Demandes par niveau d'urgence</span>
                        <button type="button" class="btn-export" data-export="demandes-par-urgence"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-demandes-urgence"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= 3. DÉMOGRAPHIE ================= --}}
    <div class="stat-section d-none" id="section-demographie">
        <div class="row">
            <div class="col-xl-8 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Pyramide des âges (par sexe)</span>
                        <button type="button" class="btn-export" data-export="pyramide-ages" title="Exporter en Excel">
                            <i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="chart-pyramide-ages"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Répartition par sexe</span>
                        <button type="button" class="btn-export" data-export="repartition-sexe"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="chart-sexe"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Répartition par groupe sanguin</span>
                        <button type="button" class="btn-export" data-export="repartition-groupe-sanguin"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-groupe-sanguin"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= 4. MÉDECINS ================= --}}
    <div class="stat-section d-none" id="section-medecins">
        <div class="row">
            <div class="col-xl-8 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Charge de travail par médecin</span>
                        <button type="button" class="btn-export" data-export="charge-travail-medecins"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0" id="table-medecins">
                                <thead>
                                <tr>
                                    <th>Médecin</th>
                                    <th class="text-center">Patients</th>
                                    <th class="text-center">Consultations</th>
                                    <th class="text-center">Rendez-vous</th>
                                    <th class="text-center">Ordonnances</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chargement…</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Statut des médecins</span>
                        <button type="button" class="btn-export" data-export="statut-medecins"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-statut-medecins"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Répartition par spécialité</span>
                        <button type="button" class="btn-export" data-export="repartition-medecins-par-specialite"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="chart-specialites"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= 5. EXAMENS ================= --}}
    <div class="stat-section d-none" id="section-examens">
        <div class="row">
            <div class="col-xl-8 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Types d'examens les plus demandés</span>
                        <button type="button" class="btn-export" data-export="types-examens-plus-demandes"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="chart-types-examens"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Délai moyen de traitement</span>
                        <button type="button" class="btn-export" data-export="delai-moyen-examens"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="mini-kpi">
                            <div class="value" id="delai-examens">--</div>
                            <div class="label">jours en moyenne</div>
                        </div>
                    </div>
                </div>
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Statut des demandes d'examens</span>
                        <button type="button" class="btn-export" data-export="statut-examens" title="Exporter en Excel">
                            <i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-statut-examens"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= 6. PRESCRIPTIONS ================= --}}
    <div class="stat-section d-none" id="section-prescriptions">
        <div class="row">
            <div class="col-xl-8 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Médicaments les plus prescrits</span>
                        <div class="d-flex align-items-center">
                            <select id="medicaments-limite" class="form-control form-control-sm mr-2"
                                    style="width:auto;">
                                <option value="5">Top 5</option>
                                <option value="10" selected>Top 10</option>
                                <option value="20">Top 20</option>
                            </select>
                            <button type="button" class="btn-export" data-export="medicaments-plus-prescrits"
                                    title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="chart-medicaments"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Moyenne / ordonnance</span>
                        <button type="button" class="btn-export" data-export="nombre-moyen-medicaments-par-ordonnance"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="mini-kpi">
                            <div class="value" id="moyenne-medicaments">--</div>
                            <div class="label">médicaments par ordonnance</div>
                        </div>
                    </div>
                </div>
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Répartition par forme galénique</span>
                        <button type="button" class="btn-export" data-export="repartition-forme-medicaments"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-forme-medicaments"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= 7. ANTÉCÉDENTS ================= --}}
    <div class="stat-section d-none" id="section-antecedents">
        <div class="row">
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Répartition par type</span>
                        <button type="button" class="btn-export" data-export="antecedents-par-type"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-antecedents-type"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Répartition par gravité</span>
                        <button type="button" class="btn-export" data-export="antecedents-par-gravite"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-antecedents-gravite"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Pathologies / allergies fréquentes</span>
                        <button type="button" class="btn-export" data-export="pathologies-frequentes"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap sm">
                            <canvas id="chart-pathologies"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ⚠️ Adaptez ce préfixe si vos routes JSON ont un chemin différent.
            const basePath = "{{ url('/admin/statistiques') }}";
            const palette = ['#556ee6', '#34c38f', '#f1b44c', '#f46a6a', '#50a5f1', '#74788d', '#a68bfa', '#4ac0d9', '#ff8c69', '#20c997'];
            const loadedSections = new Set();
            const charts = {};

            function fetchJson(path) {
                return fetch(`${basePath}/${path}`, {headers: {'Accept': 'application/json'}})
                    .then(r => {
                        if (!r.ok) throw new Error(`${path} → HTTP ${r.status}`);
                        return r.json();
                    })
                    .catch(err => {
                        console.error('Erreur de chargement statistique:', err);
                        return null;
                    });
            }

            function makeChart(id, type, labels, values, options = {}) {
                if (charts[id]) charts[id].destroy();
                const ctx = document.getElementById(id);
                if (!ctx) return;
                charts[id] = new Chart(ctx, {
                    type,
                    data: {
                        labels,
                        datasets: [{
                            label: options.label || '',
                            data: values,
                            backgroundColor: options.multi === false ? palette[0] : palette,
                            borderColor: options.border || palette[0],
                            fill: options.fill ?? false,
                            tension: .35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: options.horizontal ? 'y' : 'x',
                        plugins: {legend: {display: options.legend ?? true, position: 'bottom'}},
                        scales: options.noScales ? {} : {
                            x: {beginAtZero: true},
                            y: {beginAtZero: true, ticks: {precision: 0}}
                        }
                    }
                });
            }

            // --- Navigation par onglets ---
            document.querySelectorAll('#section-nav .nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    document.querySelectorAll('#section-nav .nav-link').forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                    document.querySelectorAll('.stat-section').forEach(s => s.classList.add('d-none'));
                    const section = link.dataset.section;
                    document.getElementById(`section-${section}`).classList.remove('d-none');
                    loadSection(section);
                });
            });

            function loadSection(section) {
                if (loadedSections.has(section)) return;
                loadedSections.add(section);
                switch (section) {
                    case 'activite':
                        loadActivite();
                        break;
                    case 'demandes':
                        loadDemandes();
                        break;
                    case 'demographie':
                        loadDemographie();
                        break;
                    case 'medecins':
                        loadMedecins();
                        break;
                    case 'examens':
                        loadExamens();
                        break;
                    case 'prescriptions':
                        loadPrescriptions();
                        break;
                    case 'antecedents':
                        loadAntecedents();
                        break;
                }
            }

            // ===== 1. ACTIVITÉ =====
            function loadConsultationsPeriode(periode) {
                fetchJson(`consultations-par-periode?periode=${periode}`).then(data => {
                    if (!data) return;
                    makeChart('chart-consultations-periode', 'line', data.map(d => d.periode), data.map(d => d.total), {
                        legend: false,
                        fill: true
                    });
                });
            }

            function loadActivite() {
                loadConsultationsPeriode('mois');
                document.getElementById('periode-select').addEventListener('change', e => loadConsultationsPeriode(e.target.value));

                fetchJson('taux-consultations-urgentes').then(data => {
                    if (!data) return;
                    makeChart('chart-urgence', 'doughnut', data.map(d => d.label), data.map(d => d.total));
                });
                fetchJson('statut-consultations').then(data => {
                    if (!data) return;
                    makeChart('chart-statut-consultations', 'pie', data.map(d => d.statut), data.map(d => d.total));
                });
                fetchJson('statut-rendez-vous').then(data => {
                    if (!data) return;
                    makeChart('chart-statut-rdv', 'pie', data.map(d => d.statut), data.map(d => d.total));
                });

                const debutInput = document.getElementById('noshow-debut');
                const finInput = document.getElementById('noshow-fin');

                function loadNoShow() {
                    let query = '';
                    if (debutInput.value && finInput.value) query = `?debut=${debutInput.value}&fin=${finInput.value}`;
                    fetchJson(`taux-no-show${query}`).then(data => {
                        if (!data) return;
                        document.getElementById('noshow-taux').textContent = `${data.taux_pourcentage}%`;
                        document.getElementById('noshow-detail').textContent = `${data.annules_ou_reportes} / ${data.total_rendez_vous} rendez-vous`;
                    });
                }

                loadNoShow();
                debutInput.addEventListener('change', loadNoShow);
                finInput.addEventListener('change', loadNoShow);
            }

            // ===== 2. DEMANDES =====
            function loadDemandes() {
                fetchJson('entonnoir-demandes-consultations').then(data => {
                    if (!data) return;
                    makeChart('chart-entonnoir', 'bar', data.map(d => d.statut), data.map(d => d.total), {
                        horizontal: true,
                        legend: false,
                        multi: false
                    });
                });
                fetchJson('demandes-par-service').then(data => {
                    if (!data) return;
                    makeChart('chart-demandes-service', 'bar', data.map(d => d.service_souhaite ?? 'Non renseigné'), data.map(d => d.total), {
                        horizontal: true,
                        legend: false,
                        multi: false
                    });
                });
                fetchJson('delai-moyen-affectation').then(data => {
                    if (!data) return;
                    document.getElementById('delai-affectation').textContent = data.delai_moyen_heures ?? '—';
                });
                fetchJson('demandes-par-urgence').then(data => {
                    if (!data) return;
                    makeChart('chart-demandes-urgence', 'bar', data.map(d => d.urgence ?? 'Non renseigné'), data.map(d => d.total), {
                        legend: false,
                        multi: false
                    });
                });
            }

            // ===== 3. DÉMOGRAPHIE =====
            function loadDemographie() {
                fetchJson('pyramide-ages').then(data => {
                    if (!data) return;
                    const tranches = [...new Set(data.map(d => d.tranche_age))];
                    const sexes = [...new Set(data.map(d => d.sexe))];
                    const datasets = sexes.map((sexe, i) => ({
                        label: sexe ?? 'Non renseigné',
                        data: tranches.map(t => data.find(d => d.tranche_age === t && d.sexe === sexe)?.total ?? 0),
                        backgroundColor: palette[i % palette.length]
                    }));
                    if (charts['chart-pyramide-ages']) charts['chart-pyramide-ages'].destroy();
                    charts['chart-pyramide-ages'] = new Chart(document.getElementById('chart-pyramide-ages'), {
                        type: 'bar',
                        data: {labels: tranches, datasets},
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {legend: {position: 'bottom'}},
                            scales: {y: {beginAtZero: true, ticks: {precision: 0}}}
                        }
                    });
                });
                fetchJson('repartition-sexe').then(data => {
                    if (!data) return;
                    makeChart('chart-sexe', 'doughnut', data.map(d => d.sexe ?? 'Non renseigné'), data.map(d => d.total));
                });
                fetchJson('repartition-groupe-sanguin').then(data => {
                    if (!data) return;
                    makeChart('chart-groupe-sanguin', 'bar', data.map(d => d.groupe_sanguin ?? 'Non renseigné'), data.map(d => d.total), {
                        legend: false,
                        multi: false
                    });
                });
            }

            // ===== 4. MÉDECINS =====
            function loadMedecins() {
                fetchJson('charge-travail-medecins').then(data => {
                    const tbody = document.querySelector('#table-medecins tbody');
                    if (!data || !data.length) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Aucune donnée</td></tr>';
                        return;
                    }
                    tbody.innerHTML = data.map(row => `
                <tr>
                    <td>${row.medecin_nom ?? '—'}
                    <td class="text-center">${row.nb_patients}</td>
                    <td class="text-center">${row.nb_consultations}</td>
                    <td class="text-center">${row.nb_rendez_vous}</td>
                    <td class="text-center">${row.nb_ordonnances}</td>
                </tr>
            `).join('');
                });
                fetchJson('statut-medecins').then(data => {
                    if (!data) return;
                    makeChart('chart-statut-medecins', 'pie', data.map(d => d.statut), data.map(d => d.total));
                });
                fetchJson('repartition-medecins-par-specialite').then(data => {
                    if (!data) return;
                    makeChart('chart-specialites', 'bar', data.map(d => d.specialite), data.map(d => d.total_medecins), {
                        horizontal: true,
                        legend: false,
                        multi: false
                    });
                });
            }

            // ===== 5. EXAMENS =====
            function loadExamens() {
                fetchJson('types-examens-plus-demandes').then(data => {
                    if (!data) return;
                    makeChart('chart-types-examens', 'bar', data.map(d => d.type_examen), data.map(d => d.total), {
                        horizontal: true,
                        legend: false,
                        multi: false
                    });
                });
                fetchJson('delai-moyen-examens').then(data => {
                    if (!data) return;
                    document.getElementById('delai-examens').textContent = data.delai_moyen_jours ?? '—';
                });
                fetchJson('statut-examens').then(data => {
                    if (!data) return;
                    makeChart('chart-statut-examens', 'pie', data.map(d => d.statut), data.map(d => d.total));
                });
            }

            // ===== 6. PRESCRIPTIONS =====
            function loadMedicaments(limite) {
                fetchJson(`medicaments-plus-prescrits?limite=${limite}`).then(data => {
                    if (!data) return;
                    makeChart('chart-medicaments', 'bar', data.map(d => d.medicament), data.map(d => d.total_prescriptions), {
                        horizontal: true,
                        legend: false,
                        multi: false
                    });
                });
            }

            function loadPrescriptions() {
                loadMedicaments(10);
                document.getElementById('medicaments-limite').addEventListener('change', e => loadMedicaments(e.target.value));
                fetchJson('nombre-moyen-medicaments-par-ordonnance').then(data => {
                    if (!data) return;
                    document.getElementById('moyenne-medicaments').textContent = data.moyenne_medicaments_par_ordonnance ?? '—';
                });
                fetchJson('repartition-forme-medicaments').then(data => {
                    if (!data) return;
                    makeChart('chart-forme-medicaments', 'pie', data.map(d => d.forme ?? 'Non renseignée'), data.map(d => d.total));
                });
            }

            // ===== 7. ANTÉCÉDENTS =====
            function loadAntecedents() {
                fetchJson('antecedents-par-type').then(data => {
                    if (!data) return;
                    makeChart('chart-antecedents-type', 'pie', data.map(d => d.type), data.map(d => d.total));
                });
                fetchJson('antecedents-par-gravite').then(data => {
                    if (!data) return;
                    makeChart('chart-antecedents-gravite', 'bar', data.map(d => d.gravite), data.map(d => d.total), {
                        legend: false,
                        multi: false
                    });
                });
                fetchJson('pathologies-frequentes?limite=10').then(data => {
                    if (!data) return;
                    makeChart('chart-pathologies', 'bar', data.map(d => d.nom), data.map(d => d.total), {
                        horizontal: true,
                        legend: false,
                        multi: false
                    });
                });
            }

            // --- Export Excel (générique, tient compte des filtres actifs de chaque graphique) ---
            function exportExcel(type, extraParams = {}) {
                const params = new URLSearchParams({type, ...extraParams});
                window.location.href = `${basePath}/export?${params.toString()}`;
            }

            document.querySelectorAll('[data-export]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const type = btn.dataset.export;
                    let extra = {};
                    if (type === 'consultations-par-periode') {
                        extra = {periode: document.getElementById('periode-select').value};
                    } else if (type === 'taux-no-show') {
                        const debut = document.getElementById('noshow-debut').value;
                        const fin = document.getElementById('noshow-fin').value;
                        if (debut && fin) extra = {debut, fin};
                    } else if (type === 'medicaments-plus-prescrits') {
                        extra = {limite: document.getElementById('medicaments-limite').value};
                    } else if (type === 'pathologies-frequentes') {
                        extra = {limite: 10};
                    }
                    exportExcel(type, extra);
                });
            });

            // Chargement initial de la section active
            loadSection('activite');
        });
    </script>
@endsection
