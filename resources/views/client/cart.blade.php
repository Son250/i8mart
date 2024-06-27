@extends('layouts.client')

@section('content')
    <div id="main-content-wp" class="cart-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('home') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="{{ route('cart') }}" title="">Giỏ hàng</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('updateCart') }}">
            @csrf
            <div id="wrapper" class="wp-inner clearfix">
                @if (session('status'))
                    <div class="alert alert-success p-3">
                        <p class="" > {{ session('status') }}</p>
                       
                    </div>
                @endif
                @if (count($cart) > 0)
                    <div class="section" id="info-cart-wp">
                        <div class="section-detail table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>STT</td>
                                        <td>Ảnh sản phẩm</td>
                                        <td>Tên sản phẩm</td>
                                        <td>Giá sản phẩm</td>
                                        <td>Số lượng</td>
                                        <td colspan="2">Thành tiền</td>
                                        {{-- <td>Xóa</td> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $k => $item)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>
                                                <a href="{{ route('detailProduct', $item->product->id) }}" title=""
                                                    class="thumb">
                                                    <img src="{{ asset($item->product->image) }}" alt="">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('detailProduct', $item->product->id) }}" title=""
                                                    class="name-product">{{ $item->product->name }}</a>
                                            </td>
                                            <td>{{ number_format($item->product->price, '0', ',', '.') }}đ</td>
                                            <td>
                                                {{-- <input type="text" name="num-order" value="{{ $item->quantity }}"
                                                    class="num-order"> --}}
                                                <input type="number" name="quantities[{{ $item->id }}]"
                                                    value="{{ $item->quantity }}" class="num-order" min="0">
                                            </td>
                                            <td>
                                                @php
                                                    $thanhTien = $item->product->price * $item->quantity;
                                                    echo number_format($thanhTien, '0', ',', '.') . 'đ';
                                                @endphp

                                            </td>
                                            <td>
                                                {{-- icon xóa --}}
                                                <a href="{{ route('deleteItemCart', $item->id) }}" title=""
                                                    class="del-product"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <div class="clearfix">
                                                <p id="total-price" class="fl-right">Tổng giá:
                                                    <span>{{ number_format($totalPrice, '0', ',', '.') }}đ </span>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            <div class="clearfix">
                                                <div class="fl-right">
                                                    {{-- <a href="" title="" id="update-cart">Cập nhật giỏ hàng</a> --}}
                                                    <button type="submit" id="update-cart">Cập nhật giỏ hàng</button>
                                                    <a href="{{ route('checkout') }}" title=""
                                                        id="checkout-cart">Thanh
                                                        toán</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
                <div class="section" id="action-cart-wp">
                    <div class="section-detail">
                        <p class="title">Click vào <span>“Cập nhật giỏ hàng”</span> để cập nhật số lượng. Nhập vào số lượng
                            <span>0</span> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào thanh toán để hoàn tất mua hàng.
                        </p>
                        <a href="{{ route('home') }}" title="" id="buy-more">Mua tiếp</a><br />
                        <a href="{{ route('deleteCart') }}" title="" id="delete-cart">Xóa giỏ hàng</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
