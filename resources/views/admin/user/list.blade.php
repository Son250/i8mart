@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách thành viên</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" class="form-control form-search" name="keyword"
                            placeholder="Tìm kiếm theo tên..." value="{{ request()->input('keyword') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ url('admin/user/add') }}" class="text-primary">Thêm mới<span class="text-muted"></span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích hoạt<span
                            class="text-muted">({{ $count_active }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu hóa<span
                            class="text-muted">({{ $count_trash }})</span></a>
                </div>
                <form action="{{ url('admin/user/action') }}" method="">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="act">
                            <option value="">Chọn</option>
                            @if (request()->status == 'trash')
                                <option value="hardDelete">Xóa vĩnh viễn</option>
                                <option value="restore">Khôi phục</option>
                            @else
                                <option value="delete">Xóa tạm thời</option>
                            @endif


                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">STT</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">SĐT</th>
                                <th scope="col">Địa chỉ</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->total() > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $user->id }}">
                                        </td>
                                        <td scope="row">{{ $loop->iteration }}</td> <!-- Sử dụng số thứ tự -->
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->permission }}</td>
                                        <td>{{ $user->created_at }}</td>

                                        <td>
                                            @if ($user->deleted_at != '')
                                                <a href="{{ route('restoreUser', $user->id) }}"
                                                    class="btn btn-info btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Restore">
                                                    <i class="fas fa-trash-restore"></i></a> {{-- icon Khôi phục  --}}
                                            @else
                                                <a href="{{ route('editUser', $user->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a> {{-- icon edit --}}
                                            @endif




                                            @if (Auth::id() != $user->id)
                                                @if ($user->deleted_at == '')
                                                    <a href=" 
                                                {{ route('deleteUser', $user->id) }}"
                                                        onclick="return confirm('Bạn chắc chắn muốn xóa không ?')"
                                                        class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                            class="fa fa-trash"></i></a> {{-- icon Xóa mềm --}}
                                                @else
                                                    <a href="{{ route('hardDeleteUser', $user->id) }}"
                                                        onclick="return confirm('Bạn chắc chắn muốn xóa không ?')"
                                                        class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Delete">
                                                        <i class="fa fa-trash"></i></a> {{-- icon Xóa cứng --}}
                                                @endif
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                {{-- <p>Không tìm thấy bản ghi nào</p> --}}
                                <td colspan="7">Không tìm thấy bản ghi nào !</td>
                            @endif


                        </tbody>
                    </table>
                </form>
                {{ $users->links() }}

            </div>
        </div>
    </div>
@endsection
