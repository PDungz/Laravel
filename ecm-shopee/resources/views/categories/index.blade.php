{{-- resources/views/categories/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Danh sách danh mục')

@section('content')
    <div class="container">
        <h1 class="mb-4">Danh sách Danh Mục</h1>

        {{-- Hiển thị thông báo thành công hoặc lỗi --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <!-- Form tìm kiếm -->
        <form method="GET" action="{{ route('categories.index') }}" class="mb-3">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control" 
                    placeholder="Tìm kiếm danh mục..." 
                    value="{{ request('search') }}"
                >
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>
        {{-- Nút tạo mới --}}
        <a href="{{ route('categories.create') }}" class="btn btn-primary mb-5">Tạo Danh Mục Mới</a>
        
        {{-- Liệt kê danh mục --}}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <th scope="row">{{ $category->id }}</th>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-primary btn-sm">Xem</a>
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
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
        return confirm('Are you sure you want to delete this category? This action cannot be undone.');
    }
</script>
@endsection
