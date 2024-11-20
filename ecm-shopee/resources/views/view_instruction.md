# Hướng Dẫn Tổng Quan Cách Sử Dụng Views trong Laravel

Trong Laravel, **View** là nơi mà dữ liệu từ controller được trình bày trên giao diện người dùng. Views trong Laravel sử dụng **Blade Template Engine**, giúp bạn dễ dàng tạo các layout động, kiểm tra điều kiện, lặp qua các mảng và các tác vụ khác ngay trong view.

Dưới đây là hướng dẫn tổng quan về cách sử dụng views trong Laravel.

---

## 1. **Cấu Trúc Thư Mục Views**

Thư mục **views** chứa tất cả các file view trong ứng dụng của bạn. Thư mục này nằm trong thư mục `resources`:

```
resources/
    views/
        layouts/         // Chứa các layout chung của ứng dụng
        categories/      // Thư mục chứa views cho phần danh mục
            index.blade.php
            create.blade.php
            edit.blade.php
            show.blade.php
        home/             // Các view cho trang chủ
        auth/             // Views cho phần đăng nhập, đăng ký, v.v.
        errors/           // Các view cho lỗi
```

## 2. **Sử Dụng Blade Template**

Laravel sử dụng **Blade Template Engine** để viết views. Blade cung cấp các cú pháp đặc biệt giúp viết mã HTML một cách dễ dàng và hiệu quả.

### 2.1. **Kế Thừa Layouts**

Laravel cho phép bạn kế thừa layout chung từ một file master để tránh việc phải viết lại mã HTML cho tất cả các trang.

Ví dụ, tạo một file layout cơ bản:

```php
{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Application</title>
    <!-- Các stylesheet -->
</head>
<body>
    <nav>
        <!-- Navbar code here -->
    </nav>

    <div class="container">
        @yield('content') <!-- Nơi nội dung của các views con sẽ được chèn vào -->
    </div>

    <!-- Các script -->
</body>
</html>
```

Trong các views, bạn có thể kế thừa layout này như sau:

```php
{{-- resources/views/categories/index.blade.php --}}

@extends('layouts.app') {{-- Kế thừa layout chung --}}

@section('content') {{-- Định nghĩa phần content cho trang này --}}
    <h1>Danh Sách Danh Mục</h1>
    <!-- Nội dung danh sách danh mục -->
@endsection
```

### 2.2. **Các Câu Lệnh Blade Cơ Bản**

-   **Hiển thị dữ liệu từ controller:**

```php
<h1>{{ $category->name }}</h1> {{-- Dùng {{}} để hiển thị dữ liệu --}}
```

-   **Câu lệnh điều kiện `@if`, `@else`:**

```php
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
```

-   **Lặp qua mảng với `@foreach`:**

```php
@foreach($categories as $category)
    <p>{{ $category->name }}</p>
@endforeach
```

-   **Đặt giá trị mặc định nếu không có giá trị:**

```php
<p>{{ $category->description ?? 'Không có mô tả' }}</p>
```

-   **Form gửi dữ liệu:**

```php
<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Tên danh mục">
    <button type="submit">Lưu</button>
</form>
```

-   **Để đảm bảo không có lỗi CSRF, Laravel yêu cầu mỗi form có token `@csrf` để ngăn chặn tấn công CSRF (Cross-Site Request Forgery).**

---

## 3. **Sử Dụng Blade @include để Chia Sẻ Phần Giao Diện**

Bạn có thể tái sử dụng các phần giao diện (như header, footer) giữa các trang bằng cách sử dụng `@include`.

```php
{{-- resources/views/layouts/app.blade.php --}}
<body>
    @include('layouts.navbar') {{-- Chèn navbar --}}
    @yield('content')
</body>

{{-- resources/views/layouts/navbar.blade.php --}}
<nav>
    <ul>
        <li><a href="/">Trang chủ</a></li>
        <li><a href="/categories">Danh mục</a></li>
    </ul>
</nav>
```

---

## 4. **Truyền Dữ Liệu từ Controller đến View**

Controller trong Laravel sẽ trả về dữ liệu từ cơ sở dữ liệu hoặc từ các logic khác để truyền vào view. Bạn có thể sử dụng `compact()` hoặc mảng để truyền dữ liệu.

**Ví dụ:**

```php
// Trong Controller
public function index()
{
    $categories = Category::all(); // Lấy tất cả danh mục
    return view('categories.index', compact('categories')); // Truyền dữ liệu vào view
}
```

Trong view `categories.index`:

```php
@foreach($categories as $category)
    <p>{{ $category->name }}</p>
@endforeach
```

---

## 5. **Sử Dụng Templating Để Quản Lý Giao Diện Động**

Laravel cung cấp cách dễ dàng để xử lý dữ liệu động trong views, chẳng hạn như:

-   **Form Validation Error:**

```php
<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Tên danh mục" value="{{ old('name') }}">
    @error('name') {{-- Hiển thị lỗi nếu có --}}
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <button type="submit">Lưu</button>
</form>
```

-   **Link với Route:**

```php
<a href="{{ route('categories.create') }}">Tạo danh mục mới</a>
```

---

## 6. **Trang Lỗi 404 và Các Trang Tùy Chỉnh**

Laravel tự động cung cấp các trang lỗi như lỗi 404, 500. Tuy nhiên, bạn có thể tùy chỉnh các trang lỗi này trong thư mục `resources/views/errors`.

Ví dụ, tạo một trang lỗi 404:

```php
{{-- resources/views/errors/404.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Không tìm thấy trang</title>
</head>
<body>
    <h1>Trang không tồn tại!</h1>
    <p>Rất tiếc, chúng tôi không thể tìm thấy trang mà bạn yêu cầu.</p>
</body>
</html>
```

---

## Kết Luận

**Views** trong Laravel là một phần quan trọng trong việc hiển thị dữ liệu và giao diện người dùng. Laravel cung cấp một bộ công cụ mạnh mẽ để bạn có thể dễ dàng xây dựng các trang web động, quản lý layout, xử lý form, hiển thị dữ liệu, và xử lý các lỗi một cách dễ dàng. Các views trong Laravel cũng rất dễ dàng mở rộng và tái sử dụng nhờ Blade Template Engine.
