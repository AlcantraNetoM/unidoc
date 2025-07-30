<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        // Estatísticas
        $stats = [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::whereDate('created_at', today())->count(),
            'critical_logs' => AuditLog::where('severity', 'critical')->count(),
            'failed_logins' => AuditLog::where('action', 'failed_login')->whereDate('created_at', today())->count(),
            'unique_users_today' => AuditLog::whereDate('created_at', today())->distinct('user_id')->count('user_id'),
        ];

        // Dados para gráficos
        $actionsChart = AuditLog::selectRaw('action, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('action')
            ->pluck('count', 'action')
            ->toArray();

        $dailyActivity = AuditLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return view('super_admin.audit_logs.index', compact('logs', 'stats', 'actionsChart', 'dailyActivity'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('super_admin.audit_logs.show', compact('auditLog'));
    }

    public function export(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Aplicar mesmos filtros
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $csv = "Data,Ação,Modelo,Usuário,Email,IP,Descrição,Severidade\n";
        
        foreach ($logs as $log) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,\"%s\",%s\n",
                $log->created_at->format('Y-m-d H:i:s'),
                $log->action,
                $log->model ?? 'N/A',
                $log->user_name ?? 'N/A',
                $log->user_email ?? 'N/A',
                $log->ip_address ?? 'N/A',
                str_replace('"', '""', $log->description ?? ''),
                $log->severity
            );
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"'
        ]);
    }

    public function deleteOld(Request $request)
    {
        $days = $request->input('days', 90);
        
        $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "Removidos {$deleted} logs com mais de {$days} dias.");
    }
}
