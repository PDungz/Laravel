{{-- resources/views/categories/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Chỉnh Sửa Danh Mục')

@section('content')
    <div class="container">
        <h1 class="mb-4">Chỉnh Sửa Danh Mục</h1>

        {{-- Hiển thị thông báo lỗi nếu có --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form chỉnh sửa danh mục --}}
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Tên Danh Mục</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô Tả</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description', $category->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
