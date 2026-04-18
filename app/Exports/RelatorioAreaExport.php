<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Area;
use App\Models\Ciclo;
use App\Services\RelatorioService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RelatorioAreaExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    private \Illuminate\Support\Collection $servidores;

    public function __construct(private Ciclo $ciclo, private Area $area)
    {
        $this->servidores = app(RelatorioService::class)->servidoresDaArea($ciclo, $area);
    }

    public function array(): array
    {
        return $this->servidores->map(fn ($s) => [
            $s['nome'],
            $s['cargo'],
            $s['total_avaliacoes'],
            $s['enviadas'],
            $s['media_geral'] !== null ? number_format($s['media_geral'], 1, ',', '') : '—',
            $s['contestacoes'],
        ])->toArray();
    }

    public function headings(): array
    {
        return ['Servidor', 'Cargo', 'Avaliações', 'Enviadas', 'Média Geral', 'Contestações'];
    }

    public function title(): string
    {
        return $this->area->nome;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
