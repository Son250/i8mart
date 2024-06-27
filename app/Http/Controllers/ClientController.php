<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\CategoryBig;
use App\Models\Images;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{

    function home()
    {
        $productsNew = Product::orderBy('created_at', 'desc')->take(6)->get();
        $productsSelling = Product::orderBy('created_at', 'asc')->take(10)->get();

        $categories = Category::select('product_categories.*', 'parent.name as parent_name')
            ->leftJoin('product_categories as parent', 'product_categories.parent_id', '=', 'parent.id')
            ->orderBy('parent_id', 'asc')->get();

        foreach ($categories as $category) {
            $category->products = Product::where('category_id', $category->id)->get();
        }

        return view('client.home', compact('productsNew', 'categories', 'productsSelling'));
    }
    function detailProduct($id)
    {
        $categories = Category::all();
        $product = Product::find($id);
        $listImages = Images::where('product_id', $id)->get();
        $productCat = Product::where('category_id', $product->category_id)->where('id', '<>', $id)->get();

        return view('client.detailProduct', compact('categories', 'product', 'listImages', 'productCat'));
    }
    function cart()
    {
        $cart = Cart::where('user_id', Auth::id())->get();
        $totalPrice = $cart->reduce(function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);
        return view('client.cart', compact('cart', 'totalPrice'));
    }
    function addToCart(Request $request, $id)
    {
        if (Auth::user()) {
            if (isset($id)) {
                $quantity = $request->input('num-order', 1);  //mặc định là 1
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $id,
                    'quantity' => $quantity
                ]);
            }

            return redirect('cart');
        } else {
            // echo "<script>alert('Bạn cần đăng nhập để thực hiện thao tác này !')</script>";
            return redirect('home')->with('status', 'Bạn cần đăng nhập để thực hiện thao tác này !');
        }
    }
    function deleteItemCart($id)
    {
        $cart = Cart::where('id', $id)->delete();
        return redirect('cart');
    }
    function categoryProduct($id)
    {

        $categories = Category::select('product_categories.*', 'parent.name as parent_name')
            ->leftJoin('product_categories as parent', 'product_categories.parent_id', '=', 'parent.id')
            ->orderBy('parent_id', 'asc')->get();

        $products = Product::where('category_id', $id)->paginate(4);
        return view('client.categoryProduct', compact('products', 'categories'));
    }

    function checkout(Request $request)
    {

        $cart = Cart::where('user_id', Auth::id())->get();
        $totalPrice = $cart->reduce(function ($carry, $item) {
            return $carry + $item->product->price * $item->quantity;
        }, 0);

        return view('client.checkout', compact('cart', 'totalPrice'));
    }

    public function checkoutBuyNow(Request $request, $id)
    {
        if (Auth::user()) {
            $product = Product::find($id);
            return view('client.checkoutBuyNow', compact('product'));
        } else {
            return redirect('home')->with('status', 'Bạn cần đăng nhập để thực hiện thao tác này !');
        }
    }
    function deleteCart()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect('cart')->with('status', 'Bạn đã xóa toàn bộ giỏ hàng !');
    }
    function checkoutStore(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->get();
        $totalPrice = $cart->reduce(function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);
        if ($request->input('btnCheckOut')) {
            $request->validate(
                [
                    'fullname' => ['required', 'string', 'max:255'],
                    'email' => ['nullable'],
                    'address' => ['required', 'string'],
                    'phone' => ['required', 'numeric'],
                    'note' => ['nullable'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'numeric' => ':attribute phải là số ',
                ],
                [
                    'fullname' => 'Họ và tên',
                    'email' => 'Email',
                    'address' => 'Địa chỉ',
                    'phone' => 'Số điện thoại',
                    'note' => 'Chú thích',
                ]
            );
            $shipping_address = $request->input('fullname') . ' - ' . $request->input('email') . ' - ' . $request->input('phone') . ' - ' .  $request->input('address') . ' - ' . $request->input('note');
            $todayDate = Carbon::now()->format('Y-m-d H:i:s');
            $payment_method = $request->input('payment-method');

            if ($request->input('payment-method') == "COD") {
                //Lưu đơn hàng 
                Order::create([
                    'product_quantity' => count($cart),
                    'total_amount' => $totalPrice,
                    'order_date' => $todayDate,
                    'payment_method' => $payment_method,
                    'shipping_address' => $shipping_address,
                    'status' => 'Chờ xác nhận',
                    'customer_id' => Auth::id()

                ]);

                // Lấy đơn hàng mới nhất
                $latestOrder = Order::latest()->first();

                //Lưu chi tiết đơn hàng
                foreach ($cart as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $latestOrder->id;
                    $orderItem->product_id = $item->product_id;
                    $orderItem->quantity = $item->quantity;
                    $orderItem->price = $item->product->price;
                    $orderItem->save();
                }

                Cart::where('user_id', Auth::id())->delete();
                return redirect('orderStatus')->with('status', 'Bạn đã đặt hàng thành công!');
            } else if ($request->input('payment-method') == "ONLINE") {
                $data = $request->all();

                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
                $vnp_TmnCode = "0F5SFEKL"; //Mã website tại VNPAY 
                $vnp_HashSecret = "2WI7O0VUDU0T6DNFER322YK480ETBVAR"; //Chuỗi bí mật

                $vnp_TxnRef = rand(00, 9999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
                $vnp_OrderInfo = 'Nội dung thanh toán';
                $vnp_OrderType = 'billpayment';
                $vnp_Amount =
                    $totalPrice * 100;
                $vnp_Locale = "vn";
                $vnp_BankCode = "NCB";
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef

                );

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }
                if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                    $inputData['vnp_Bill_State'] = $vnp_Bill_State;
                }

                //var_dump($inputData);
                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }
                $returnData = array(
                    'code' => '00', 'message' => 'success', 'data' => $vnp_Url
                );
                if (isset($_POST['btnCheckOut'])) {
                    header('Location: ' . $vnp_Url);
                    die();
                } else {
                    echo json_encode($returnData);
                }
                // vui lòng tham khảo thêm tại code demo
            } else {
                return redirect('checkout')->with('status', 'Vui lòng chọn phương thức thanh toán !');
            }
        }
    }


    public function checkoutBuyNowStore(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->get();


        if ($request->input('btnCheckOutNow')) {
            $request->validate(
                [
                    'fullname' => ['required', 'string', 'max:255'],
                    'email' => ['nullable'],
                    'address' => ['required', 'string'],
                    'phone' => ['required', 'numeric'],
                    'note' => ['nullable'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'numeric' => ':attribute phải là số ',
                ],
                [
                    'fullname' => 'Họ và tên',
                    'email' => 'Email',
                    'address' => 'Địa chỉ',
                    'phone' => 'Số điện thoại',
                    'note' => 'Chú thích',
                ]
            );


            $shipping_address = $request->input('fullname') . ' - ' . $request->input('email') . ' - ' . $request->input('phone') . ' - ' . $request->input('address') . ' - ' . $request->input('note');
            $todayDate = Carbon::now()->format('Y-m-d H:i:s');
            $payment_method = $request->input('payment-method');

            if ($request->input('payment-method') == "COD") {
                //Lưu đơn hàng 
                $order = Order::create([
                    'product_quantity' => '1',
                    'total_amount' => $request->product_price,
                    'order_date' => $todayDate,
                    'payment_method' => $payment_method,
                    'shipping_address' => $shipping_address,
                    'status' => 'Chờ xác nhận',
                    'customer_id' => Auth::id()
                ]);


                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $request->product_id,
                    'quantity' => '1',
                    'price' => $request->product_price,
                ]);

                return redirect('orderStatus')->with('status', 'Bạn đã đặt hàng thành công!');
            } else if ($request->input('payment-method') == "ONLINE") {
                $data = $request->all();

                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
                $vnp_TmnCode = "0F5SFEKL"; //Mã website tại VNPAY 
                $vnp_HashSecret = "2WI7O0VUDU0T6DNFER322YK480ETBVAR"; //Chuỗi bí mật

                $vnp_TxnRef = rand(00, 9999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
                $vnp_OrderInfo = 'Nội dung thanh toán';
                $vnp_OrderType = 'billpayment';
                $vnp_Amount =
                    $request->product_price * 100;
                $vnp_Locale = "vn";
                $vnp_BankCode = "NCB";
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef

                );

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }
                if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                    $inputData['vnp_Bill_State'] = $vnp_Bill_State;
                }

                //var_dump($inputData);
                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }
                $returnData = array(
                    'code' => '00', 'message' => 'success', 'data' => $vnp_Url
                );
                if (isset($_POST['btnCheckOutNow'])) {
                    header('Location: ' . $vnp_Url);
                    die();
                } else {
                    echo json_encode($returnData);
                }
                // vui lòng tham khảo thêm tại code demo


            } else {
                return redirect('checkout')->with('status', 'Vui lòng chọn phương thức thanh toán !');
            }
        }
    }
    public function updateCart(Request $request)
    {
        $quantities = $request->input('quantities');

        // dd($quantities); số lượng của từng sp trong giỏ: 4, 3, 2, 1
        foreach ($quantities as $cartId => $quantity) {
            $cartItem = Cart::find($cartId);

            if ($cartItem) {
                if ($quantity == 0) {
                    $cartItem->delete();
                } else {
                    $cartItem->quantity = $quantity;
                    $cartItem->save();
                }
            }
        }

        return redirect()->back()->with('status', 'Giỏ hàng đã được cập nhật.');
    }
    function orderStatus(Request $request)
    {
        if ($request->status == "cho_xac_nhan") {
            $orders = $orders = Order::with('orderItems.product')
                ->where('customer_id', Auth::id())
                ->where('status', 'Chờ xác nhận')
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($request->status == "da_xac_nhan") {
            $orders = $orders = Order::with('orderItems.product')
                ->where('customer_id', Auth::id())
                ->where('status', 'Đã xác nhận')
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($request->status == "dang_giao_hang") {
            $orders = $orders = Order::with('orderItems.product')
                ->where('customer_id', Auth::id())
                ->where('status', 'Đang giao hàng')
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($request->status == "hoan_thanh") {
            $orders = $orders = Order::with('orderItems.product')
                ->where('customer_id', Auth::id())
                ->where('status', 'Hoàn thành')
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($request->status == "huy_don") {
            $orders = $orders = Order::with('orderItems.product')
                ->where('customer_id', Auth::id())
                ->where('status', 'Hủy đơn')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $orders = Order::with('orderItems.product')->where('customer_id', Auth::id())->orderBy('created_at', 'desc')->get();
        }

        // dd($orders);
        return view('client.orderStatus', compact('orders'));
    }
    public function cancelOrder(Request $request, $id)
    {
        if ($request->has('btnSubmit')) {
            Order::where('id', $id)->update([
                'status' => "Hủy đơn"
            ]);
        }
        return redirect()->back()->with('status', 'Đơn hàng đã được cập nhật.');
    }
}
