<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'admin']);
            return $next($request);
        });
    }
    //
    function show()
    {
        $status_order = [
            1 => 'Chờ xác nhận',
            2 => 'Đã xác nhận',
            3 => 'Đang giao hàng',
            4 => 'Hoàn thành',
            5 => 'Hủy đơn'
        ];
        $badge_colors = [
            1 => "badge-warning",
            2 => "badge-secondary",
            3 => "badge-primary",
            4 => "badge-success",
            5 => "badge-danger"

        ];

        $quantity_order = [];
        for ($i = 1; $i < count($status_order); $i++) {
            $quantity_order[$i] = Order::where('status', $status_order[$i])->count();
            array_push($quantity_order, $quantity_order[$i]);
        }

        $orders = Order::latest('id')->paginate(10);
        $count_all = Order::count();

        $price = Order::where('status', 'Hoàn thành')->get();
        $totalSales = $price->sum('total_amount');

        return view('admin.dashboard', compact('orders', 'status_order', 'badge_colors', 'quantity_order', 'count_all', 'totalSales'));
    }
}
