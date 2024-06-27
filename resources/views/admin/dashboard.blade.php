@extends('layouts.admin')
@section('content')
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $quantity_order[4] }}</h5>
                        <p class="card-text">Đơn hàng giao dịch thành công</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">CHỜ XÁC NHẬN</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $quantity_order[1] }}</h5>
                        <p class="card-text">Số lượng đơn hàng đang chờ xử lý</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">DOANH SỐ</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ number_format($totalSales, 0, ',', '.') }}₫</h5>
                        <p class="card-text">Doanh số hệ thống</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN HÀNG HỦY</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $quantity_order[5] + 1 }}</h5>
                        <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end analytic  -->
        <div class="card">
            <div class="card-header font-weight-bold">
                ĐƠN HÀNG MỚI
            </div>
            <div class="card-body">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="checkall">
                            </th>

                            <th scope="col">Mã</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Giá trị</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $k => $item)
                            <tr>
                                <td>
                                    <input type="checkbox">
                                </td>
                                <td>{{ $item->id }}</td>
                                <td>
                                    {{ $item->shipping_address }} <br>
                                </td>
                                <td>{{ $item->product_quantity }}</td>
                                <td>{{ number_format($item->total_amount, 0, ',', '.') }}đ</td>

                                @php
                                    if ($item->status == $status_order[1]) {
                                        $badge_color = $badge_colors[1];
                                    }
                                    if ($item->status == $status_order[2]) {
                                        $badge_color = $badge_colors[2];
                                    }
                                    if ($item->status == $status_order[3]) {
                                        $badge_color = $badge_colors[3];
                                    }
                                    if ($item->status == $status_order[4]) {
                                        $badge_color = $badge_colors[4];
                                    }
                                    if ($item->status == $status_order[5]) {
                                        $badge_color = $badge_colors[5];
                                    }
                                @endphp
                                <td><span class="badge {{ $badge_color }}">{{ $item->status }}</span></td>
                                <td>{{ $item->order_date }}</td>
                                <td>
                                    <a href="{{ route('editOrder', $item->id) }}"
                                        class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                        data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    <a href="{{ route('deleteOrder', $item->id) }}"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa đơn hàng không ?')"
                                        class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                        data-toggle="tooltip" data-placement="top" title="Delete"><i
                                            class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}

            </div>
        </div>

    </div>
@endsection
