@extends('layouts.app')

@section('content')
    <div>
        <div class="center">
            <h1>Tạo mới</h1>
            <div class="center margin-all">
                @if (session('success'))
                    <div class="text-success font-bold text-medium">
                        {{session('success')}}
                    </div>
                @elseif(session('error'))
                    <div class="text-success font-bold text-medium">
                        {{session('error')}}
        
                    </div>
                @endif      
            </div>
    

        </div>

        <form action="{{route('cars.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="margin-bottom">
                <label for="name">Name:</label>
                <input class="input-field-full" type="text" id="name" name="name" required >
            </div>
            <div class="margin-bottom">
                <label for="image">Ảnh:</label>
                <input class="input-field-full" type="file" id="image" name="image" required >
            </div>
            <div class="margin-bottom">
                <label  for="manufacturer">Manufacturer:</label>
                <textarea class="input-field-full" type="text" id="manufacturer" name="manufacturer" required ></textarea>
            </div>
            <div class="margin-bottom">
                <label for="price">Giá tiền:</label>
                <input class="input-field-full" type="text" id="price" name="price" required >
            </div>
            <div class="margin-bottom">
                <label for="quantity">So luong:</label>
                <input class="input-field-full" type="number" id="quantity" name="quantity" required >
            </div>
            <div class="margin-bottom">
                <label for="color">Color:</label>
                <input class="input-field-full" type="text" id="color" name="color" required >
            </div>
            <div class="margin-bottom">
                <label for="yearOfManufacture">YearOfManufacture:</label>
                <input class="input-field-full" type="date" id="yearOfManufacture" name="yearOfManufacture" required >
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
            <a class="btn btn-danger" href="{{route('cars.index')}}">Hủy</a>
        </form>
    </div>
@endsection