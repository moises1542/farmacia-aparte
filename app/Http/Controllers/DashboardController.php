<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        $todayTotal = Sale::whereDate('date', $today)->sum('total');
        $todayCount = Sale::whereDate('date', $today)->count();
        $monthTotal = Sale::whereYear('date', now()->year)->whereMonth('date', now()->month)->sum('total');
        $monthCount = Sale::whereYear('date', now()->year)->whereMonth('date', now()->month)->count();
        $medicinesCount = Medicine::count();
        $customersCount = Customer::count();

        $dailyTotals = Sale::whereDate('date', '>=', now()->subDays(6)->toDateString())
            ->whereDate('date', '<=', $today)
            ->selectRaw('DATE(date) as day, SUM(total) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $weekdays = ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'];
        $maxTotal = (float) $dailyTotals->max();
        $days = [];

        for ($daysAgo = 6; $daysAgo >= 0; $daysAgo--) {
            $date = now()->subDays($daysAgo);
            $total = (float) $dailyTotals->get($date->toDateString(), 0);

            $days[] = [
                'label'   => $weekdays[$date->dayOfWeek].' '.$date->day,
                'total'   => $total,
                'percent' => $maxTotal > 0 ? round($total / $maxTotal * 100) : 0,
            ];
        }

        $topMedicines = Medicine::join('detail_sales', 'detail_sales.medicine_id', '=', 'medicines.id')
            ->join('sales', 'sales.id', '=', 'detail_sales.sale_id')
            ->whereDate('sales.date', '>=', now()->subDays(29)->toDateString())
            ->whereDate('sales.date', '<=', $today)
            ->groupBy('medicines.id', 'medicines.name')
            ->select('medicines.id', 'medicines.name')
            ->selectRaw('SUM(detail_sales.amount) as units')
            ->orderByDesc('units')
            ->orderBy('medicines.name')
            ->limit(5)
            ->get();

        $maxUnits = (int) $topMedicines->max('units');

        $recentSales = Sale::with('customer')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $lowStock = Medicine::where('stock', '<=', Medicine::LOW_STOCK)
            ->orderBy('stock')
            ->orderBy('name')
            ->get();

        return view('principal.dashboard.index', compact(
            'todayTotal', 'todayCount', 'monthTotal', 'monthCount',
            'medicinesCount', 'customersCount', 'days', 'topMedicines',
            'maxUnits', 'recentSales', 'lowStock',
        ));
    }
}
