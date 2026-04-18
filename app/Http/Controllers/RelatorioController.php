<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\RelatorioAdminExport;
use App\Exports\RelatorioAreaExport;
use App\Models\Ciclo;
use App\Services\RelatorioService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioController extends Controller
{
    public function __construct(private RelatorioService $service) {}

    public function adminCsv(Request $request)
    {
        $ciclo = Ciclo::findOrFail($request->ciclo_id);
        $nome  = 'relatorio-' . str($ciclo->nome)->slug() . '.xlsx';

        return Excel::download(new RelatorioAdminExport($ciclo), $nome);
    }

    public function adminPdf(Request $request)
    {
        $ciclo   = Ciclo::findOrFail($request->ciclo_id);
        $resumo  = $this->service->resumoGeral($ciclo);
        $porArea = $this->service->mediaPorArea($ciclo);
        $ranking = $this->service->rankingServidores($ciclo);

        $pdf = Pdf::loadView('pdf.relatorio-admin', compact('ciclo', 'resumo', 'porArea', 'ranking'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-' . str($ciclo->nome)->slug() . '.pdf');
    }

    public function gestorCsv(Request $request)
    {
        $gestor = auth()->user()->servidor;
        abort_if(! $gestor->area, 403);

        $ciclo = Ciclo::findOrFail($request->ciclo_id);
        $nome  = 'relatorio-area-' . str($gestor->area->nome)->slug() . '.xlsx';

        return Excel::download(new RelatorioAreaExport($ciclo, $gestor->area), $nome);
    }
}
