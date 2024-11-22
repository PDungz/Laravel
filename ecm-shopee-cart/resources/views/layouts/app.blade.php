<!-- views/layout/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyShop')</title>
    <!-- Bao gồm Bootstrap CSS hoặc các file CSS khác -->
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet"> --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        /* Class cho căn giữa (center) */
        .center {
            display: flex;
            justify-content: center;
            /* align-items: center; */
            /* text-align: center; */
        }

        .left {
            display: flex;
            justify-content: left;
            /* text-align: left; */
        }

        .right {
            display: flex;
            justify-content: right;
            /* text-align: right; */
        }

        /* Class cho margin */
        .margin-top {
            margin-top: 20px;
        }

        .margin-bottom {
            margin-bottom: 20px;
        }

        .margin-left {
            margin-left: 20px;
        }

        .margin-right {
            margin-right: 20px;
        }

        .margin-all {
            margin: 20px;
        }

        /* Class cho padding */
        .padding-top {
            padding-top: 20px;
        }

        .padding-bottom {
            padding-bottom: 20px;
        }

        .padding-left {
            padding-left: 20px;
        }

        .padding-right {
            padding-right: 20px;
        }

        .padding-all {
            padding: 20px;
        }

        /* Font size */
        .text-small {
            font-size: 12px;
        }

        .text-medium {
            font-size: 16px;
        }

        .text-large {
            font-size: 24px;
        }

        .text-xlarge {
            font-size: 32px;
        }

        /* Font weight */
        .font-light {
            font-weight: 300;
        }

        .font-normal {
            font-weight: 400;
        }

        .font-bold {
            font-weight: 700;
        }

        /* Text color */
        .text-primary {
            color: #007bff;
        }

        .text-secondary {
            color: #6c757d;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-info {
            color: #17a2b8;
        }

        .text-light {
            color: #f8f9fa;
        }

        .text-dark {
            color: #343a40;
        }

        /* Text alignment */
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        /* Text decoration */
        .text-underline {
            text-decoration: underline;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-lowercase {
            text-transform: lowercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        /* Line height */
        .line-height-normal {
            line-height: 1.5;
        }

        .line-height-loose {
            line-height: 1.8;
        }

        .line-height-tight {
            line-height: 1.2;
        }


        /* Định dạng cho label */
        .label {
            display: block; /* Đảm bảo label chiếm một dòng */
            margin-bottom: 5px; /* Khoảng cách dưới label */
            font-size: 16px; /* Kích thước chữ cho label */
            font-weight: bold; /* Đậm cho label */
            color: #333; /* Màu chữ cho label */
        }

        /* Định dạng cho input */
        .input-field-full {
            width: 100%; /* Chiếm toàn bộ chiều rộng của container */
            padding: 8px; /* Khoảng cách bên trong input */
            font-size: 16px; /* Kích thước chữ trong input */
            border: 1px solid #ccc; /* Viền cho input */
            border-radius: 4px; /* Bo góc cho input */
            box-sizing: border-box; /* Đảm bảo padding không vượt ngoài kích thước */
        }

        .input-field {
            padding: 8px; /* Khoảng cách bên trong input */
            font-size: 16px; /* Kích thước chữ trong input */
            border: 1px solid #ccc; /* Viền cho input */
            border-radius: 4px; /* Bo góc cho input */
            box-sizing: border-box; /* Đảm bảo padding không vượt ngoài kích thước */
        }

        /* Căn chỉnh form */
        .form-group {
            margin-bottom: 20px; /* Khoảng cách giữa các phần tử trong form */
            display: flex; /* Dùng flexbox để dễ dàng căn chỉnh các phần tử */
            flex-direction: row; /* Đặt các phần tử theo chiều dọc */
            align-items: flex-start; /* Căn chỉnh sang bên trái */
        }

        .input-field:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
        }

        /* Dành cho button (a) */
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-warning {
            background-color: #ffa500; /* Màu cam nhạt */
        }

        .btn-warning:hover {
            background-color: #e0a800; /* Màu cam đậm */
        }

        /* Dành cho bảng */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        .table th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            white-space: nowrap; /* Đảm bảo nội dung không xuống dòng */
        }

        .table td a,
        .table td form {
            display: inline-block; /* Đặt các nút và form trên cùng một dòng */
            margin-right: 5px; /* Khoảng cách giữa các nút */
        }

        .table td form {
            margin: 0; /* Xóa margin mặc định của form */
        }

        .table td form button {
            margin: 0; /* Xóa margin của nút trong form */
            display: inline-block; /* Đảm bảo nút giữ nguyên chiều cao */
            vertical-align: middle; /* Căn giữa nút với các liên kết khác */
        }

        

        nav {
            background-color: #007bff;
            color: white;
            padding: 0.5em 2em;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 1em;
        }

        nav a:hover {
            text-decoration: underline;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            display: inline;
            margin-right: 15px;
        }

    </style>
</head>

<body>
    <nav >
        <div>
            <ul>
                <li>
                    <a href="{{ route('products.index') }}">Sản phảm</a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}">Danh mục</a>
                </li>
                <li>
                    <a href="{{ route('carts.index') }}">Giỏ hàng</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="center">
        @yield('content')
    </div>
     <!-- Scripts -->
     @yield('scripts')
    <!-- Bao gồm các file JS cần thiết -->
    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
</body>

</html>