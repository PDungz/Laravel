@extends('layouts.app')

@section('title')

@section('content')
    <div>
        <div class="center">
            <h1>Danh sách danh mục</h1>
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

        <form action="{{route('categories.index')}}" method="get">  
            <div>
                <input class="input-field" type="text" name="search" placeholder="Tìm kiếm danh mục..." value="{{request('search')}}">
                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            </div>
        </form>
        
        <div class="right">
            <a class="btn btn-primary margin-top" href="{{route('categories.create')}}" > Thêm danh mục</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Danh mục</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{$category->id}}</td>
                        <td>{{$category->name}}</td>
                        <td>{{$category->description}}</td>
                        <td style="text-align: center">
                            <a class="btn btn-primary" href="{{route('categories.show', $category->id)}}">Xem</a>
                            <a class="btn btn-warning" href="{{route('categories.edit', $category->id)}}">Sửa</a>
                            <form action="{{route('categories.destroy', $category->id)}}" method="post" onsubmit="return confirmDelete();">
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