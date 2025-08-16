<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
    public function index()
    {
        $totalSales = Order::sum('total');
        $totalRevenue = Order::sum('downpayment');
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();

        // Monthly sales (last 12 months)
        $monthlySales = Order::selectRaw('MONTH(order_date) as month, SUM(total) as total')
            ->whereYear('order_date', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Prepare labels & data for chart
        $months = [];
        $salesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
            $salesData[] = $monthlySales[$i] ?? 0;
        }

        return view('dashboard', [
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
            'totalCustomers' => $totalCustomers,
            'totalProducts' => $totalProducts,
            'months' => json_encode($months),
            'salesData' => json_encode($salesData),
        ]);
    }


}
