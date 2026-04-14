<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period  = $request->get('period', 'monthly');
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)
            : Carbon::now()->startOfMonth();
        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)
            : Carbon::now();

        // Sales data
        $salesData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$dateFrom->startOfDay(), $dateTo->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Summary stats
        $summary = [
            'total_orders'   => $salesData->sum('orders'),
            'total_revenue'  => $salesData->sum('revenue'),
            'avg_order'      => $salesData->count() ? round($salesData->sum('revenue') / max($salesData->sum('orders'), 1), 2) : 0,
        ];

        return view('admin.reports.index', compact('salesData', 'summary', 'period', 'dateFrom', 'dateTo'));
    }

    public function exportCsv(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo   = $request->filled('date_to')   ? Carbon::parse($request->date_to)   : Carbon::now();

        $orders = Order::with('customer')
            ->whereBetween('created_at', [$dateFrom->startOfDay(), $dateTo->endOfDay()])
            ->latest()
            ->get();

        $filename = 'sales-report-' . now()->format('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order #', 'Customer', 'Date', 'Amount', 'Payment', 'Status']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->customer->shop_name ?? $order->customer->owner_name,
                    $order->created_at->format('d M Y'),
                    '₹' . number_format($order->total_amount, 2),
                    ucfirst($order->payment_status),
                    ucfirst(str_replace('_', ' ', $order->status)),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo   = $request->filled('date_to')   ? Carbon::parse($request->date_to)   : Carbon::now();

        $orders = Order::with('customer')
            ->whereBetween('created_at', [$dateFrom->startOfDay(), $dateTo->endOfDay()])
            ->latest()
            ->get();

        $totalRevenue = $orders->sum('total_amount');

        $pdf = Pdf::loadView('admin.reports.pdf', compact('orders', 'totalRevenue', 'dateFrom', 'dateTo'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('sales-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
