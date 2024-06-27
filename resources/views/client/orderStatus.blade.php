@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="cart-page orderStatus">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('home') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="{{ route('orderStatus') }}" title="">Đơn hàng của bạn</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="wrapper" class="wp-inner clearfix">
            @if (session('status'))
                <div class="alert alert-success p-3" align='center'>
                    <p class=""> {{ session('status') }}</p>
                </div>
            @endif

            <div class="section" id="info-cart-wp">

                <div class="section-detail table-responsive ">
                    <table class="table">
                        <thead>
                            <tr>
                                <td><a href="{{ route('orderStatus') }}">TẤT CẢ</a></td>
                                <td><a href="{{ request()->fullUrlWithQuery(['status' => 'cho_xac_nhan']) }}">CHỜ XÁC
                                        NHẬN</a></td>
                                <td><a href="{{ request()->fullUrlWithQuery(['status' => 'da_xac_nhan']) }}">ĐÃ XÁC NHẬN</a>
                                </td>
                                <td><a href="{{ request()->fullUrlWithQuery(['status' => 'dang_giao_hang']) }}">ĐANG GIAO
                                        HÀNG</a></td>
                                <td><a href="{{ request()->fullUrlWithQuery(['status' => 'hoan_thanh']) }}">HOÀN THÀNH</a>
                                </td>
                                <td><a href="{{ request()->fullUrlWithQuery(['status' => 'huy_don']) }}">HỦY ĐƠN</a></td>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($orders as $item)
                                <tr>
                                    <td colspan="6">
                                        <div class="product_donmua">
                                            <div class='trang_thai'>
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('home') }}">Shop i8mart</a>
                                                    </li>
                                                    <li><a href=""><span>HOÀN THÀNH</span></a></li>
                                                </ul>
                                            </div>

                                            <div class="product_mua">
                                                <div class="_prod">
                                                    <div class="img_prod">
                                                        <img src="https://cdn.hoanghamobile.com/i/preview/Uploads/2023/09/13/iphone-15-pro-max-natural-titanium-pure-back-iphone-15-pro-max-natural-titanium-pure-front-2up-screen-usen.png"
                                                            alt="">
                                                    </div>
                                                    <div class='name_prod'>
                                                        <p class='name'>iphone 15 pro max</p>
                                                        <p class='phan_loai'>Phân loại: Laptop, Acer</p>
                                                        <p class='so_luong'>x1</p>
                                                    </div>
                                                </div>
                                                <div class="price_prod">
                                                    <p class="price"><span>389.000đ</span> <span>175.000đ</span></p>
                                                </div>
                                            </div>
                                            <div class="thanh_tien">
                                                <div class="btn_mua_lai">
                                                    <a href="" class="mua_lai">Mua lại</a>
                                                    <a href="" class="lien_he_cua_hang">Liên hệ cửa hàng </a>
                                                </div>
                                                <p>Thành tiền: <span>200.000đ</span></p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach --}}
                            @foreach ($orders as $order)
                                @php
                                    $firstItem = true;
                                    $firstItem2 = true;
                                @endphp
                                @foreach ($order->orderItems as $item)
                                    <tr>

                                        <td colspan="6">
                                            <div class="product_donmua">
                                                @if ($firstItem)
                                                    <div class='trang_thai'>
                                                        <ul>
                                                            <li>
                                                                <a href="{{ route('home') }}">Shop i8mart</a> <span> - Mã
                                                                    đơn:
                                                                    {{ $item->order->id }}</span>
                                                            </li>
                                                            <li><a href=""><span>{{ $order->status }}</span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    @php
                                                        $firstItem = false;
                                                    @endphp
                                                @endif

                                                <div class="product_mua">
                                                    <div class="_prod">
                                                        <div class="img_prod">
                                                            <img src="{{ asset($item->product->image) }}" alt="">
                                                        </div>
                                                        <div class='name_prod'>
                                                            <p class='name'>{{ $item->product->name }}</p>
                                                            <p class='phan_loai'>Phân loại:
                                                                {{ $item->product->category->name }}
                                                            </p>
                                                            <p class='so_luong'>x{{ $item->quantity }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="price_prod">

                                                        <p class="price"><span> @php
                                                            $old = $item->price + 0.1 * $item->price;
                                                            echo number_format($old, 0, ',', '.') . 'đ';
                                                        @endphp</span>
                                                            <span>{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="thanh_tien">
                                                    @if ($firstItem2)
                                                        <div class="btn_mua_lai">

                                                            @if ($item->order->status == 'Hoàn thành')
                                                                <div>
                                                                    <form
                                                                        action="{{ route('checkoutBuyNow', $item->product->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="mua_lai">Mua
                                                                            lại</button>
                                                                    </form>

                                                                </div>
                                                            @endif

                                                            @if ($item->order->status == 'Chờ xác nhận')
                                                                <div>
                                                                    <form
                                                                        action="{{ route('cancelOrder', $item->order->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="mua_lai"
                                                                            name="btnSubmit">Hủy
                                                                            đơn</button>
                                                                    </form>

                                                                </div>
                                                            @endif
                                                            <div>
                                                                <form action="#" method="">
                                                                    @csrf
                                                                    <a href=""><button class="lien_he_cua_hang">Liên
                                                                            hệ cửa hàng</button></a>
                                                                </form>
                                                                {{-- <a href="" class="lien_he_cua_hang">Liên hệ cửa hàng
                                                                </a> --}}
                                                            </div>
                                                        </div>
                                                        @php
                                                            $firstItem2 = false;
                                                        @endphp
                                                    @endif
                                                    <p>Thành tiền:
                                                        <span>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>


        </div>

    </div>
@endsection
