@extends('layouts.app')

@section('title', "Tao danh muc moi")

@section('content')
    <div>
        <div class="center">
            <h1>Tạo danh mục mới</h1>
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

        <form action="{{route('categories.store')}}" method="post">
            @csrf
            <div>
                <label for="name">Danh mục:</label>
                <input class="input-field-full" type="text" id="name" name="name" required >
            </div>
            <div class="margin-top">
                <label  for="description">Mô tả:</label>
                <textarea class="input-field-full" type="text" id="description" name="description" required ></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Thêm</button>
            <a class="btn btn-danger" href="{{route('categories.index')}}">Hủy</a>
        </form>
    </div>
@endsection