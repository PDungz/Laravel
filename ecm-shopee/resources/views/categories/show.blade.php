{{-- resources/views/categories/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Chi Tiết Danh Mục')

@section('content')
    <div class="container">
        <h1 class="mb-4">Chi Tiết Danh Mục</h1>

        <div class="mb-3">
            <strong>Tên:</strong> {{ $category->name }}
        </div>

        <div class="mb-3">
            <strong>Mô Tả:</strong> {{ $category->description }}
        </div>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Trở lại</a>
    </div>
@endsection
