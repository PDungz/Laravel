@extends('layouts.app')

@section('content')
    <div>
        <h1>Hiển thị</h1>
        <div>
            <strong>Danh mục:</strong> {{$car->name}}
        </div>
        <div>
            <strong>Mô tả:</strong> {{$car->manufacturer}}
        </div>
    </div>
@endsection