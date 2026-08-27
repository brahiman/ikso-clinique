<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export Excel générique : prend n'importe quelle Collection de lignes
 * (stdClass ou tableaux associatifs, peu importe leurs colonnes) et
 * génère automatiquement les en-têtes à partir des clés de la première ligne.
 *
 * Utilisation :
 *   Excel::download(new StatistiqueExport($data, 'Titre de la feuille'), 'fichier.xlsx');
 */
class StatistiqueExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected Collection $data;
    protected string $titre;

    public function __construct(Collection $data, string $titre = 'Statistique')
    {
        $this->data = $data;
        $this->titre = $titre;
    }

    public function collection()
    {
        return $this->data->map(fn($ligne) => (array)$ligne);
    }

    public function headings(): array
    {
        $premiere = $this->data->first();

        if (!$premiere) {
            return ['Aucune donnée'];
        }

        return array_map(
            fn($cle) => ucfirst(str_replace('_', ' ', $cle)),
            array_keys((array)$premiere)
        );
    }

    public function title(): string
    {
        // Excel limite le nom d'un onglet à 31 caractères.
        return substr($this->titre, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
