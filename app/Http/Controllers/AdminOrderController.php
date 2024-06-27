<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;

use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    function list(Request $request)
    {
        // $orders = Order::latest('id')->paginate(5);
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

        for ($i = 1; $i < count($status_order) + 1; $i++) {
            if ($request->status == $i) {
                $orders = Order::where('status', $status_order[$i])->latest('id')->paginate(10);
            }
        }

        for ($i = 1; $i < count($status_order); $i++) {
            $quantity_order[$i] = Order::where('status', $status_order[$i])->count();
            array_push($quantity_order, $quantity_order[$i]);
        }
        // dd($quantity_order);


        if ($request->status == "tat_ca" || $request->status == "") {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $orders = Order::where('shipping_address', 'LIKE', "%{$keyword}%")->latest('id')->paginate(10);
        }
        $count_all = Order::count();

        return view('admin.order.list', compact('orders', 'status_order', 'badge_colors', 'quantity_order', 'count_all'));
    }

    public function edit($id)
    {
        $status_order = [
            1 => 'Chờ xác nhận',
            2 => 'Đã xác nhận',
            3 => 'Đang giao hàng',
            4 => 'Hoàn thành',
            5 => 'Hủy đơn'
        ];
        $order = Order::with('orderItems')->findOrFail($id);
        return view('admin.order.update', compact('order', 'status_order'));
    }
    function update(Request $request, $id)
    {
        if ($request->has('btnSubmit')) {
            $order = Order::findOrFail($id);

            $statuses = [
                1 => 'Chờ xác nhận',
                2 => 'Đã xác nhận',
                3 => 'Đang giao hàng',
                4 => 'Hoàn thành',
                5 => 'Hủy đơn'
            ];
            $currentStatus = array_search($order->status, $statuses);     //trạng thái cũ
            $newStatus = (int) $request->input('status');       //trạng thái mới

            // Kiểm tra điều kiện 
            if ($newStatus <  $currentStatus) {
                return redirect()->back()->with('error', 'Không thể chuyển trạng thái đơn hàng quay trở lại !');
            } else {
                Order::where('id', $id)->update([
                    'status' => $request->status
                ]);
            }
        }
        return redirect('admin/order/list')->with('status', 'Bạn đã cập nhật thành công !');
    }
    function deleteOrder($id)
    {
        Order::where('id', $id)->delete();
        return redirect('admin/order/list')->with('status', 'Bạn đã xóa thành công !');
    }
}
