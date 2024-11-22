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
                        @endforeach</ul>    
                </div>            
            @endif

        </div>

        <form action="{{route('admin.categories.update', $category->id)}}" method="post">
            @csrf
            @method('PUT')
            <div>
                <label for="name">Danh mục:</label>
                <input class="input-field-full" type="text" id="name" name="name" value="{{old('name', $category->name)}}"  required>
            </div>
            <div>
                <label  for="description">Mô tả:</label>
                <textarea class="input-field-full" type="text" id="description" name="description" required >{{ old('description', $category->description) }}</textarea>
            </div>
            <button class="btn btn-primary" type="submit">Cập nhật</button>
            <a class="btn btn-danger" href="{{route('admin.categories.index')}}">Hủy</a>
        </form>
    </div>
@endsection