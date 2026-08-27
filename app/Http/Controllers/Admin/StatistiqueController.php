<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StatistiqueExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StatistiqueController extends Controller
{
    public function index()
    {
        return view('admin.statistiques.index');
    }

    public function show()
    {
        return view('admin.statistiques.show');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL GÉNÉRIQUE
    |--------------------------------------------------------------------------
    | GET /admin/statistiques/export?type=consultations-par-periode&periode=mois
    | Réutilise exactement les mêmes méthodes de données que les endpoints JSON,
    | donc les exports reflètent toujours ce qui est affiché à l'écran.
    */

    public function export(Request $request)
    {
        $type = $request->get('type');

        $registre = [
            'consultations-par-periode' => fn() => [
                'titre' => 'Consultations par periode',
                'data' => $this->consultationsParPeriodeData($request),
            ],
            'taux-consultations-urgentes' => fn() => [
                'titre' => 'Urgences vs normales',
                'data' => $this->tauxConsultationsUrgentesData(),
            ],
            'statut-consultations' => fn() => [
                'titre' => 'Statut consultations',
                'data' => $this->statutConsultationsData(),
            ],
            'statut-rendez-vous' => fn() => [
                'titre' => 'Statut rendez-vous',
                'data' => $this->statutRendezVousData(),
            ],
            'taux-no-show' => fn() => [
                'titre' => 'Taux no-show',
                'data' => collect([$this->tauxNoShowData($request)]),
            ],
            'entonnoir-demandes-consultations' => fn() => [
                'titre' => 'Entonnoir demandes',
                'data' => collect($this->entonnoirDemandesConsultationsData()),
            ],
            'delai-moyen-affectation' => fn() => [
                'titre' => 'Delai moyen affectation',
                'data' => collect([$this->delaiMoyenAffectationData()]),
            ],
            'demandes-par-urgence' => fn() => [
                'titre' => 'Demandes par urgence',
                'data' => $this->demandesParUrgenceData(),
            ],
            'demandes-par-service' => fn() => [
                'titre' => 'Demandes par service',
                'data' => $this->demandesParServiceData(),
            ],
            'pyramide-ages' => fn() => [
                'titre' => 'Pyramide des ages',
                'data' => $this->pyramideAgesData(),
            ],
            'repartition-sexe' => fn() => [
                'titre' => 'Repartition sexe',
                'data' => $this->repartitionSexeData(),
            ],
            'repartition-groupe-sanguin' => fn() => [
                'titre' => 'Groupe sanguin',
                'data' => $this->repartitionGroupeSanguinData(),
            ],
            'charge-travail-medecins' => fn() => [
                'titre' => 'Charge travail medecins',
                'data' => $this->chargeTravailMedecinsData(),
            ],
            'repartition-medecins-par-specialite' => fn() => [
                'titre' => 'Medecins par specialite',
                'data' => $this->repartitionMedecinsParSpecialiteData(),
            ],
            'statut-medecins' => fn() => [
                'titre' => 'Statut medecins',
                'data' => $this->statutMedecinsData(),
            ],
            'types-examens-plus-demandes' => fn() => [
                'titre' => 'Types examens demandes',
                'data' => $this->typesExamensPlusDemandesData(),
            ],
            'delai-moyen-examens' => fn() => [
                'titre' => 'Delai moyen examens',
                'data' => collect([$this->delaiMoyenExamensData()]),
            ],
            'statut-examens' => fn() => [
                'titre' => 'Statut examens',
                'data' => $this->statutExamensData(),
            ],
            'medicaments-plus-prescrits' => fn() => [
                'titre' => 'Medicaments prescrits',
                'data' => $this->medicamentsPlusPrescritsData($request),
            ],
            'nombre-moyen-medicaments-par-ordonnance' => fn() => [
                'titre' => 'Moyenne medicaments-ordo',
                'data' => collect([['moyenne_medicaments_par_ordonnance' => $this->nombreMoyenMedicamentsParOrdonnanceData()]]),
            ],
            'repartition-forme-medicaments' => fn() => [
                'titre' => 'Forme medicaments',
                'data' => $this->repartitionFormeMedicamentsData(),
            ],
            'antecedents-par-type' => fn() => [
                'titre' => 'Antecedents par type',
                'data' => $this->antecedentsParTypeData(),
            ],
            'antecedents-par-gravite' => fn() => [
                'titre' => 'Antecedents par gravite',
                'data' => $this->antecedentsParGraviteData(),
            ],
            'pathologies-frequentes' => fn() => [
                'titre' => 'Pathologies frequentes',
                'data' => $this->pathologiesFrequentesData($request),
            ],
            'dashboard-kpis' => fn() => [
                'titre' => 'KPIs dashboard',
                'data' => collect([$this->dashboardKpisData()]),
            ],
        ];

        if (!isset($registre[$type])) {
            abort(404, "Type d'export inconnu : {$type}");
        }

        $resultat = $registre[$type]();
        $nomFichier = str_replace('-', '_', $type) . '_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new StatistiqueExport($resultat['data'], $resultat['titre']), $nomFichier);
    }

    /*
    |--------------------------------------------------------------------------
    | 1. ACTIVITE ET FLUX DE PATIENTS
    |--------------------------------------------------------------------------
    */

    private function consultationsParPeriodeData(Request $request): Collection
    {
        $periode = $request->get('periode', 'mois'); // jour | semaine | mois

        $format = match ($periode) {
            'jour' => '%Y-%m-%d',
            'semaine' => '%x-W%v',
            default => '%Y-%m',
        };

        return DB::table('consultations')
            ->selectRaw("DATE_FORMAT(date_consultation, '{$format}') as periode, COUNT(*) as total")
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();
    }

    /**
     * Nombre de consultations par période (jour, semaine, mois).
     * GET /admin/statistiques/consultations-par-periode?periode=mois
     */
    public function consultationsParPeriode(Request $request)
    {
        return response()->json($this->consultationsParPeriodeData($request));
    }

    private function tauxConsultationsUrgentesData(): Collection
    {
        return DB::table('consultations')
            ->selectRaw('est_urgence, COUNT(*) as total')
            ->groupBy('est_urgence')
            ->get()
            ->map(function ($row) {
                $row->label = $row->est_urgence ? 'Urgence' : 'Normale';
                return $row;
            });
    }

    /**
     * Taux de consultations urgentes vs normales.
     */
    public function tauxConsultationsUrgentes()
    {
        return response()->json($this->tauxConsultationsUrgentesData());
    }

    private function statutConsultationsData(): Collection
    {
        return DB::table('consultations')
            ->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();
    }

    /**
     * Répartition des consultations par statut (en_cours / terminee).
     */
    public function statutConsultations()
    {
        return response()->json($this->statutConsultationsData());
    }

    private function statutRendezVousData(): Collection
    {
        return DB::table('rendez_vous')
            ->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();
    }

    /**
     * Répartition des rendez-vous par statut.
     */
    public function statutRendezVous()
    {
        return response()->json($this->statutRendezVousData());
    }

    private function tauxNoShowData(Request $request): array
    {
        $debut = $request->get('debut', now()->subMonths(1)->toDateString());
        $fin = $request->get('fin', now()->toDateString());

        $total = DB::table('rendez_vous')
            ->whereBetween('date_heure', [$debut, $fin])
            ->count();

        $annulesOuReportes = DB::table('rendez_vous')
            ->whereBetween('date_heure', [$debut, $fin])
            ->whereIn('statut', ['annule', 'reporte'])
            ->count();

        $taux = $total > 0 ? round(($annulesOuReportes / $total) * 100, 2) : 0;

        return [
            'total_rendez_vous' => $total,
            'annules_ou_reportes' => $annulesOuReportes,
            'taux_pourcentage' => $taux,
        ];
    }

    /**
     * Taux de rendez-vous annulés ou reportés (proxy de no-show) sur une période.
     */
    public function tauxNoShow(Request $request)
    {
        return response()->json($this->tauxNoShowData($request));
    }

    /*
    |--------------------------------------------------------------------------
    | 2. DEMANDES DE CONSULTATION (ENTONNOIR)
    |--------------------------------------------------------------------------
    */

    private function entonnoirDemandesConsultationsData(): \Illuminate\Support\Collection
    {
        $data = DB::table('demandes_consultations')
            ->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();

        // Ordre logique pour l'affichage en entonnoir
        $ordre = ['en_attente', 'affectee', 'confirmee', 'terminee', 'annulee'];

        return collect($ordre)->map(function ($statut) use ($data) {
            $ligne = $data->firstWhere('statut', $statut);
            return [
                'statut' => $statut,
                'total' => $ligne->total ?? 0,
            ];
        });
    }

    /**
     * Entonnoir de conversion des demandes de consultation.
     */
    public function entonnoirDemandesConsultations()
    {
        return response()->json($this->entonnoirDemandesConsultationsData());
    }

    private function delaiMoyenAffectationData(): array
    {
        $delai = DB::table('demandes_consultations')
            ->whereNotNull('date_affectation')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, date_affectation)) as delai_moyen_heures')
            ->value('delai_moyen_heures');

        return [
            'delai_moyen_heures' => $delai ? round($delai, 2) : null,
        ];
    }

    /**
     * Délai moyen (en heures) entre la création de la demande et son affectation.
     */
    public function delaiMoyenAffectation()
    {
        return response()->json($this->delaiMoyenAffectationData());
    }

    private function demandesParUrgenceData(): Collection
    {
        return DB::table('demandes_consultations')
            ->select('urgence', DB::raw('COUNT(*) as total'))
            ->groupBy('urgence')
            ->get();
    }

    /**
     * Répartition des demandes de consultation par niveau d'urgence.
     */
    public function demandesParUrgence()
    {
        return response()->json($this->demandesParUrgenceData());
    }

    private function demandesParServiceData(): Collection
    {
        return DB::table('demandes_consultations')
            ->select('service_souhaite', DB::raw('COUNT(*) as total'))
            ->groupBy('service_souhaite')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Répartition des demandes de consultation par service souhaité.
     */
    public function demandesParService()
    {
        return response()->json($this->demandesParServiceData());
    }

    /*
    |--------------------------------------------------------------------------
    | 3. DEMOGRAPHIE DES PATIENTS
    |--------------------------------------------------------------------------
    */

    private function pyramideAgesData(): Collection
    {
        return DB::table('patients')
            ->whereNull('deleted_at')
            ->whereNotNull('date_naissance')
            ->selectRaw("
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) < 18 THEN '0-17'
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 18 AND 30 THEN '18-30'
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 31 AND 45 THEN '31-45'
                    WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 46 AND 60 THEN '46-60'
                    ELSE '60+'
                END as tranche_age,
                sexe,
                COUNT(*) as total
            ")
            ->groupBy('tranche_age', 'sexe')
            ->orderBy('tranche_age')
            ->get();
    }

    /**
     * Pyramide des âges des patients par tranche.
     */
    public function pyramideAges()
    {
        return response()->json($this->pyramideAgesData());
    }

    private function repartitionSexeData(): Collection
    {
        return DB::table('patients')
            ->whereNull('deleted_at')
            ->select('sexe', DB::raw('COUNT(*) as total'))
            ->groupBy('sexe')
            ->get();
    }

    /**
     * Répartition des patients par sexe.
     */
    public function repartitionSexe()
    {
        return response()->json($this->repartitionSexeData());
    }

    private function repartitionGroupeSanguinData(): Collection
    {
        return DB::table('patients')
            ->whereNull('deleted_at')
            ->select('groupe_sanguin', DB::raw('COUNT(*) as total'))
            ->groupBy('groupe_sanguin')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Répartition des patients par groupe sanguin.
     */
    public function repartitionGroupeSanguin()
    {
        return response()->json($this->repartitionGroupeSanguinData());
    }

    /*
    |--------------------------------------------------------------------------
    | 4. MEDECINS ET SPECIALITES
    |--------------------------------------------------------------------------
    */

    private function chargeTravailMedecinsData(): Collection
    {
        return DB::table('medecins')
            ->join('users', 'users.id', '=', 'medecins.user_id')
            ->whereNull('medecins.deleted_at')
            ->leftJoin('consultations', 'consultations.medecin_id', '=', 'medecins.id')
            ->leftJoin('rendez_vous', 'rendez_vous.medecin_id', '=', 'medecins.id')
            ->leftJoin('ordonnances', 'ordonnances.medecin_id', '=', 'medecins.id')
            ->leftJoin('patient_medecin', 'patient_medecin.medecin_id', '=', 'medecins.id')
            // ->leftJoin('patients', 'patients.id', '=', 'patient_medecin.patient_id')
            ->select(
            //'medecins.id as medecin_id',
                'users.name as medecin_nom',
                DB::raw('COUNT(DISTINCT patient_medecin.id) as nb_patients'),
                DB::raw('COUNT(DISTINCT consultations.id) as nb_consultations'),
                DB::raw('COUNT(DISTINCT rendez_vous.id) as nb_rendez_vous'),
                DB::raw('COUNT(DISTINCT ordonnances.id) as nb_ordonnances'),
            )
            ->groupBy('medecins.id', 'users.name')
            ->orderBy('users.name')
            ->get();
    }

    /**
     * Charge de travail par médecin (nb de consultations, rdv, ordonnances).
     */
    public function chargeTravailMedecins()
    {
        return response()->json($this->chargeTravailMedecinsData());
    }

    private function repartitionMedecinsParSpecialiteData(): Collection
    {
        return DB::table('medecins_specialites')
            ->join('specialites', 'specialites.id', '=', 'medecins_specialites.specialite_id')
            ->whereNull('medecins_specialites.deleted_at')
            ->select('specialites.nom as specialite', DB::raw('COUNT(DISTINCT medecins_specialites.medecin_id) as total_medecins'))
            ->groupBy('specialites.nom')
            ->orderByDesc('total_medecins')
            ->get();
    }

    /**
     * Répartition des médecins par spécialité.
     */
    public function repartitionMedecinsParSpecialite()
    {
        return response()->json($this->repartitionMedecinsParSpecialiteData());
    }

    private function statutMedecinsData(): Collection
    {
        return DB::table('medecins')
            ->whereNull('deleted_at')
            ->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();
    }

    /**
     * Répartition des médecins par statut (actif / en_conge / inactif).
     */
    public function statutMedecins()
    {
        return response()->json($this->statutMedecinsData());
    }

    /*
    |--------------------------------------------------------------------------
    | 5. EXAMENS MEDICAUX
    |--------------------------------------------------------------------------
    */

    private function typesExamensPlusDemandesData(): Collection
    {
        return DB::table('demande_type_examen')
            ->join('type_examens', 'type_examens.id', '=', 'demande_type_examen.type_examen_id')
            ->select('type_examens.nom as type_examen', DB::raw('COUNT(*) as total'))
            ->groupBy('type_examens.nom')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Types d'examens les plus demandés.
     */
    public function typesExamensPlusDemandes()
    {
        return response()->json($this->typesExamensPlusDemandesData());
    }

    private function delaiMoyenExamensData(): array
    {
        $delai = DB::table('demande_type_examen')
            ->whereNotNull('date_resultat')
            ->join('demande_examens', 'demande_examens.id', '=', 'demande_type_examen.demande_examen_id')
            ->selectRaw('AVG(DATEDIFF(demande_type_examen.date_resultat, demande_examens.date_demande)) as delai_moyen_jours')
            ->value('delai_moyen_jours');

        return [
            'delai_moyen_jours' => $delai ? round($delai, 2) : null,
        ];
    }

    /**
     * Délai moyen de traitement des examens (demande -> résultat), en jours.
     */
    public function delaiMoyenExamens()
    {
        return response()->json($this->delaiMoyenExamensData());
    }

    private function statutExamensData(): Collection
    {
        return DB::table('demande_examens')
            ->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();
    }

    /**
     * Répartition des demandes d'examens par statut.
     */
    public function statutExamens()
    {
        return response()->json($this->statutExamensData());
    }

    /*
    |--------------------------------------------------------------------------
    | 6. PRESCRIPTIONS (ORDONNANCES)
    |--------------------------------------------------------------------------
    */

    private function medicamentsPlusPrescritsData(Request $request): Collection
    {
        $limite = $request->get('limite', 10);

        return DB::table('ordonnance_details')
            ->join('medicaments', 'medicaments.id', '=', 'ordonnance_details.medicament_id')
            ->select('medicaments.nom as medicament', DB::raw('COUNT(*) as total_prescriptions'))
            ->groupBy('medicaments.nom')
            ->orderByDesc('total_prescriptions')
            ->limit($limite)
            ->get();
    }

    /**
     * Médicaments les plus prescrits.
     */
    public function medicamentsPlusPrescrits(Request $request)
    {
        return response()->json($this->medicamentsPlusPrescritsData($request));
    }

    private function nombreMoyenMedicamentsParOrdonnanceData(): float
    {
        $moyenne = DB::table('ordonnance_details')
            ->select('ordonnance_id', DB::raw('COUNT(*) as nb_medicaments'))
            ->groupBy('ordonnance_id')
            ->get()
            ->avg('nb_medicaments');

        return $moyenne ? round($moyenne, 2) : 0;
    }

    /**
     * Nombre moyen de médicaments par ordonnance.
     */
    public function nombreMoyenMedicamentsParOrdonnance()
    {
        return response()->json([
            'moyenne_medicaments_par_ordonnance' => $this->nombreMoyenMedicamentsParOrdonnanceData(),
        ]);
    }

    private function repartitionFormeMedicamentsData(): Collection
    {
        return DB::table('ordonnance_details')
            ->join('medicaments', 'medicaments.id', '=', 'ordonnance_details.medicament_id')
            ->select('medicaments.forme', DB::raw('COUNT(*) as total'))
            ->groupBy('medicaments.forme')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Répartition des médicaments prescrits par forme galénique.
     */
    public function repartitionFormeMedicaments()
    {
        return response()->json($this->repartitionFormeMedicamentsData());
    }

    /*
    |--------------------------------------------------------------------------
    | 7. ANTECEDENTS MEDICAUX
    |--------------------------------------------------------------------------
    */

    private function antecedentsParTypeData(): Collection
    {
        return DB::table('antecedents_medicaux')
            ->select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Répartition des antécédents par type (maladie, allergie, operation, vaccin, familial, autre).
     */
    public function antecedentsParType()
    {
        return response()->json($this->antecedentsParTypeData());
    }

    private function antecedentsParGraviteData(): Collection
    {
        return DB::table('antecedents_medicaux')
            ->whereNotNull('gravite')
            ->select('gravite', DB::raw('COUNT(*) as total'))
            ->groupBy('gravite')
            ->get();
    }

    /**
     * Répartition des antécédents par gravité.
     */
    public function antecedentsParGravite()
    {
        return response()->json($this->antecedentsParGraviteData());
    }

    private function pathologiesFrequentesData(Request $request): Collection
    {
        $limite = $request->get('limite', 10);

        return DB::table('antecedents_medicaux')
            ->select('nom', 'type', DB::raw('COUNT(*) as total'))
            ->groupBy('nom', 'type')
            ->orderByDesc('total')
            ->limit($limite)
            ->get();
    }

    /**
     * Pathologies / allergies les plus fréquentes dans la patientèle.
     */
    public function pathologiesFrequentes(Request $request)
    {
        return response()->json($this->pathologiesFrequentesData($request));
    }

    /*
    |--------------------------------------------------------------------------
    | 8. VUE D'ENSEMBLE / TABLEAU DE BORD
    |--------------------------------------------------------------------------
    */

    private function dashboardKpisData(): array
    {
        $aujourdHui = now()->toDateString();

        return [
            'patients_actifs' => DB::table('patients')->whereNull('deleted_at')->count(),
            'medecins_actifs' => DB::table('medecins')->whereNull('deleted_at')->where('statut', 'actif')->count(),
            'consultations_aujourdhui' => DB::table('consultations')
                ->whereDate('date_consultation', $aujourdHui)
                ->count(),
            'rendez_vous_a_venir' => DB::table('rendez_vous')
                ->where('date_heure', '>=', now())
                ->whereIn('statut', ['planifie', 'confirme'])
                ->count(),
            'demandes_en_attente' => DB::table('demandes_consultations')
                ->where('statut', 'en_attente')
                ->count(),
            'examens_en_cours' => DB::table('demande_examens')
                ->where('statut', 'en_cours')
                ->count(),
        ];
    }

    /**
     * KPIs principaux pour l'en-tête du dashboard.
     */
    public function dashboardKpis()
    {
        return response()->json($this->dashboardKpisData());
    }

    /**
     * Vue d'ensemble complète combinant plusieurs indicateurs clés,
     * utile pour charger le dashboard en un seul appel réseau.
     */
    public function dashboardComplet()
    {
        return response()->json([
            'kpis' => $this->dashboardKpisData(),
            'consultations_par_periode' => $this->consultationsParPeriodeData(new Request(['periode' => 'mois'])),
            'entonnoir_demandes' => $this->entonnoirDemandesConsultationsData(),
            'charge_medecins' => $this->chargeTravailMedecinsData(),
            'repartition_sexe' => $this->repartitionSexeData(),
            'statut_rendez_vous' => $this->statutRendezVousData(),
        ]);
    }
}
