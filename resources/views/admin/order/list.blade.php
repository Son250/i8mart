@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách đơn hàng</h5>

                <div class="form-search form-inline">
                    <form action="">
                        <input type="text" class="form-control form-search" name="keyword"
                            placeholder="Tìm kiếm theo khách hàng" value="{{ request()->input('keyword') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'tat_ca']) }}" class="text-primary">Tất cả<span
                            class="text-muted">({{ $count_all }})</span></a>
                    @foreach ($status_order as $i => $item)
                        <a href="{{ request()->fullUrlWithQuery(['status' => $i]) }}"
                            class="text-primary">{{ $item }}<span
                                class="text-muted">({{ $quantity_order[$i] }})</span></a>
                    @endforeach


                </div>
                <div class="form-action form-inline py-3">
                    <select class="form-control mr-1" id="">
                        <option>Chọn</option>
                        {{-- <option>Tác vụ 1</option> --}}
                        {{-- <option>Tác vụ 2</option> --}}
                    </select>
                    <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                </div>
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
