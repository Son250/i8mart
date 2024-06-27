<!DOCTYPE html>
<html>

<head>
    <title>ISMART STORE</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <link href="public/css/bootstrap/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/> --}}
    <link href="{{ asset('client/css/bootstrap/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('client/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/reset.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/carousel/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/carousel/owl.theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/responsive.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('client/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/js/elevatezoom-master/jquery.elevatezoom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/js/bootstrap/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/js/carousel/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/js/main.js') }}" type="text/javascript"></script>
  
</head>

<body>
    @php
        use App\Models\Cart;

        $cart = Cart::where('user_id', Auth::id())->get();

        $totalPrice = $cart->reduce(function ($carry, $item) {
            return $carry + $item->product->price * $item->quantity;
        }, 0);
    @endphp
    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li>
                                    <a href="{{ url('home') }}" title="">Trang chủ</a>
                                </li>
                                <li>
                                    <a href="" title="">Sản phẩm</a>
                                </li>
                                <li>
                                    <a href="" title="">Blog</a>
                                </li>
                                <li>
                                    <a href="" title="">Giới thiệu</a>
                                </li>
                                <li>
                                    <a href="" title="">Liên hệ</a>
                                </li>
                                @if (Auth::user())
                                    <li>
                                        <a href="#" title="">Tài khoản</a>
                                        <ul class="sub-menu-account">
                                            <li>
                                                <a href="">Xin chào {{ Auth::user()->name }}</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('profile') }}">Thông tin cá nhân</a>
                                            </li>
                                            @if (Auth::user()->permission == 'admin')
                                                <li>
                                                    <a href="{{ url('admin') }}">Đăng nhập vào Admin</a>
                                                </li>
                                            @endif

                                            <li>
                                                <a href="{{ url('profile') }}">Đổi mật khẩu</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('orderStatus') }}">Trạng thái đơn hàng</a>
                                            </li>
                                            <li class="liLogout">
                                                {{-- <a href="">Đăng xuất</a> --}}
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf

                                                    <x-responsive-nav-link :href="route('logout')"
                                                        onclick="event.preventDefault();
                                                                    this.closest('form').submit();" class="eventLogout">


                                                        <a href="" class="btnLogout"> <button class=" dropdown-item">

                                                                {{ __('Log Out') }}

                                                            </button> </a>
                                                    </x-responsive-nav-link>
                                                </form>
                                            </li>

                                        </ul>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('login') }}" title="">Đăng nhập</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('register') }}" title="">Đăng ký</a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
                <div id="head-body" class="clearfix">
                    <div class="wp-inner">
                        <a href="{{ url('home') }}" title="" id="logo" class="fl-left"><img
                                src="{{ asset('client/images/logo.png') }}" /></a>
                        <div id="search-wp" class="fl-left">
                            <form method="POST" action="">
                                <input type="text" name="s" id="s"
                                    placeholder="Nhập từ khóa tìm kiếm tại đây!">
                                <button type="submit" id="sm-s">Tìm kiếm</button>
                            </form>
                        </div>
                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">Tư vấn</span>
                                <span class="phone">0987.654.321</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>
                            <a href="{{ url('cart') }}" title="giỏ hàng" id="cart-respon-wp" class="fl-right">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span id="num">{{ count($cart) }}</span>
                            </a>
                            <div id="cart-wp" class="fl-right">
                                <div id="btn-cart">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span id="num">{{ count($cart) }}</span>
                                </div>
                                <div id="dropdown">
                                    <p class="desc">Có <span>{{ count($cart) }} sản phẩm</span> trong giỏ hàng</p>
                                    <ul class="list-cart">
                                        @foreach ($cart as $item)
                                            <li class="clearfix">
                                                <a href="" title="" class="thumb fl-left">
                                                    <img src="{{ asset($item->product->image) }}" alt="">
                                                </a>
                                                <div class="info fl-right">
                                                    <a href="" title=""
                                                        class="product-name">{{ $item->product->name }}</a>
                                                    <p class="price">
                                                        {{ number_format($item->product->price, '0', ',', '.') }}đ</p>
                                                    <p class="qty">Số lượng: <span>{{ $item->quantity }}</span></p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="total-price clearfix">
                                        <p class="title fl-left">Tổng:</p>
                                        <p class="price fl-right">{{ number_format($totalPrice, '0', ',', '.') }}đ</p>
                                    </div>

                                    <dic class="action-cart clearfix">
                                        <a href="{{ url('cart') }}" title="Giỏ hàng" class="view-cart fl-left">Giỏ
                                            hàng</a>
                                        @if (count($cart) > 0)
                                            <a href="{{ route('checkout') }}" title="Thanh toán"
                                                class="checkout fl-right">Thanh
                                                toán</a>
                                        @endif

                                    </dic>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                @yield('content')
            </div>

            {{-- footer --}}
            <div id="footer-wp">
                <div id="foot-body">
                    <div class="wp-inner clearfix">
                        <div class="block" id="info-company">
                            <h3 class="title">ISMART</h3>
                            <p class="desc">ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ ràng,
                                chính sách ưu đãi cực lớn cho khách hàng có thẻ thành viên.</p>
                            <div id="payment">
                                <div class="thumb">
                                    <img src="public/images/img-foot.png" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="block menu-ft" id="info-shop">
                            <h3 class="title">Thông tin cửa hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <p>106 - Trần Bình - Cầu Giấy - Hà Nội</p>
                                </li>
                                <li>
                                    <p>0987.654.321 - 0989.989.989</p>
                                </li>
                                <li>
                                    <p>vshop@gmail.com</p>
                                </li>
                            </ul>
                        </div>
                        <div class="block menu-ft policy" id="info-shop">
                            <h3 class="title">Chính sách mua hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <a href="" title="">Quy định - chính sách</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách bảo hành - đổi trả</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách hội viện</a>
                                </li>
                                <li>
                                    <a href="" title="">Giao hàng - lắp đặt</a>
                                </li>
                            </ul>
                        </div>
                        <div class="block" id="newfeed">
                            <h3 class="title">Bảng tin</h3>
                            <p class="desc">Đăng ký với chung tôi để nhận được thông tin ưu đãi sớm nhất</p>
                            <div id="form-reg">
                                <form method="POST" action="">
                                    <input type="email" name="email" id="email"
                                        placeholder="Nhập email tại đây">
                                    <button type="submit" id="sm-reg">Đăng ký</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="foot-bot">
                    <div class="wp-inner">
                        <p id="copyright">© Bản quyền thuộc về unitop.vn | Php Master</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="menu-respon">
            <a href="?page=home" title="" class="logo">VSHOP</a>
            <div id="menu-respon-wp">
                <ul class="" id="main-menu-respon">
                    <li>
                        <a href="?page=home" title>Trang chủ</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title>Điện thoại</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="?page=category_product" title="">Iphone</a>
                            </li>
                            <li>
                                <a href="?page=category_product" title="">Samsung</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="?page=category_product" title="">Iphone X</a>
                                    </li>
                                    <li>
                                        <a href="?page=category_product" title="">Iphone 8</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="?page=category_product" title="">Nokia</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="?page=category_product" title>Máy tính bảng</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title>Laptop</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title>Đồ dùng sinh hoạt</a>
                    </li>
                    <li>
                        <a href="?page=blog" title>Blog</a>
                    </li>
                    <li>
                        <a href="#" title>Liên hệ</a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="btn-top"><img src=" {{ asset('client/images/icon-to-top.png') }}" alt="" /></div>
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
</body>

</html>
