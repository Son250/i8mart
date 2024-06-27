@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix detail-product-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('home') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="" title="">Điện thoại</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="main-content fl-right">
                <div class="section" id="detail-product-wp">
                    <div class="section-detail clearfix">
                        <div class="thumb-wp fl-left">
                            <a href="" title="" id="main-thumb">

                                <img id="zoom" width="350px" class="" src="{{ asset($product->image) }}"
                                    data-zoom-image="{{ asset($product->image) }}"
                                    style="position: absolute; width:350px; height:350px" />
                            </a>
                            <div id="list-thumb">
                                @foreach ($listImages as $item)
                                    {{-- <a href=""  data-image="{{ asset($item->image_url) }}"
                                        data-zoom-image="{{ asset($item->image_url) }}"> --}}
                                    {{-- <img id="zoom" src="{{ asset($item->image_url) }}" /> --}}
                                    {{-- </a> --}}
                                    <a>
                                        <img id="zoom" src="{{ asset($item->image_url) }}" />
                                    </a>
                                @endforeach


                            </div>
                        </div>
                        <div class="thumb-respon-wp fl-left">
                            <img src="{{ asset($product->image) }}" alt="">
                        </div>
                        <div class="info fl-right">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="desc">
                                <p>Bộ vi xử lý :Intel Core i505200U 2.2 GHz (3MB L3)</p>
                                <p>Cache upto 2.7 GHz</p>
                                <p>Bộ nhớ RAM :4 GB (DDR3 Bus 1600 MHz)</p>
                                <p>Đồ họa :Intel HD Graphics</p>
                                <p>Ổ đĩa cứng :500 GB (HDD)</p>
                            </div>
                            <div class="num-product">
                                <span class="title">Sản phẩm: </span>
                                @if ($product->quantity > 0)
                                    <span class="status">Còn hàng</span>
                                @else
                                    <span class="status">Hết hàng</span>
                                @endif

                            </div>
                            <p class="price">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                            <form action="{{ route('addToCart', $product->id) }}" method="POST">
                                @csrf
                                <div id="num-order-wp">
                                    <a title="" id="minus"><i class="fa fa-minus"></i></a>
                                    <input type="text" name="num-order" value="1" id="num-order">
                                    <a title="" id="plus"><i class="fa fa-plus"></i></a>
                                </div>

                                @if (Auth::user())
                                    <button type="submit" name="btnSubmit" class="btn btn-success add-cart">Thêm vào
                                        giỏ hàng</button>
                                @else
                                    <p>Bạn cần <a href="{{ route('login') }}">Đăng nhập</a> để mua hàng</p>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>
                <div class="section" id="post-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Mô tả sản phẩm</h3>
                    </div>
                    <div class="section-detail">
                        {{ $product->description }}
                    </div>
                </div>
                <div class="section" id="same-category-wp">
                    @if (count($productCat) > 0)
                        <div class="section-head">
                            <h3 class="section-title">Cùng chuyên mục</h3>
                        </div>
                        <div class="section-detail">
                            <ul class="list-item">
                                @foreach ($productCat as $item)
                                    <li>
                                        <a href="{{ route('detailProduct', $item->id) }}" title="" class="thumb">
                                            <img src="{{ asset($item->image) }}" alt="">
                                        </a>
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
                                        <div class="action clearfix">
                                            {{-- <a href="" title="" class="add-cart fl-left">Thêm giỏ hàng</a>
                                            <a href="" title="" class="buy-now fl-right">Mua ngay</a> --}}
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
                    @endif
                </div>
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
                                        <a href="" title="">{{ $cat->name }}</a>
                                        {{-- Điện thoại, Laptop  --}}
                                        @if ($categories->where('parent_id', $cat->id)->isNotEmpty())
                                            <ul class="sub-menu">
                                                @foreach ($categories->where('parent_id', $cat->id) as $child)
                                                    <li>
                                                        <a href="?page=category_product"
                                                            title="">{{ $child->name }}</a> {{-- iPhone, Samsung, Xiaomi  --}}
                                                        @if ($categories->where('category_id', $child->id)->isNotEmpty())
                                                            <ul class="sub-menu">
                                                                @foreach ($categories->where('category_id', $child->id) as $product)
                                                                    <li>
                                                                        <a href="?page=category_product"
                                                                            title="">{{ $product->name }}</a>
                                                                    </li>
                                                                @endforeach

                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                            {{--                            
                            <li>
                                <a href="?page=category_product" title="">Thiết bị văn phòng</a>
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="section" id="banner-wp">
                    <div class="section-detail">
                        <a href="" title="" class="thumb">
                            <img src="{{ asset('client/images/banner.png') }}" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
