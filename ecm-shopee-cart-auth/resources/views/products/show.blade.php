@extends('layouts.app')

@section('content')
    <div>
        <h1>Hiển thị</h1>
        <form>
            @csrf
            <div class="margin-bottom">
                <label for="name">Sản phẩm:</label>
                <input class="input-field-full" type="text" id="name" name="name" value="{{$product->name}}" disabled>
            </div>
            <div class="margin-bottom">
                <label for="image">Ảnh:</label>
                <div class="margin-bottom"></div>
                @if ($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="Ảnh {{$product->name}}" style="max-width: 100px; max-height: 100px; height: auto; width: auto;">
                @else
                    <span>No Image</span>
                @endif
            </div>
            <div class="margin-bottom">
                <label  for="description">Mô tả:</label>
                <textarea class="input-field-full" type="text" id="description" name="description" disabled>{{$product->description}}</textarea>
            </div>
            <div class="margin-bottom">
                <label for="quantity">Số lượng:</label>
                <input class="input-field-full" type="number" id="quantity" name="quantity" value="{{$product->quantity}}" disabled>
            </div>
            <div class="margin-bottom">
                <label for="price">Giá tiền:</label>
                <input class="input-field-full" type="text" id="price" name="price" value="{{$product->price}}" disabled>
            </div>
            <div class="margin-bottom">
                <label for="category_id">Danh mục:</label>
                <input class="input-field-full" type="text" id="category_id" name="category_id" value="{{$product->category->name}}" disabled>
            </div>
            <a class="btn btn-danger" href="{{route('products.index')}}">Hủy</a>
        </form>
    </div>
    </div>
@endsection