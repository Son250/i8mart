<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;


class AdminCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }
    //
    function list(Request $request)
    {
        $users = User::all();
        // $categories = Category::orderBy('parent_id', 'asc')->paginate(10);

        //tự mình join chính mình =))))
        $categories = Category::select('product_categories.*', 'parent.name as parent_name')
            ->leftJoin('product_categories as parent', 'product_categories.parent_id', '=', 'parent.id')
            ->orderBy('parent_id', 'asc')->paginate(10);

        return view('admin.category.list', compact('categories', 'users'));
    }
    function add(Request $request)
    {
        if ($request->input('btnSubmit')) {
            $request->validate(
                [
                    'name' => ['required', 'string', 'min:2', 'max:255'],
                    'user_id' => ['required'],
                    'parent_id' => ['nullable'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                ],
                [
                    'name' => 'Tên hãng',
                    'user_id' => 'ID người tạo',
                    'parent_id' => 'Danh mục lớn'
                ]
            );

            $cat = Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $request->user_id,
                'parent_id' => $request->parent_id
            ]);

            return redirect('admin/product/cat/list')->with('status', 'Bạn đã thêm danh mục thành công !');
        }
    }

    function delete($id)
    {
        Category::where('id', $id)->delete();
        return redirect('admin/product/cat/list')->with('status', 'Bạn đã xóa thành công !');
    }
    function edit($id)
    {
        $users = User::all();
        $cat = Category::find($id);
        $categories = Category::select('product_categories.*', 'parent.name as parent_name')
            ->leftJoin('product_categories as parent', 'product_categories.parent_id', '=', 'parent.id')
            ->orderBy('parent_id', 'asc')->paginate(10);

        return view('admin.category.update', compact('cat', 'users', 'categories'));
    }
    function update(Request $request, $id)
    {
        if ($request->input('btnSubmit')) {
            $request->validate(
                [
                    'name' => ['required', 'string', 'min:2', 'max:255'],
                    'user_id' => ['required'],
                    'parent_id' => ['nullable'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                ],
                [
                    'name' => 'Tên hãng',
                    'user_id' => 'ID người tạo',
                    'parent_id' => 'Danh mục lớn'
                ]
            );
            $cat = Category::where('id', $id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $request->user_id,
                'parent_id' => $request->parent_id
            ]);
            return redirect('admin/product/cat/list')->with('status', 'Bạn đã cập nhật thành công !');
        }
    }
}
