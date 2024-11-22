<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/PDungz/Laravel">
  <img src="https://img.shields.io/badge/-GitHub%20Phùng Văn Dũng-black?logo=github&logoColor=white" alt="GitHub Channel">
</a>
<a href="https://youtube.com/@pvdnocode3623?si=o3UX8WHisI5mAVCu">
  <img src="https://img.shields.io/badge/-Youtube%20Pvd NoCode-black?logo=youtube&logoColor=red" alt="Youtube Channel">
</a>
</p>

Để tạo một dự án quản lý giỏ hàng với các thông tin về **category**, **product**, và **cart** trong hệ thống cơ sở dữ liệu, bạn có thể làm theo các bước dưới đây. Dự án này sẽ sử dụng PHP và MySQL để lưu trữ dữ liệu và Laravel làm framework.

### Bước 1: Cài đặt Laravel

1. **Cài đặt Composer** (nếu chưa cài đặt):
   Tải Composer từ [https://getcomposer.org](https://getcomposer.org).

2. **Tạo dự án Laravel**:

    ```bash
    composer create-project --prefer-dist laravel/laravel ecm-shopee-cart
    ```

3. **Di chuyển vào thư mục dự án**:

    ```bash
    cd ecm-shopee-cart
    ```

4. **Chạy ứng dụng Laravel**:

    ```bash
    php artisan serve
    ```

    Mở trình duyệt và vào `http://127.0.0.1:8000` để xem ứng dụng.

### Bước 2: Tạo cơ sở dữ liệu

1. **Tạo cơ sở dữ liệu trong MySQL**:
   Đăng nhập vào MySQL và tạo cơ sở dữ liệu:

    ```sql
    CREATE DATABASE ecm-shopee-cart;
    ```

2. **Cấu hình kết nối cơ sở dữ liệu**:
   Mở tệp `.env` và chỉnh sửa thông tin kết nối cơ sở dữ liệu:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ecm-shopee-cart
    DB_USERNAME=root
    DB_PASSWORD=
    ```

### Bước 3: Tạo các bảng trong cơ sở dữ liệu

1. **Tạo Migration cho Category**:
   Chạy lệnh để tạo migration cho bảng `categories`:

    ```bash
    php artisan make:migration create_categories_table
    ```

    Mở tệp migration vừa tạo trong thư mục `database/migrations` và chỉnh sửa như sau:

    ```php
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });
    }
    ```

2. **Tạo Migration cho Product**:
   Tạo migration cho bảng `products`:

    ```bash
    php artisan make:migration create_products_table
    ```

    Mở tệp migration và chỉnh sửa:

    ```php
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->text('description');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->date('entry_date');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    ```

3. **Tạo Migration cho Cart**:
   Tạo migration cho bảng `carts`:

    ```bash
    php artisan make:migration create_carts_table
    ```

    Mở tệp migration và chỉnh sửa:

    ```php
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }
    ```

### Bước 4: Chạy Migration

Chạy lệnh sau để tạo các bảng trong cơ sở dữ liệu:

```bash
php artisan migrate
```

### Bước 5: Tạo Model cho các bảng

1. **Tạo Model cho Category**:
   Chạy lệnh:

    ```bash
    php artisan make:model Category
    ```

    Trong tệp `app/Models/Category.php`, định nghĩa quan hệ:

    ```php
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    ```

2. **Tạo Model cho Product**:
   Chạy lệnh:

    ```bash
    php artisan make:model Product
    ```

    Trong tệp `app/Models/Product.php`, định nghĩa quan hệ:

    ```php
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    ```

3. **Tạo Model cho Cart**:
   Chạy lệnh:

    ```bash
    php artisan make:model Cart
    ```

    Trong tệp `app/Models/Cart.php`, định nghĩa quan hệ:

    ```php
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    ```

### Bước 6: Tạo Controller và Routes

1. **Tạo Controller cho Category, Product và Cart**:
   Chạy lệnh để tạo controller:

    ```bash
    php artisan make:controller CategoryController
    php artisan make:controller ProductController
    php artisan make:controller CartController
    ```

2. **Định nghĩa các phương thức trong Controller**:

    - `CategoryController`: Quản lý các thao tác CRUD cho `Category`.
    - `ProductController`: Quản lý các thao tác CRUD cho `Product`.
    - `CartController`: Quản lý các thao tác liên quan đến giỏ hàng (thêm, sửa, xóa sản phẩm).

3. **Tạo Routes trong `routes/web.php`**:
    ```php
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('carts', CartController::class);
    ```

### Bước 7: Xây dựng Views

Sử dụng Blade để tạo giao diện cho việc hiển thị các danh mục, sản phẩm và giỏ hàng. Tạo các tệp Blade trong thư mục `resources/views`:

-   `categories.index`: Hiển thị danh sách các danh mục.
-   `products.index`: Hiển thị danh sách các sản phẩm.
-   `carts.index`: Hiển thị sản phẩm trong giỏ hàng.

### Bước 8: Kiểm tra và Chạy Ứng Dụng

Sau khi đã hoàn thành các bước trên, bạn có thể truy cập vào các đường dẫn để thêm, xem và quản lý các danh mục, sản phẩm và giỏ hàng.

### Tóm tắt cấu trúc cơ sở dữ liệu:

1. **categories**: `id`, `name`, `description`
2. **products**: `id`, `name`, `image`, `description`, `quantity`, `price`, `entry_date`, `category_id`
3. **carts**: `id`, `product_id`, `quantity`

Bạn có thể mở rộng các tính năng này như thêm chức năng thanh toán, xác nhận đơn hàng, v.v. tùy theo yêu cầu của dự án.
