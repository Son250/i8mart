@extends('layouts.admin');

@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="card-header font-weight-bold">
                        Danh mục sản phẩm
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/product/cat/add') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên hãng</label>
                                <input class="form-control" type="text" name="name" id="name">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control " name="description" id="description" cols="30" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Tên người tạo</label>
                                <select class="form-control" name="user_id" id="">
                                    <option value="">Chọn</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach

                                </select>
                                @error('user_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Danh mục lớn</label>
                                <select class="form-control" name="parent_id" id="">
                                    <option value="">Chọn</option>

                                    @foreach ($categories as $item)
                                        @if ($item->parent_id == '')
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                    <small class="text-black">Nếu bạn muốn tên hãng là danh mục lớn thì bạn để trống trường này !</small>
                            </div>
                            <button type="submit" value="Thêm mới" name="btnSubmit" class="btn btn-primary">Thêm
                                mới</button>
                        </form>
                    </div>
                </div>

                {{-- /// --}}
           
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên hãng</th>
                                    <th scope="col">Mô tả</th>
                                    <th scope="col">Tên người tạo</th>
                                    <th scope="col">Danh mục lớn</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $k => $cat)
                                    {{-- @if ($cat->parent_id != '') --}}
                                    <tr>
                                        <th scope="row">{{ $k + 1 }}</th>
                                        <td>{{ $cat->name }}</td>
                                        <td>{{ $cat->description }}</td>
                                        <td>{{ $cat->user_id }}</td>
                                        <td>
                                            @if ($cat->parent_id != '')
                                                {{ $cat->parent_name }}
                                            @else
                                                Danh mục cha
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('editCat', $cat->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a> {{-- icon edit --}}
                                            <a href="{{ route('deleteCat', $cat->id) }}"
                                                onclick="return confirm('Bạn chắc chắn muốn xóa không ?')"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a> {{-- icon Xóa cứng --}}
                                        </td>
                                    </tr>
                                    {{-- @endif --}}
                                @endforeach
                            </tbody>
                        </table>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
