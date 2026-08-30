<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Sistem: Log Aktivitas & Audit (/admin/logs)
     */
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        // Kata kunci umum: cari di aksi, email aktor, atau IP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action_type', 'like', "%{$search}%")
                  ->orWhere('actor_email', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filter level (INFO/WARN/ERROR)
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter tipe aksi (exact match, dari dropdown)
        if ($request->filled('aksi')) {
            $query->where('action_type', $request->aksi);
        }

        // Filter email pengguna (khusus, terpisah dari kata kunci umum)
        if ($request->filled('email')) {
            $query->where('actor_email', 'like', "%{$request->email}%");
        }

        // Filter rentang tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }

        $logs = $query->latest('created_at')->paginate(10)->withQueryString();

        // Untuk badge total & dropdown Tipe Aksi (daftar unik dari seluruh data, bukan hasil filter)
        $totalLogs   = ActivityLog::count();
        $actionTypes = ActivityLog::select('action_type')->distinct()->orderBy('action_type')->pluck('action_type');

        return view('admin.logs', [
            'logs'         => $logs,
            'totalLogs'    => $totalLogs,
            'actionTypes'  => $actionTypes,
            'search'       => $request->search,
            'levelFilter'  => $request->level,
            'aksiFilter'   => $request->aksi,
            'emailFilter'  => $request->email,
            'dariTanggal'  => $request->dari_tanggal,
            'sampaiTanggal' => $request->sampai_tanggal,
        ]);
    }

    /**
     * Bersihkan log lama (lebih dari 90 hari) — data terbaru tetap
     * append-only sesuai kebijakan retensi.
     */
    public function clear(Request $request)
    {
        ActivityLog::where('created_at', '<', now()->subDays(90))->delete();

        return response()->json(['message' => 'Log lama berhasil dibersihkan.']);
    }
}