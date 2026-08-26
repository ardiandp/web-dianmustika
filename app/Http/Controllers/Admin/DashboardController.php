<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageClick;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->query('period', '30d'); // 7d, 30d, 12m, year

        [$start, $end, $groupFormat, $labelFormat] = $this->resolvePeriod($period);

        // Summary boxes
        $todayStart = now()->startOfDay();
        $weekStart = now()->subDays(6)->startOfDay();
        $monthStart = now()->subDays(29)->startOfDay();
        $yearStart = now()->startOfYear();

        $summary = [
            'today' => $this->summaryForRange($todayStart, now()),
            'week' => $this->summaryForRange($weekStart, now()),
            'month' => $this->summaryForRange($monthStart, now()),
            'year' => $this->summaryForRange($yearStart, now()),
        ];

        // Chart data for selected period
        $chart = $this->chartData($start, $end, $groupFormat, $labelFormat);

        // Top pages
        $topPages = PageView::query()
            ->whereBetween('viewed_at', [$start, $end])
            ->select('path', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT ip_hash) as unik'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->map(function ($row) use ($start, $end) {
                $row->clicks = PageClick::where('path', $row->path)->whereBetween('clicked_at', [$start, $end])->count();
                $row->ctr = $row->views > 0 ? round($row->clicks / $row->views * 100, 1) : 0;
                return $row;
            });

        // Clicks by element
        $clicksByElement = PageClick::query()
            ->whereBetween('clicked_at', [$start, $end])
            ->select('element', DB::raw('COUNT(*) as total'))
            ->groupBy('element')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Device breakdown
        $deviceStats = PageView::query()
            ->whereBetween('viewed_at', [$start, $end])
            ->select('device', DB::raw('COUNT(*) as total'))
            ->groupBy('device')
            ->get();

        // Country breakdown
        $countryStats = PageView::query()
            ->whereBetween('viewed_at', [$start, $end])
            ->whereNotNull('country')
            ->select('country', 'city', DB::raw('COUNT(*) as total'))
            ->groupBy('country', 'city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Browser / OS
        $browserStats = PageView::query()
            ->whereBetween('viewed_at', [$start, $end])
            ->whereNotNull('browser')
            ->select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'period', 'start', 'end', 'summary', 'chart', 'topPages', 'clicksByElement', 'deviceStats', 'countryStats', 'browserStats'
        ));
    }

    private function summaryForRange($start, $end): array
    {
        $views = PageView::whereBetween('viewed_at', [$start, $end])->count();
        $unik = PageView::whereBetween('viewed_at', [$start, $end])->distinct('ip_hash')->count('ip_hash');
        // Fallback for distinct count with session: count distinct ip_hash
        $clicks = PageClick::whereBetween('clicked_at', [$start, $end])->count();

        return compact('views', 'unik', 'clicks');
    }

    private function chartData($start, $end, $groupFormat, $labelFormat): array
    {
        // Fetch daily aggregates (DB agnostic: use DATE() which works in MySQL & SQLite)
        $driver = DB::connection()->getDriverName();
        $dateExprViews = $driver === 'sqlite' ? "date(viewed_at)" : "DATE(viewed_at)";
        $dateExprClicks = $driver === 'sqlite' ? "date(clicked_at)" : "DATE(clicked_at)";

        $viewsPerDay = PageView::query()
            ->whereBetween('viewed_at', [$start, $end])
            ->select(DB::raw("$dateExprViews as date"), DB::raw('COUNT(*) as total'), DB::raw('COUNT(DISTINCT ip_hash) as unik'))
            ->groupBy(DB::raw($dateExprViews))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $clicksPerDay = PageClick::query()
            ->whereBetween('clicked_at', [$start, $end])
            ->select(DB::raw("$dateExprClicks as date"), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw($dateExprClicks))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // For monthly/yearly, aggregate daily data in PHP (DB agnostic)
        if (in_array($groupFormat, ['Y-m'])) {
            $monthlyViews = [];
            $monthlyUnik = [];
            $monthlyClicks = [];
            foreach ($viewsPerDay as $date => $row) {
                $ym = substr($date, 0, 7);
                $monthlyViews[$ym] = ($monthlyViews[$ym] ?? 0) + $row->total;
                $monthlyUnik[$ym] = ($monthlyUnik[$ym] ?? 0) + $row->unik;
            }
            foreach ($clicksPerDay as $date => $row) {
                $ym = substr($date, 0, 7);
                $monthlyClicks[$ym] = ($monthlyClicks[$ym] ?? 0) + $row->total;
            }
            $labels = [];
            $views = [];
            $unik = [];
            $clicks = [];
            $cursor = $start->copy()->startOfMonth();
            while ($cursor->lte($end)) {
                $key = $cursor->format('Y-m');
                $labels[] = $cursor->format('M Y');
                $views[] = $monthlyViews[$key] ?? 0;
                $unik[] = $monthlyUnik[$key] ?? 0;
                $clicks[] = $monthlyClicks[$key] ?? 0;
                $cursor->addMonth();
            }
            return compact('labels', 'views', 'unik', 'clicks');
        }

        $labels = [];
        $views = [];
        $unik = [];
        $clicks = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->format('Y-m-d');
            $labels[] = $cursor->format($labelFormat);
            $views[] = $viewsPerDay[$key]->total ?? 0;
            $unik[] = $viewsPerDay[$key]->unik ?? 0;
            $clicks[] = $clicksPerDay[$key]->total ?? 0;
            $cursor->addDay();
        }

        return compact('labels', 'views', 'unik', 'clicks');
    }

    private function resolvePeriod(string $period): array
    {
        return match ($period) {
            '7d' => [now()->subDays(6)->startOfDay(), now()->endOfDay(), 'Y-m-d', 'd M'],
            '12m' => [now()->subMonths(11)->startOfMonth(), now()->endOfDay(), 'Y-m', 'M Y'],
            'year' => [now()->startOfYear(), now()->endOfDay(), 'Y-m', 'M Y'],
            default => [now()->subDays(29)->startOfDay(), now()->endOfDay(), 'Y-m-d', 'd M'], // 30d
        };
    }
}
