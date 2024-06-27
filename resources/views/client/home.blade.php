@extends('layouts.client')

@section('content')
    <div id="main-content-wp" class="home-page clearfix">
        <div class="wp-inner">
            <div class="main-content fl-right">
                <div class="section" id="slider-wp">
                    {{-- Slide show --}}
                    @if (session('status'))
                        <div class="alert alert-danger">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="section-detail">
                        <div class="item">

                            <img src="{{ asset('client/images/slider-01.png') }}">
                        </div>
                        <div class="item">
                            <img src="{{ asset('client/images/slider-02.png') }}">
                        </div>
                        <div class="item">
                            <img src="{{ asset('client/images/slider-03.png') }}">
                        </div>
                    </div>
                </div>
                <div class="section" id="support-wp">
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-1.png') }}">
                                </div>
                                <h3 class="title">Miễn phí vận chuyển</h3>
                                <p class="desc">Tới tận tay khách hàng</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-2.png') }}">
                                </div>
                                <h3 class="title">Tư vấn 24/7</h3>
                                <p class="desc">1900.9999</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-3.png') }}">
                                </div>
                                <h3 class="title">Tiết kiệm hơn</h3>
                                <p class="desc">Với nhiều ưu đãi cực lớn</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-4.png') }}">
                                </div>
                                <h3 class="title">Thanh toán nhanh</h3>
                                <p class="desc">Hỗ trợ nhiều hình thức</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-5.png') }}">
                                </div>
                                <h3 class="title">Đặt hàng online</h3>
                                <p class="desc">Thao tác đơn giản</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="section" id="feature-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm mới ra mắt</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            @foreach ($productsNew as $product)
                                <li>
                                    <a href="{{ route('detailProduct', $product->id) }}" title="" class="thumb">
                                        <img src="{{ asset($product->image) }}">
                                    </a>
                                    <a href="{{ route('detailProduct', $product->id) }}" title=""
                                        class="product-name">{{ $product->name }}</a>
                                    <div class="price">
                                        <span class="new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        <span class="old">
                                            @php
                                                $old = $product->price + 0.1 * $product->price;
                                                echo number_format($old, 0, ',', '.') . 'đ';
                                            @endphp
                                        </span>
                                    </div>

                                    <div class="action clearfix">
                                        <form action="{{ route('addToCart', $product->id) }}" method="POST">
                                            @csrf
                                            {{-- <a href="?page=cart" title="" class="add-cart fl-left">Thêm giỏ hàng</a> --}}
                                            <a href="">
                                                <button class="add-cart fl-left">Thêm giỏ hàng</button>
                                            </a>
                                        </form>
                                        <form action="{{ route('checkoutBuyNow', $product->id) }}" method="POST">
                                            @csrf
                                            <a href="" title="">
                                                <button class="buy-now fl-right">Mua ngay</button>
                                            </a>
                                        </form>

                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>

                @foreach ($categories as $k => $category)
                    @if (count($category->products) > 0)
                        <div class="section" id="list-product-wp">
                            <div class="section-head">

                                @if ($category->parent_id != null)
                                    <h3 class="section-title">{{ $category->name }}</h3>
                                @endif

                            </div>
                            <div class="section-detail">
                                <ul class="list-item clearfix">
                                    @foreach ($category->products as $pro)
                                        <li>
                                            <a href="{{ route('detailProduct', $pro->id) }}" title="" class="thumb">
                                                <img src="{{ asset($pro->image) }}">
                                            </a>
                                            <a href="{{ route('detailProduct', $pro->id) }}" title=""
                                                class="product-name">{{ $pro->name }}</a>
                                            <div class="price">
                                                <span class="new">{{ number_format($pro->price, 0, ',', '.') }}đ</span>
                                                <span class="old">
                                                    @php
                                                        $old = $pro->price + 0.1 * $pro->price;
                                                        echo number_format($old, 0, ',', '.') . 'đ';
                                                    @endphp
                                                </span>
                                            </div>
                                            <div class="action clearfix">
                                                <form action="{{ route('addToCart', $pro->id) }}" method="POST">
                                                    @csrf
                                                    {{-- <a href="?page=cart" title="" class="add-cart fl-left">Thêm giỏ hàng</a> --}}
                                                    <a href="">
                                                        <button class="add-cart fl-left">Thêm giỏ hàng</button>
                                                    </a>
                                                </form>
                                                <form action="{{ route('checkoutBuyNow', $pro->id) }}" method="POST">
                                                    @csrf
                                                    <a href="" title="">
                                                        <button class="buy-now fl-right">Mua ngay</button>
                                                    </a>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
            <div class="sidebar fl-left">
                <div class="section" id="category-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Danh mục sản phẩm</h3>
                    </div>
                    <div class="secion-detail">
                        <ul class="list-item">
                            @foreach ($categories as $cat)
                                @if ($cat->parent_id == null)
                                    <li>
                                        <a href="{{ route('categoryProduct', $cat->id) }}"
                                            title="">{{ $cat->name }}</a>
                                        {{-- Điện thoại, Laptop  --}}
                                        @if ($categories->where('parent_id', $cat->id)->isNotEmpty())
                                            <ul class="sub-menu">
                                                @foreach ($categories->where('parent_id', $cat->id) as $child)
                                                    <li>
                                                        <a href="{{ route('categoryProduct', $child->id) }}"
                                                            title="">{{ $child->name }}</a> {{-- iPhone, Samsung, Xiaomi  --}}

                                                        {{-- @if ($categories->where('category_id', $child->id)->isNotEmpty())
                                                            <ul class="sub-menu">
                                                                @foreach ($categories->where('category_id', $child->id) as $product)
                                                                    <li>
                                                                        <a href="?page=category_product"
                                                                            title="">{{ $product->name }}</a>
                                                                    </li>
                                                                @endforeach

                                                            </ul>
                                                        @endif --}}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="section" id="selling-wp">
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm bán chạy</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            @foreach ($productsSelling as $item)
                                <li class="clearfix">
                                    <a href="{{ route('detailProduct', $item->id) }}" title=""
                                        class="thumb fl-left">
                                        <img src="{{ asset($item->image) }}">
                                    </a>
                                    <div class="info fl-right">
                                        <a href="{{ route('detailProduct', $item->id) }}" title=""
                                            class="product-name">{{ $item->name }}</a>
                                        <div class="price">
                                            <span class="new">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                            <span class="old">
                                                @php
                                                    $old = $item->price + 0.1 * $item->price;
                                                    echo number_format($old, 0, ',', '.') . 'đ';
                                                @endphp
                                            </span>
                                        </div>
                                        {{-- <a href="" title="" class="buy-now">Mua ngay</a> --}}
                                        <form action="{{ route('checkoutBuyNow', $item->id) }}" method="POST">
                                            @csrf
                                            <a href="" title="" class="buy-now">
                                                <button class=" fl-left">Mua ngay</button>
                                            </a>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="section" id="banner-wp">
                    <div class="section-detail">
                        <a href="" title="" class="thumb">
                            <img src="{{ asset('client/images/banner.png') }}">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
