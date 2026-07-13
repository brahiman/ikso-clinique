@extends('layouts.master')

@section('title', 'Clinique IA - Tableau de bord - Statistiques')

@section('styles')
    <style>
        .kpi-card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            transition: transform .15s ease;
        }

        .kpi-card:hover {
            transform: translateY(-3px);
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .kpi-value {
            font-size: 1.6rem;
            font-weight: 700;
        }

        .kpi-label {
            font-size: .8rem;
            color: #74788d;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .chart-card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
        }

        .chart-card .card-header {
            background: transparent;
            border-bottom: 1px solid #eef0f2;
            font-weight: 600;
        }

        .chart-wrap {
            position: relative;
            height: 300px;
        }

        .chart-wrap.sm {
            height: 220px;
        }

        .loading-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #adb5bd;
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

    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Tableau de bord</h4>
                    <p class="text-muted mb-0">Vue d'ensemble de l'activité de la clinique</p>
                </div>
                <a href="{{ url('/admin/statistiques/details') }}" class="btn btn-primary">
                    <i class="mdi mdi-chart-box-outline mr-1"></i> Statistiques détaillées
                </a>
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row" id="kpi-row">
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="kpi-icon bg-soft-primary text-primary mr-3"><i class="mdi mdi-account-group"></i></div>
                    <div>
                        <div class="kpi-value" data-kpi="patients_actifs">--</div>
                        <div class="kpi-label">Patients actifs</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="kpi-icon bg-soft-success text-success mr-3"><i class="mdi mdi-doctor"></i></div>
                    <div>
                        <div class="kpi-value" data-kpi="medecins_actifs">--</div>
                        <div class="kpi-label">Médecins actifs</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="kpi-icon bg-soft-info text-info mr-3"><i class="mdi mdi-stethoscope"></i></div>
                    <div>
                        <div class="kpi-value" data-kpi="consultations_aujourdhui">--</div>
                        <div class="kpi-label">Consultations aujourd'hui</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="kpi-icon bg-soft-warning text-warning mr-3"><i class="mdi mdi-calendar-clock"></i></div>
                    <div>
                        <div class="kpi-value" data-kpi="rendez_vous_a_venir">--</div>
                        <div class="kpi-label">RDV à venir</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="kpi-icon bg-soft-danger text-danger mr-3"><i
                            class="mdi mdi-clipboard-clock-outline"></i></div>
                    <div>
                        <div class="kpi-value" data-kpi="demandes_en_attente">--</div>
                        <div class="kpi-label">Demandes en attente</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="kpi-icon bg-soft-secondary text-secondary mr-3"><i class="mdi mdi-flask-outline"></i>
                    </div>
                    <div>
                        <div class="kpi-value" data-kpi="examens_en_cours">--</div>
                        <div class="kpi-label">Examens en cours</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Consultations par période --}}
        <div class="col-xl-8 col-12 mb-4">
            <div class="card chart-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Consultations par période</span>
                    <div class="d-flex align-items-center">
                        <select id="periode-select" class="form-control form-control-sm mr-2" style="width:auto;">
                            <option value="jour">Jour</option>
                            <option value="semaine">Semaine</option>
                            <option value="mois" selected>Mois</option>
                        </select>
                        <button type="button" class="btn-export" data-export="consultations-par-periode"
                                title="Exporter en Excel">
                            <i class="mdi mdi-file-excel-outline"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-wrap">
                        <canvas id="chart-consultations-periode"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Répartition par sexe --}}
        <div class="col-xl-4 col-12 mb-4">
            <div class="card chart-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Répartition des patients par sexe</span>
                    <button type="button" class="btn-export" data-export="repartition-sexe" title="Exporter en Excel"><i
                            class="mdi mdi-file-excel-outline"></i></button>
                </div>
                <div class="card-body">
                    <div class="chart-wrap sm">
                        <canvas id="chart-sexe"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Statut des rendez-vous --}}
        <div class="col-xl-4 col-12 mb-4">
            <div class="card chart-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Statut des rendez-vous</span>
                    <button type="button" class="btn-export" data-export="statut-rendez-vous" title="Exporter en Excel">
                        <i class="mdi mdi-file-excel-outline"></i></button>
                </div>
                <div class="card-body">
                    <div class="chart-wrap sm">
                        <canvas id="chart-statut-rdv"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Entonnoir des demandes --}}
        <div class="col-xl-4 col-12 mb-4">
            <div class="card chart-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Entonnoir des demandes de consultation</span>
                    <button type="button" class="btn-export" data-export="entonnoir-demandes-consultations"
                            title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                </div>
                <div class="card-body">
                    <div class="chart-wrap sm">
                        <canvas id="chart-entonnoir"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charge de travail médecins --}}
        <div class="col-xl-4 col-12 mb-4">
            <div class="card chart-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Charge de travail — top médecins</span>
                    <div>
                        <button type="button" class="btn-export" data-export="charge-travail-medecins"
                                title="Exporter en Excel"><i class="mdi mdi-file-excel-outline"></i></button>
                        <a href="{{ url('/admin/statistiques/details') }}?section=medecins" class="small">Voir tout</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:300px;">
                        <table class="table table-sm table-hover mb-0" id="table-medecins">
                            <thead>
                            <tr>
                                <th>Médecin</th>
                                <th class="text-center">Consult.</th>
                                <th class="text-center">RDV</th>
                                <th class="text-center">Ordo.</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="4" class="loading-placeholder">Chargement…</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const basePath = "{{ url('/admin/statistiques') }}";

            const palette = ['#556ee6', '#34c38f', '#f1b44c', '#f46a6a', '#50a5f1', '#74788d', '#a68bfa', '#4ac0d9'];

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

            // --- KPIs ---
            fetchJson('dashboard-kpis').then(data => {
                if (!data) return;
                Object.keys(data).forEach(key => {
                    const el = document.querySelector(`[data-kpi="${key}"]`);
                    if (el) el.textContent = data[key];
                });
            });

            // --- Consultations par période ---
            let consultationsChart;

            function loadConsultationsPeriode(periode) {
                fetchJson(`consultations-par-periode?periode=${periode}`).then(data => {
                    if (!data) return;
                    const labels = data.map(d => d.periode);
                    const values = data.map(d => d.total);
                    if (consultationsChart) {
                        consultationsChart.data.labels = labels;
                        consultationsChart.data.datasets[0].data = values;
                        consultationsChart.update();
                        return;
                    }
                    const ctx = document.getElementById('chart-consultations-periode');
                    consultationsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Consultations',
                                data: values,
                                borderColor: palette[0],
                                backgroundColor: 'rgba(85,110,230,.12)',
                                fill: true,
                                tension: .35,
                                pointRadius: 3,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {legend: {display: false}},
                            scales: {y: {beginAtZero: true, ticks: {precision: 0}}}
                        }
                    });
                });
            }

            loadConsultationsPeriode('mois');
            document.getElementById('periode-select').addEventListener('change', e => loadConsultationsPeriode(e.target.value));

            // --- Répartition par sexe ---
            fetchJson('repartition-sexe').then(data => {
                if (!data) return;
                new Chart(document.getElementById('chart-sexe'), {
                    type: 'doughnut',
                    data: {
                        labels: data.map(d => d.sexe ?? 'Non renseigné'),
                        datasets: [{data: data.map(d => d.total), backgroundColor: palette}]
                    },
                    options: {responsive: true, maintainAspectRatio: false, plugins: {legend: {position: 'bottom'}}}
                });
            });

            // --- Statut des rendez-vous ---
            fetchJson('statut-rendez-vous').then(data => {
                if (!data) return;
                new Chart(document.getElementById('chart-statut-rdv'), {
                    type: 'pie',
                    data: {
                        labels: data.map(d => d.statut),
                        datasets: [{data: data.map(d => d.total), backgroundColor: palette}]
                    },
                    options: {responsive: true, maintainAspectRatio: false, plugins: {legend: {position: 'bottom'}}}
                });
            });

            // --- Entonnoir des demandes ---
            fetchJson('entonnoir-demandes-consultations').then(data => {
                if (!data) return;
                new Chart(document.getElementById('chart-entonnoir'), {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.statut),
                        datasets: [{label: 'Demandes', data: data.map(d => d.total), backgroundColor: palette[1]}]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {legend: {display: false}},
                        scales: {x: {beginAtZero: true, ticks: {precision: 0}}}
                    }
                });
            });

            // --- Export Excel (générique) ---
            function exportExcel(type, extraParams = {}) {
                const params = new URLSearchParams({type, ...extraParams});
                window.location.href = `${basePath}/export?${params.toString()}`;
            }

            document.querySelectorAll('[data-export]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const type = btn.dataset.export;
                    const extra = type === 'consultations-par-periode'
                        ? {periode: document.getElementById('periode-select').value}
                        : {};
                    exportExcel(type, extra);
                });
            });

            // --- Charge de travail médecins (top 8) ---
            fetchJson('charge-travail-medecins').then(data => {
                const tbody = document.querySelector('#table-medecins tbody');
                if (!data || !data.length) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Aucune donnée</td></tr>';
                    return;
                }
                tbody.innerHTML = data.slice(0, 8).map(row => `
            <tr>
                <td>${row.medecin_nom ?? '—'}</td>
                <td class="text-center">${row.nb_consultations}</td>
                <td class="text-center">${row.nb_rendez_vous}</td>
                <td class="text-center">${row.nb_ordonnances}</td>
            </tr>
        `).join('');
            });
        });
    </script>
@endsection
