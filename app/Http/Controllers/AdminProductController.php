<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Images;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }
    //
    //
    function list(Request $request)
    {
        if ($request->status == "trash") {
            $products = Product::onlyTrashed()->paginate(10);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $products = Product::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }

        $count_active = Product::count();
        $count_trash = Product::onlyTrashed()->count();

        return view('admin.product.list', compact('products', 'count_active', 'count_trash'));
    }
    function add(Request $request)
    {
        $users = User::all();
        // $categories = Category::all();
        $categories = Category::select('product_categories.*', 'parent.name as parent_name')
            ->leftJoin('product_categories as parent', 'product_categories.parent_id', '=', 'parent.id')
            ->orderBy('parent_id', 'asc')->get();
        return view('admin.product.add', compact('categories', 'users'));
    }
    function store(Request $request)
    {
        if ($request->input('btnSubmit')) {
            $data = $request->validate(
                [
                    'name' => ['required', 'string', 'max:255'],
                    'price' => ['required', 'numeric'],
                    'description' => ['required', 'string'],
                    'quantity' => ['required', 'numeric'],
                    'user_id' => ['required'],
                    'category_id' => ['required'],
                    'image' => ['required', 'min:1'],
                    // 'images' => ['array', 'required'],
                    // 'images.*' => ['file', 'mimes:jpg,jpeg,png', 'max:2048'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'numeric' => ':attribute phải là số ',
                ],
                [
                    'name' => 'Tên sản phẩm',
                    'price' => 'Giá',
                    'description' => 'Mô tả sản phẩm',
                    'quantity' => 'Số lượng',
                    'user_id' => 'Người tạo',
                    'category_id' => 'Danh mục',
                    'image' => 'Ảnh đại diện',
                    'images' => 'Ảnh chi tiết sản phẩm',
                ]
            );

            $product = Product::create($data);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_name = time() . '_' . $file->getClientOriginalName();

                $path = $file->move(public_path('uploads'), $file_name);
                $relative_path = 'uploads/' . $file_name;

                $product->image = $relative_path;       // Cập nhật đường dẫn cho sản phẩm
                $product->save();
            }

            // Lưu ảnh chi tiết
            if ($request->hasFile('images')) {
                $file = $request->file('images');
                foreach ($request->file('images') as $file) {

                    $file_name = time() . '_' . $file->getClientOriginalName();
                    $path = $file->move(public_path('uploads'), $file_name);

                    Images::create([
                        'product_id' => $product->id,
                        'image_url' => 'uploads/' . $file_name, // Đường dẫn tương đối
                    ]);
                }
            }

            return redirect('admin/product/list')->with('status', 'Bạn đã thêm sản phẩm thành công !');
        }
    }
    function delete($id)
    {
        Product::where('id', $id)->delete();
        return redirect('admin/product/list')->with('status', 'Bạn đã xóa thành công !');
    }
    function hardDeleteProduct($id)
    {
        Product::where('id', $id)->forceDelete();
        return redirect('admin/product/list')->with('status', 'Bạn đã xóa vĩnh viễn sản phẩm này !');
    }
    function restoreProduct($id)
    {
        Product::onlyTrashed()->where('id', $id)->restore();
        return redirect('admin/product/list')->with('status', 'Bạn đã khôi phục thành công !');
    }
    function edit($id)
    {
        $product = Product::find($id);
        $users = User::all();
        $categories = Category::select('product_categories.*', 'parent.name as parent_name')
            ->leftJoin('product_categories as parent', 'product_categories.parent_id', '=', 'parent.id')
            ->orderBy('parent_id', 'asc')->get();
        return view('admin.product.update', compact('categories', 'users', 'product'));
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {

            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == "delete") {
                    Product::destroy($list_check);
                    return redirect('admin/product/list')->with('status', 'Bạn đã xóa thành công !');
                }
                if ($act == "restore") {
                    Product::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('admin/product/list')->with('status', 'Bạn đã khôi phục thành công !');
                }
                if ($act == "hardDelete") {
                    Product::onlyTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('admin/product/list')->with('status', 'Bạn đã xóa vĩnh viễn thành công !');
                }
                if ($act == "") {
                    return redirect('admin/product/list')->with('status', 'Bạn cần chọn thao tác thực thi !');
                }
            }
        } else {
            return redirect('admin/user/list')->with('status', 'Bạn cần chọn phần tử cần thực thi !');
        }
    }
    public function update(Request $request, $id)
    {
        if ($request->input('btnSubmit')) {
            $data = $request->validate(
                [
                    'name' => ['required', 'string', 'max:255'],
                    'price' => ['required', 'numeric'],
                    'description' => ['required', 'string'],
                    'quantity' => ['required', 'numeric'],
                    'user_id' => ['required'],
                    'category_id' => ['required'],
                    // 'image' => ['nullable', 'file', 'max:2048'],
                    
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'numeric' => ':attribute phải là số ',
                ],
                [
                    'name' => 'Tên sản phẩm',
                    'price' => 'Giá',
                    'description' => 'Mô tả sản phẩm',
                    'quantity' => 'Số lượng',
                    'user_id' => 'Người tạo',
                    'category_id' => 'Danh mục',
                    'image' => 'Ảnh đại diện',
                ]
            );

            $product = Product::find($id);

            if (!$product) {
                return redirect('admin/product/list')->with('error', 'Sản phẩm không tồn tại!');
            }

            // Cập nhật các thông tin sản phẩm khác
            $product->name = $request->name;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->user_id = $request->user_id;
            $product->category_id = $request->category_id;

            // Kiểm tra và cập nhật ảnh nếu có file mới
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_name = time() . '_' . $file->getClientOriginalName();

                // Di chuyển file đến thư mục public/uploads và lấy đường dẫn tương đối
                $file->move(public_path('uploads'), $file_name);
                $relative_path = 'uploads/' . $file_name;

                // Xóa ảnh cũ nếu có
                if ($product->image) {
                    $old_image_path = public_path($product->image);
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Cập nhật đường dẫn ảnh mới cho sản phẩm
                $product->image = $relative_path;
            }

            $product->save();

            return redirect('admin/product/list')->with('status', 'Bạn đã cập nhật sản phẩm thành công!');
        }
    }
}
