@extends('layouts.app')

@section('content')
    <div>
        <h1>Hiển thị</h1>
        <div>
            <strong>Danh mục:</strong> {{$category->name}}
        </div>
        <div>
            <strong>Mô tả:</strong> {{$category->description}}
        </div>
    </div>
@endsection