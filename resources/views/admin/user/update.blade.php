@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật người dùng
            </div>
            <div class="card-body">
                <form action="{{ url('admin/user/update', $user->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ $user->name }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" disabled type="text" name="email" id="email"
                                    value="{{ $user->email }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">SĐT</label>
                                <input class="form-control" type="text" name="phone" id="phone"
                                    value="{{ $user->phone }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Thay đổi mật khẩu</label>
                        <input class="form-control" disabled type="text"
                            value="Truy cập 'Thông tin tài khoản' để đổi ">

                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input class="form-control" type="text" name="address" id="address"
                            value="{{ $user->address }}">
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="">Chọn quyền</label>
                        <select class="form-control" id="" name="permission">
                            <option value="">Chọn quyền</option>
                            <option value="admin" @if ($user->permission == 'admin') selected @endif>admin</option>
                            <option value="user" @if ($user->permission == 'user') selected @endif>user</option>

                        </select>
                    </div>

                    <button type="submit" value="Cập nhật" name="btnSubmit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
