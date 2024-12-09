@extends('layouts.app')

@section('title')

@section('content')
    <div>
        <div class="center">
            <h1>Danh sách car</h1>
        </div>
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

        <form action="{{route('cars.index')}}" method="get">  
            <div>
                <input class="input-field" type="text" name="search" placeholder="Tìm kiếm danh mục..." value="{{request('search')}}">
                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            </div>
        </form>
        
        <div class="right">
            <a class="btn btn-primary margin-top" href="{{route('cars.create')}}" > Thêm danh mục</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Manufacturer</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Color</th>
                    <th>YearOfManufacture</th>
                    <th>Category</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cars as $car)
                    <tr>
                        <td>{{$car->id}}</td>
                        <td>{{$car->name}}</td>
                        <td>
                            @if ($car->image)
                                <img src="{{ asset('storage/'.$car->image) }}" alt="Ảnh {{$car->name}}" style="max-width: 100px; max-height: 100px; height: auto; width: auto;">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{$car->manufacturer}}</td>
                        <td>{{$car->quantity}}</td>
                        <td>{{$car->price}}</td>
                        <td>{{$car->color}}</td>
                        <td>{{$car->yearOfManufacture}}</td>
                        <td>{{$car->category->name}}</td>
                        <td style="text-align: center">
                            <a class="btn btn-primary" href="{{route('cars.show', $car->id)}}">Xem</a>
                            <a class="btn btn-warning" href="{{route('cars.edit', $car->id)}}">Sửa</a>
                            <form action="{{route('cars.destroy', $car->id)}}" method="post" onsubmit="return confirmDelete();">
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
            return confirm("Bạn có muốn xóa danh mục này không?");
        }
    </script>
@endsection