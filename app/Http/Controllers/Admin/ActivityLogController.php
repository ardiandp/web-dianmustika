<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::query()->with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('module')) {
            $query->where('module', $request->string('module'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }

        $logs = $query->get();

        $users = User::orderBy('name')->get(['id', 'name']);
        $modules = ActivityLog::distinct()->pluck('module')->filter()->sort()->values();
        $actions = ['created', 'updated', 'deleted', 'login', 'logout', 'login_failed'];

        return view('admin.activity-logs.index', compact('logs', 'users', 'modules', 'actions'));
    }

    public function destroy(ActivityLog $activityLog): RedirectResponse
    {
        $activityLog->delete();

        return back()->with('success', 'Log dihapus.');
    }

    public function prune(Request $request): RedirectResponse
    {
        $deleted = ActivityLog::where('created_at', '<', now()->subMonths(6))->delete();

        return back()->with('success', "Berhasil menghapus {$deleted} log lebih dari 6 bulan.");
    }
}
