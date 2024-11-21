@extends('layouts.app')

@section('content')
    <div>
        <div class="center">
            <h1>Danh sách sản phẩm</h1>
        </div>
        <div class="center">
            @if (session('success'))
                <div class="text-success font-bold text-medium">
                    {{session('success')}}
                </div>
            @elseif (session('error'))
                <div class="text-danger font-bold text-medium">
                    {{session('error')}}
                </div>
            @endif
        </div>
    
        <div>
            <form action="{{route('products.index')}}" method="get">
                <div class="form-group">
                    <input type="text" name="search" placeholder="Tìm kiếm..." value="{{request('search')}}" class="input-field margin-right">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
        </div>

        <div class="right">
            <a class="btn btn-primary" href="{{route('products.create')}}">Thêm</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Mô tả</th>
                    <th>Số lượng</th>
                    <th>Giá tiền</th>
                    <th>Danh mục</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->name}}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="Ảnh {{$product->name}}" style="max-width: 100px; max-height: 100px; height: auto; width: auto;">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{$product->description}}</td>
                        <td>{{$product->quantity}}</td>
                        <td>{{$product->price}}</td>
                        <td>{{$product->category->name}}</td>
                        <td style="text-align: center">
                            <a class="btn btn-primary" href="{{route('products.show', $product->id)}}">Xem</a>
                            <a class="btn btn-warning" href="{{route('products.edit', $product->id)}}">Sửa</a>
                            <form action="{{route('products.destroy', $product->id)}}" method="post" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm("Bạn có muốn xóa không?");
        }
    </script>
@endsection