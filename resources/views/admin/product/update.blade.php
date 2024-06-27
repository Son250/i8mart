@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">

            <div class="card-header font-weight-bold">
                Cập nhật sản phẩm
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('admin/product/update', $product->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ $product->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Giá</label>
                                <input class="form-control" type="text" name="price" id="price"
                                    value="{{ $product->price }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="intro">Mô tả sản phẩm</label>
                                <textarea name="description" class="form-control" id="intro" cols="30" rows="5">{{ $product->description }}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="image">Ảnh đại diện</label>
                                <input class="form-control" type="file" name="image" id="image">
                                <img width="100" src="{{ asset($product->image) }}" alt="">
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="images">Ảnh chi tiết </label>
                                <input class="form-control" type="file" name="images[]" id="images" multiple>
                                @foreach ($product->images as $img)
                                    <img width="100" src="{{ asset($img->image_url) }}" alt="">
                                @endforeach

                                @error('images')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Số lượng</label>
                        <input class="form-control" type="number" name="quantity" id=""
                            value="{{ $product->quantity }}">
                        @error('quantity')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Người tạo</label>
                        <select class="form-control" id="" name="user_id">
                            <option value="">Chọn </option>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" @if ($item->id == $product->user_id) selected @endif>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select class="form-control" id="" name="category_id">
                            <option value="">Chọn danh mục</option>
                            {{-- @foreach ($categories as $item)
                                <option value="{{ $item->id }}" @if ($item->id == $product->category_id) selected @endif>
                                    {{ $item->name }}</option>
                            @endforeach --}}
                            @foreach ($categories as $item)
                            @if ($item->parent_id != '')
                                <option value="{{ $item->id }}" @if ($item->id == $product->category_id) selected @endif>{{ $item->name }} - {{ $item->parent_name }}
                                </option>
                            @endif
                        @endforeach
                        </select>
                        @error('category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                                value="option1" checked>
                            <label class="form-check-label" for="exampleRadios1">
                                Chờ duyệt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                                value="option2">
                            <label class="form-check-label" for="exampleRadios2">
                                Công khai
                            </label>
                        </div>
                    </div> --}}

                    <button type="submit" name="btnSubmit" value="Cập nhật" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
