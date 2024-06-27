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
                        Cập nhật danh mục
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/product/cat/update', $cat->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên hãng</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ $cat->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control " name="description" id="description" cols="30" rows="4">{{ $cat->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Tên người tạo</label>
                                <select class="form-control" name="user_id" id="">
                                    <option value="">Chọn</option>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->id }}"
                                            @if ($item->id == $cat->user_id) selected @endif>{{ $item->name }}
                                        </option>
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
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $cat->parent_id) selected @endif>{{ $item->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" value="Cập nhật" name="btnSubmit" class="btn btn-primary">Cập nhật
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div> --}}
        </div>

    </div>
@endsection
