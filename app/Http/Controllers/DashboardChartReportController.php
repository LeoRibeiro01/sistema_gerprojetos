<?php

namespace App\Http\Controllers;

use App\Models\DashboardChart;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class DashboardChartReportController extends Controller
{
    public function show(Request $request, DashboardChart $chart)
    {
        abort_unless($request->user()->isInternalTeam(), 403);
        abort_unless($chart->user_id === $request->user()->id, 403);

        $data = app(DashboardController::class)->chartData($chart->metrica);
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('reports.chart', compact('chart', 'data')));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="grafico-'.$chart->id.'.pdf"',
        ]);
    }
}
