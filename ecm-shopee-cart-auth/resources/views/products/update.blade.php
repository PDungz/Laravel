@extends('layouts.app')

@section('title', "Cập nhật")

@section('content')
    <div>
        <div class="center">
            <h1>Cập nhật</h1>
            @if ($errors->any)
                <div>
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>    
                </div>            
            @endif
        </div>

        <form action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="margin-bottom">
                <label for="name">Sản phẩm:</label>
                <input class="input-field-full" type="text" id="name" name="name" value="{{$product->name}}" required>
            </div>
            <div class="margin-bottom">
                <label for="image">Ảnh:</label>
                <div class="margin-bottom"></div>
                @if ($product->image)
                    <img id="imagePreview" src="{{ asset('storage/'.$product->image) }}" alt="Ảnh {{$product->name}}" style="max-width: 100px; max-height: 100px; height: auto; width: auto;">
                @else
                    <span>No Image</span>
                @endif
                <input class="input-field-full" type="file" id="image" name="image" onchange="previewImage(event)">
            </div>
            <div class="margin-bottom">
                <label for="description">Mô tả:</label>
                <textarea class="input-field-full" type="text" id="description" name="description" required>{{$product->description}}</textarea>
            </div>
            <div class="margin-bottom">
                <label for="quantity">Số lượng:</label>
                <input class="input-field-full" type="number" id="quantity" name="quantity" value="{{$product->quantity}}" required>
            </div>
            <div class="margin-bottom">
                <label for="price">Giá tiền:</label>
                <input class="input-field-full" type="text" id="price" name="price" value="{{$product->price}}" required>
            </div>
            <div class="margin-bottom">
                <label for="entry_date">Ngày nhập:</label>
                <input class="input-field-full" type="date" id="entry_date" name="entry_date" value="{{$product->entry_date}}" required >
            </div>
            <div class="margin-bottom">
                <label for="name">Danh mục:</label>
                <select class="input-field-full" name="category_id" id="categoryId">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                    @endforeach
                </select>    
            </div>
            <button class="btn btn-primary" type="submit" id="btnSubmit">Cập nhật</button>
            <a class="btn btn-danger" href="{{route('products.index')}}">Hủy</a>
        </form>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
