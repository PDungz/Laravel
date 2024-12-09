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

        <form action="{{route('cars.update', $car->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="margin-bottom">
                <label for="name">Name:</label>
                <input class="input-field-full" type="text" id="name" name="name" value="{{$car->name}}" required >
            </div>
            <div class="margin-bottom">
                <label for="image">Ảnh:</label>
                <div class="margin-bottom"></div>
                @if ($car->image)
                    <img id="imagePreview" src="{{ asset('storage/'.$car->image) }}" alt="Ảnh {{$car->name}}" style="max-width: 100px; max-height: 100px; height: auto; width: auto;">
                @else
                    <span>No Image</span>
                @endif
                <input class="input-field-full" type="file" id="image" name="image" required onchange="previewImage(event)">
            </div>
            <div class="margin-bottom">
                <label  for="manufacturer">Manufacturer:</label>
                <textarea class="input-field-full" type="text" id="manufacturer" name="manufacturer"  required >{{$car->manufacturer}}</textarea>
            </div>
            <div class="margin-bottom">
                <label for="price">Giá tiền:</label>
                <input class="input-field-full" type="text" id="price" name="price" value="{{$car->price}}" required >
            </div>
            <div class="margin-bottom">
                <label for="quantity">So luong:</label>
                <input class="input-field-full" type="number" id="quantity" name="quantity" value="{{$car->quantity}}" required >
            </div>
            <div class="margin-bottom">
                <label for="color">Color:</label>
                <input class="input-field-full" type="text" id="color" name="color" value="{{$car->color}}" required >
            </div>
            <div class="margin-bottom">
                <label for="yearOfManufacture">YearOfManufacture:</label>
                <input class="input-field-full" type="date" id="yearOfManufacture" value="{{$car->yearOfManufacture}}" name="yearOfManufacture" required >
            </div>
            <div class="margin-bottom">
                <label for="name">Danh mục:</label>
                <select class="input-field-full" name="category_id" id="categoryId">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}" {{ $car->category_id == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                    @endforeach
                </select>    
            </div>
            <button class="btn btn-primary" type="submit">Cap nhat</button>
            <a class="btn btn-danger" href="{{route('cars.index')}}">Hủy</a>
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