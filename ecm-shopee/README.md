<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/PDungz">
  <img src="https://img.shields.io/badge/-GitHub%20Phùng Văn Dũng-black?logo=github&logoColor=white" alt="GitHub Channel">
</a>
<a href="https://youtube.com/@pvdnocode3623?si=o3UX8WHisI5mAVCu">
  <img src="https://img.shields.io/badge/-Youtube%20Pvd NoCode-black?logo=youtube&logoColor=red" alt="Youtube Channel">
</a>
</p>

### **Hướng dẫn tạo CRUD quản lý danh mục sản phẩm (Category)**

Hướng dẫn này sẽ giúp bạn xây dựng một hệ thống CRUD (Create, Read, Update, Delete) để quản lý danh mục sản phẩm bằng Laravel. Dưới đây là từng bước thực hiện cùng ví dụ minh họa cụ thể.

---

### **1. Tạo dự án Laravel**

#### **Giải thích**:

Laravel là một framework PHP mạnh mẽ giúp tạo ra các ứng dụng web nhanh chóng. Trước tiên, bạn cần cài đặt Laravel và thiết lập cơ sở dự án.

#### **Các bước**:

1. **Cài đặt Laravel**:
   Laravel được cài đặt thông qua Composer:

    ```bash
    composer create-project laravel/laravel category-crud
    ```

2. **Cấu hình cơ sở dữ liệu**:
   Laravel sử dụng file `.env` để quản lý các cấu hình. Mở file `.env` và thay đổi các thông số cấu hình cơ sở dữ liệu:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=category_crud
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```

3. **Tạo cơ sở dữ liệu**:
   Tạo một cơ sở dữ liệu MySQL có tên `category_crud`. Bạn có thể sử dụng công cụ quản lý cơ sở dữ liệu hoặc câu lệnh SQL sau:
    ```sql
    CREATE DATABASE category_crud;
    ```

#### **Ví dụ**:

Sau khi cài đặt và cấu hình, chạy server Laravel bằng lệnh:

```bash
php artisan serve
```

Bạn có thể truy cập ứng dụng tại `http://127.0.0.1:8000`.

---

### **2. Tạo Model, Controller và Migration**

#### **Giải thích**:

-   **Model**: Quản lý dữ liệu từ cơ sở dữ liệu.
-   **Controller**: Chứa các logic xử lý.
-   **Migration**: Quản lý cấu trúc bảng trong cơ sở dữ liệu.

#### **Các bước**:

1. Tạo các tệp bằng lệnh Artisan:

    ```bash
    php artisan make:model Category -mcr
    ```

    - `-m`: Tạo Migration.
    - `-c`: Tạo Controller.
    - `-r`: Tạo Controller theo chuẩn RESTful.

2. Định nghĩa bảng `categories` trong file Migration:
   Mở file `database/migrations/xxxx_xx_xx_create_categories_table.php` và thêm các cột:

    ```php
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    ```

3. Chạy Migration để tạo bảng `categories` trong cơ sở dữ liệu:
    ```bash
    php artisan migrate
    ```

#### **Ví dụ**:

Sau khi chạy Migration, bảng `categories` sẽ được tạo trong cơ sở dữ liệu với các cột: `id`, `name`, `description`, `created_at`, `updated_at`.

---

### **3. Thiết lập Routes**

#### **Giải thích**:

Routes định nghĩa các URL trong ứng dụng và liên kết chúng với các phương thức trong Controller.

#### **Các bước**:

1. Mở file `routes/web.php` và thêm route tài nguyên cho `CategoryController`:

    ```php
    use App\Http\Controllers\CategoryController;

    Route::resource('categories', CategoryController::class);
    ```

2. Lệnh trên sẽ tự động tạo các route cho CRUD:
    - `GET /categories` → `index()`
    - `GET /categories/create` → `create()`
    - `POST /categories` → `store()`
    - `GET /categories/{id}` → `show()`
    - `GET /categories/{id}/edit` → `edit()`
    - `PUT /categories/{id}` → `update()`
    - `DELETE /categories/{id}` → `destroy()`

#### **Ví dụ**:

Truy cập `http://127.0.0.1:8000/categories` sẽ hiển thị danh sách các danh mục sản phẩm.

---

### **4. Xử lý Logic trong Controller**

#### **Giải thích**:

`CategoryController` chứa các phương thức xử lý CRUD:

-   `index`: Hiển thị danh sách các danh mục sản phẩm.
-   `create`: Hiển thị form tạo danh mục sản phẩm.
-   `store`: Lưu danh mục sản phẩm vào cơ sở dữ liệu.
-   `edit`: Hiển thị form chỉnh sửa danh mục sản phẩm.
-   `update`: Cập nhật thông tin danh mục sản phẩm.
-   `destroy`: Xóa danh mục sản phẩm.

#### **Các bước**:

1. Cập nhật `CategoryController` với logic cần thiết:

    - **Hiển thị danh sách các danh mục sản phẩm**:

        ```php
        public function index()
        {
            $categories = Category::all();
            return view('categories.index', compact('categories'));
        }
        ```

    - **Tạo danh mục sản phẩm mới**:

        ```php
        public function create()
        {
            return view('categories.create');
        }
        ```

    - **Lưu danh mục sản phẩm**:

        ```php
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required',
            ]);

            Category::create($request->all());

            return redirect()->route('categories.index')->with('success', 'Danh mục sản phẩm đã được thêm!');
        }
        ```

#### **Ví dụ**:

Truy cập `http://127.0.0.1:8000/categories/create` để tạo danh mục sản phẩm mới.

---

### **5. Tạo Giao Diện (Views)**

#### **Giải thích**:

Views hiển thị dữ liệu từ Controller và cung cấp giao diện cho người dùng.

#### **Các bước**:

1. Tạo thư mục `resources/views/categories`.
2. Tạo các file giao diện:

    - `index.blade.php`: Hiển thị danh sách các danh mục sản phẩm.
    - `create.blade.php`: Hiển thị form tạo danh mục sản phẩm.

3. Ví dụ về `index.blade.php`:
    ```html
    <!DOCTYPE html>
    <html>
        <head>
            <title>Quản lý danh mục sản phẩm</title>
        </head>
        <body>
            <h1>Danh sách danh mục sản phẩm</h1>
            <a href="{{ route('categories.create') }}"
                >Thêm danh mục sản phẩm</a
            >
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Miêu tả</th>
                    <th>Hành động</th>
                </tr>
                @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}"
                            >Chỉnh sửa</a
                        >
                        <form
                            action="{{ route('categories.destroy', $category->id) }}"
                            method="POST"
                            style="display:inline;"
                        >
                            @csrf @method('DELETE')
                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </body>
    </html>
    ```

---

### **6. Kiểm tra ứng dụng**

1. Chạy server:

    ```bash
    php artisan serve
    ```

2. Kiểm tra các URL:
    - Danh sách danh mục sản phẩm: `http://127.0.0.1:8000/categories`
    - Thêm danh mục sản phẩm: `http://127.0.0.1:8000/categories/create`

---

### **Tổng kết**

Hệ thống CRUD quản lý danh mục sản phẩm đã hoàn thành với các bước:

1. Tạo dự án Laravel.
2. Thiết lập cơ sở dữ liệu.
3. Tạo Model, Controller, và Migration.
4. Cài đặt Routes.
5. Viết logic trong Controller.
6. Tạo giao diện với Views.

Chúc bạn thành công trong việc phát triển hệ thống quản lý danh mục sản phẩm!
