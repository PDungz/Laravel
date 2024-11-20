# **Hướng Dẫn Các Câu Lệnh Phổ Biến Trong Migration Laravel**

Dưới đây là một **code mẫu đầy đủ** cho các thao tác thường sử dụng trong migration trong Laravel. Code này bao gồm các phương thức thường gặp khi bạn làm việc với các bảng trong cơ sở dữ liệu.

### **1. Tạo Migration Mới**

Lệnh tạo migration mới từ terminal:

```bash
php artisan make:migration create_products_table
```

### **2. Nội Dung Migration (Tạo, Thêm, Sửa Đổi Cột)**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng 'products'
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Tạo cột 'id' tự động tăng
            $table->string('name'); // Cột 'name' kiểu string
            $table->decimal('price', 10, 2); // Cột 'price' kiểu decimal với 10 chữ số, 2 chữ số thập phân
            $table->text('description')->nullable(); // Cột 'description' kiểu text, có thể null
            $table->foreignId('category_id')->constrained(); // Thêm khóa ngoại 'category_id' liên kết với bảng 'categories'
            $table->timestamps(); // Tạo cột 'created_at' và 'updated_at'
        });

        // Tạo chỉ mục duy nhất cho cột 'sku'
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->unique(); // Tạo chỉ mục duy nhất cho cột 'sku'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Xóa bảng 'products'
        Schema::dropIfExists('products');
    }
}
```

### **Giải Thích Các Phương Thức trong `up()`**

1. **`$table->id()`**: Tạo cột `id` tự động tăng.
2. **`$table->string('name')`**: Tạo cột `name` kiểu chuỗi (string).
3. **`$table->decimal('price', 10, 2)`**: Tạo cột `price` kiểu số thập phân, với 10 chữ số và 2 chữ số thập phân.
4. **`$table->text('description')->nullable()`**: Tạo cột `description` kiểu text, có thể nhận giá trị null.
5. **`$table->foreignId('category_id')->constrained()`**: Tạo cột `category_id` là khóa ngoại tham chiếu tới bảng `categories`.
6. **`$table->timestamps()`**: Tạo hai cột `created_at` và `updated_at` để lưu trữ thời gian tạo và cập nhật.
7. **`$table->string('sku')->unique()`**: Tạo chỉ mục duy nhất cho cột `sku`.

### **3. Thêm Cột Mới vào Bảng**

Giả sử bảng `products` đã tồn tại và bạn muốn thêm cột `stock`:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->integer('stock')->default(0); // Thêm cột 'stock' kiểu integer với giá trị mặc định là 0
    });
}
```

### **4. Thay Đổi Kiểu Dữ Liệu Cột**

Giả sử bạn muốn thay đổi kiểu dữ liệu của cột `price` từ `decimal` sang `string`:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('price')->change(); // Thay đổi kiểu cột 'price' thành kiểu string
    });
}
```

### **5. Xóa Cột**

Giả sử bạn muốn xóa cột `description`:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('description'); // Xóa cột 'description'
    });
}
```

### **6. Đổi Tên Cột**

Giả sử bạn muốn đổi tên cột `sku` thành `product_sku`:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->renameColumn('sku', 'product_sku'); // Đổi tên cột 'sku' thành 'product_sku'
    });
}
```

### **7. Tạo Khóa Ngoại (Foreign Key)**

Giả sử bạn muốn thêm khóa ngoại cho cột `category_id` liên kết với bảng `categories`:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade'); // Thêm khóa ngoại cho cột 'category_id'
    });
}
```

### **8. Tạo Index (Chỉ Mục)**

Giả sử bạn muốn tạo chỉ mục cho cột `name`:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->index('name'); // Tạo chỉ mục cho cột 'name'
    });
}
```

### **9. Tạo Cột Duy Nhất (Unique)**

Giả sử bạn muốn tạo chỉ mục duy nhất cho cột `sku`:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('sku')->unique(); // Tạo chỉ mục duy nhất cho cột 'sku'
    });
}
```

### **10. Xóa Bảng**

Giả sử bạn muốn xóa bảng `products`:

```php
public function up()
{
    Schema::dropIfExists('products'); // Xóa bảng 'products' nếu bảng đó tồn tại
}
```

### **11. Phương Thức `down()` (Hoàn Tác Migration)**

Phương thức `down()` giúp hoàn tác các thay đổi trong phương thức `up()`. Ví dụ:

```php
public function down()
{
    // Xóa bảng 'products' nếu có thay đổi trong phương thức `up()`
    Schema::dropIfExists('products');
}
```

---

### **Lưu ý khi thực hiện Migration**

1. **Rollback Migration**: Nếu bạn muốn hoàn tác tất cả các migration đã chạy, bạn có thể sử dụng lệnh:

    ```bash
    php artisan migrate:rollback
    ```

2. **Chạy lại Migration**: Nếu bạn đã thay đổi migration và muốn chạy lại, có thể sử dụng:

    ```bash
    php artisan migrate:refresh
    ```

3. **Chạy Migration**: Để chạy các migration, bạn sử dụng lệnh:

    ```bash
    php artisan migrate
    ```

4. **Chạy Migration cụ Thể**: Để chỉ chạy một migration cụ thể từ file, bạn sử dụng lệnh:

    ```bash
    php artisan migrate --path=/database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php
    ```

5. **Tạo Seeder**: Để thêm dữ liệu mẫu sau khi chạy migration, bạn có thể tạo một seeder:

    ```bash
    php artisan make:seeder ProductSeeder
    ```

6. **Gọi Seeder trong Migration**:

    ```php
    public function up()
    {
        $this->call(ProductSeeder::class); // Gọi seeder ProductSeeder
    }
    ```

---

### **Kết luận**

Các ví dụ trên bao gồm các thao tác thường xuyên sử dụng trong migration như tạo bảng, thêm cột, sửa đổi kiểu dữ liệu, tạo khóa ngoại, chỉ mục và xóa bảng. Những thao tác này sẽ giúp bạn quản lý cơ sở dữ liệu trong Laravel một cách linh hoạt và hiệu quả.
