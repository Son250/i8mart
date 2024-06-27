@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="checkout-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('home') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="" title="">Thanh toán</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('checkoutBuyNowStore') }}" name="form-checkout">
            @csrf
            <div id="wrapper" class="wp-inner clearfix">
                <div class="section" id="customer-info-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin khách hàng</h1>
                    </div>
                    <div class="section-detail">

                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="fullname">Họ tên *</label>
                                <input type="text" name="fullname" id="fullname">
                                @error('fullname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>
                            <div class="form-col fl-right">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="address">Địa chỉ *</label>
                                <input type="text" name="address" id="address">
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col fl-right">
                                <label for="phone">Số điện thoại *</label>
                                <input type="tel" name="phone" id="phone">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <label for="notes">Ghi chú</label>
                                <textarea name="note"></textarea>
                                @error('note')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
                <div class="section" id="order-review-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin đơn hàng</h1>
                    </div>
                    <div class="section-detail">
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <td>Sản phẩm</td>
                                    <td>Tổng</td>
                                </tr>
                            </thead>
                            <tbody>

                                <tr class="cart-item">
                                    <td class="product-name">{{ $product->name }}<strong class="product-quantity">x
                                            1</strong>
                                    </td>
                                    <input type="hidden" name="product_name" value="{{ $product->name }}">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <td class="product-total"> @php
                                        echo number_format($product->price, '0', ',', '.') . 'đ';
                                    @endphp</td>
                                    <input type="hidden" name="product_price" value="{{ $product->price }}">
                                </tr>

                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <td>Tổng đơn hàng:</td>
                                    <td><strong class="total-price">{{ number_format($product->price, '0', ',', '.') }}đ
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <div id="payment-checkout-wp">
                            <ul id="payment_methods">
                                <li>
                                    <input type="radio" id="direct-payment" name="payment-method" value="ONLINE">
                                    <label for="direct-payment">Thanh toán online</label>
                                </li>
                                <li>
                                    <input type="radio" checked id="payment-home" name="payment-method" value="COD">
                                    <label for="payment-home">Thanh toán tại nhà</label>
                                </li>
                            </ul>

                        </div>
                        <div class="place-order-wp clearfix">
                            <input type="submit" id="order-now" name="btnCheckOutNow" value="Đặt hàng">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
