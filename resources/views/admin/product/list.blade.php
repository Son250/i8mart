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
                <h5 class="m-0 ">Danh sách sản phẩm</h5>
                <div class="form-search form-inline">
                    <form action="{{ url('admin/product/list') }}">
                        <input type="" class="form-control form-search" name="keyword"
                            placeholder="Tìm kiếm theo tên..." value="{{ request()->input('keyword') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ url('admin/product/add') }}" class="text-primary">Thêm mới<span
                            class="text-muted"></span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích hoạt<span
                            class="text-muted">({{ $count_active }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu hóa<span
                            class="text-muted">({{ $count_trash }})</span></a>
                </div>
                <form action="{{ url('admin/product/action') }}" method="">
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
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">STT</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($products->total() > 0)
                                @foreach ($products as $product)
                                    <tr class="">
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $product->id }}">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img width="100" class="img-thumbnail" src="{{ asset($product->image) }}"
                                                alt=""></td>
                                        <td><a href="#">{{ $product->name }}</a></td>
                                        <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>{!! $product->created_at !!}</td>
                                        <td>
                                            @if ($product->quantity > 0)
                                                <span class="badge badge-success">
                                                    Còn hàng({{ $product->quantity }})
                                                </span>
                                            @else
                                                <span class="badge badge-dark">
                                                    Hết hàng({{ $product->quantity }})
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->deleted_at != '')
                                                <a href="{{ route('restoreProduct', $product->id) }}"
                                                    class="btn btn-info btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Restore">
                                                    <i class="fas fa-trash-restore"></i></a> {{-- icon Khôi phục  --}}
                                            @else
                                                <a href="{{ route('editProduct', $product->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>     {{-- icon sửa --}}
                                            @endif

                                            @if ($product->deleted_at == '')
                                                {{-- icon xóa --}}
                                                <a href="{{ route('deleteProduct', $product->id) }}"
                                                    onclick="return confirm('Bạn chắc chắn muốn xóa không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                                {{-- xóa mềm --}}
                                            @else
                                                <a href="{{ route('hardDeleteProduct', $product->id) }}"
                                                    onclick="return confirm('Bạn chắc chắn muốn xóa không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                                {{-- xóa cứng --}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                {{-- <p>Không tìm thấy bản ghi nào</p> --}}
                                <td colspan="9">Không tìm thấy bản ghi nào !</td>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
