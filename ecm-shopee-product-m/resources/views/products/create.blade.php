@extends('layouts.app')

@section('content')
    <div>
        <div class="center">
            <h1>Tạo mới</h1>
            @if ($errors->any)
                <div>
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{$error}}</li>
                        @endforeach</ul>    
                </div>            
            @endif

        </div>

        <form action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="margin-bottom">
                <label for="name">Sản phẩm:</label>
                <input class="input-field-full" type="text" id="name" name="name" required >
            </div>
            <div class="margin-bottom">
                <label for="image">Ảnh:</label>
                <input class="input-field-full" type="file" id="image" name="image" required >
            </div>
            <div class="margin-bottom">
                <label  for="description">Mô tả:</label>
                <textarea class="input-field-full" type="text" id="description" name="description" required ></textarea>
            </div>
            <div class="margin-bottom">
                <label for="quantity">Số lượng:</label>
                <input class="input-field-full" type="number" id="quantity" name="quantity" required >
            </div>
            <div class="margin-bottom">
                <label for="price">Giá tiền:</label>
                <input class="input-field-full" type="text" id="price" name="price" required >
            </div>
            <div class="margin-bottom">
                <label for="name">Danh mục:</label>
                <select class="input-field-full" name="category_id" id="categoryId">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>    
            </div>
            <button class="btn btn-primary" type="submit">Thêm</button>
            <a class="btn btn-danger" href="{{route('products.index')}}">Hủy</a>
        </form>
    </div>
@endsection