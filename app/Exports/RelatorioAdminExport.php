<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Ciclo;
use App\Services\RelatorioService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RelatorioAdminExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    private array $ranking;

    public function __construct(private Ciclo $ciclo)
    {
        $this->ranking = app(RelatorioService::class)->rankingServidores($ciclo, 100);
    }

    public function array(): array
    {
        return array_map(fn ($s) => [
            $s['nome'],
            $s['cargo'],
            $s['area_nome'],
            $s['total_competencias'],
            number_format($s['media_geral'], 1, ',', ''),
        ], $this->ranking);
    }

    public function headings(): array
    {
        return ['Servidor', 'Cargo', 'Área', 'Competências', 'Média Geral'];
    }

    public function title(): string
    {
        return 'Ranking';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
