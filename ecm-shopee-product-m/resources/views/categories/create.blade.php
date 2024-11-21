@extends('layouts.app')

@section('title', "Tao danh muc moi")

@section('content')
    <div>
        <div class="center">
            <h1>Tạo danh mục mới</h1>
            @if ($errors->any)
                <div>
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{$error}}</li>
                        @endforeach</ul>    
                </div>            
            @endif

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