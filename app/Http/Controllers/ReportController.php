<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function interaccionesPorAsesor()
    {
            $asesores = DB::table('users')
            ->select(
            'users.id',
            'users.name',
            DB::raw("COUNT(CASE WHEN interactions.tipo_interaccion = 'Llamada'
            THEN 1 END) AS llamadas"),
            DB::raw("COUNT(CASE WHEN interactions.tipo_interaccion = 'Visita'
            THEN 1 END) AS visitas"),
            DB::raw("COUNT(CASE WHEN interactions.tipo_interaccion = 'WhatsApp'
            THEN 1 END) AS whatsapp"),
            DB::raw('COUNT(interactions.id) AS total')
        )
            ->leftJoin('clients', 'clients.user_id', '=', 'users.id')
            ->leftJoin('interactions', 'interactions.client_id', '=', 'clients.id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->get();
            $labels = $asesores->pluck('name')->toArray();
            $llamadas = $asesores->pluck('llamadas')->toArray();
            $visitas = $asesores->pluck('visitas')->toArray();
            $whatsapp = $asesores->pluck('whatsapp')->toArray();
            return view('reports.interacciones', compact(
            'asesores', 'labels', 'llamadas', 'visitas', 'whatsapp'
        ));
    }
}
