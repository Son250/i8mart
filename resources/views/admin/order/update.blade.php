@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">

            <div class="card-header font-weight-bold">
                Cập nhật đơn hàng
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('updateOrder', $order->id) }}">
                    @csrf
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
               
                    <p><strong>Mã đơn hàng:</strong> {{ $order->id }}</p>
                    <p><strong>Khách hàng :</strong> {{ $order->shipping_address }}</p>
                    <p><strong>Phương thức thanh toán :</strong> {{ $order->payment_method }}</p>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="" class="text-primary"><b>Trạng thái:</b> </label> <br>
                            <select name="status" id="" class="form-control">
                                @foreach ($status_order as $item)
                                    <option value="{{ $loop->iteration }}"
                                        @if ($item == $order->status) selected @endif>{{ $item }}</option>
                                @endforeach
                               
                                       
                            </select>
                        </div>
                    </div>
                    <ul>
                        @foreach ($order->orderItems as $item)
                            <li>
                                <p><strong>Sản phẩm:</strong> {{ $item->product->name }}</p>
                                <p><strong>Số lượng:</strong> {{ $item->quantity }}</p>
                                <p><strong>Giá:</strong> {{ number_format($item->price, 0, ',', '.') }}đ</p>
                            </li>
                        @endforeach
                    </ul>
                    <p><strong>Ngày đặt:</strong> {{ $order->order_date }}</p>
                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }}đ</p>




                    <button type="submit" name="btnSubmit" value="Cập nhật" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
