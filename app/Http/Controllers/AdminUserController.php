<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rules; // Đúng cách import

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);
            return $next($request);
        });
    }
    //
    function list(Request $request)
    {
        if ($request->status == "trash") {
            $users = User::onlyTrashed()->orderBy('permission', 'asc')->paginate(5);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $users = User::where('name', 'LIKE', "%{$keyword}%")->orderBy('permission', 'asc')->paginate(5);
        }

        $count_active = User::count();
        $count_trash = User::onlyTrashed()->count();

        // dd($users->total());
        return view('admin.user.list', compact('users', 'count_active', 'count_trash'));
    }
    function add(Request $request)
    {

        return view('admin.user.add');
    }
    function store(Request $request)
    {
        if ($request->input('btnSubmit')) {
            $request->validate(
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                    'password_confirmation' => ['required'],
                    'address' => ['required'],
                    'phone' => ['required', 'numeric'],
                    'permission' => ['required'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'confirmed' => 'Xác nhận mật khẩu không thành công',
                ],
                [
                    'name' => 'Tên người dùng',
                    'email' => 'Email',
                    'password' => 'Mật khẩu',
                    'password_confirmation' => 'Xác nhận mật khẩu',
                    'address' => 'Địa chỉ',
                    'phone' => 'Số điện thoại',
                    'permission' => 'Quyền'
                ]
            );

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'permission' => $request->permission

            ]);

            return redirect('admin/user/list')->with('status', 'Bạn đã thêm thành viên thành công !');
        }
    }

    function edit($id)
    {
        $user = User::find($id);
        // return $user;
        return view('admin/user/update', compact('user'));
    }

    function update(Request $request, $id)
    {
        if ($request->input('btnSubmit')) {
            $request->validate(
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => [ 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                   
                    'address' => ['required'],
                    'phone' => ['required', 'numeric'],
                    'permission' => ['required'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'confirmed' => 'Xác nhận mật khẩu không thành công',
                ],
                [
                    'name' => 'Tên người dùng',
                    'email' => 'Email',
              
                    
                    'address' => 'Địa chỉ',
                    'phone' => 'Số điện thoại',
                    'permission' => 'Quyền'
                ]
            );


            $user = User::where('id', $id)->update([
                'name' => $request->name,
             
                'phone' => $request->phone,
                'address' => $request->address,
                'permission' => $request->permission
            ]);

            return redirect('admin/user/list')->with('status', 'Bạn đã cập nhật thành công !');
        }
    }

    function delete($id)
    {
        if (Auth::id() != $id) {
            User::where('id', $id)->delete();
            return redirect('admin/user/list')->with('status', 'Bạn đã xóa thành công!');
        } else {
            return redirect('admin/user/list')->with('status', 'Bạn không thể tự xóa chính mình !');
        }
    }
    function restore($id)
    {
        User::onlyTrashed()->where('id', $id)->restore();
        return redirect('admin/user/list')->with('status', 'Bạn đã khôi phục dữ liệu thành công!');
    }
    function hardDelete($id)
    {
        //xóa cứng
        User::onlyTrashed()->where('id', $id)->forceDelete();
        return redirect('admin/user/list')->with('status', 'Bạn đã xóa vĩnh viễn người dùng này !');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            //xóa id của bản thân mình đi
            foreach ($list_check as $k => $id) {
                if (Auth::id() == $id) {
                    unset($list_check[$k]);
                }
            }

            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == "delete") {
                    User::destroy($list_check);
                    return redirect('admin/user/list')->with('status', 'Bạn đã xóa thành công !');
                }
                if ($act == "restore") {
                    User::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('admin/user/list')->with('status', 'Bạn đã khôi phục thành công !');
                }
                if ($act == "hardDelete") {
                    User::onlyTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('admin/user/list')->with('status', 'Bạn đã xóa vĩnh viễn thành công !');
                }
            }

            return redirect('admin/user/list')->with('status', 'Bạn không thể thao tác trên tài khoản của mình !');
        } else {
            return redirect('admin/user/list')->with('status', 'Bạn cần chọn phần tử cần thực thi !');
        }
    }
}
