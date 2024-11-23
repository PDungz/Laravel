# **Hướng Dẫn Các Câu Lệnh Phổ Biến Trong Migration Laravel**

Dưới đây là một **code mẫu đầy đủ** cho các thao tác thường sử dụng trong migration trong Laravel. Code này bao gồm các phương thức thường gặp khi bạn làm việc với các bảng trong cơ sở dữ liệu.

### **1. Tạo Migration Mới**

Lệnh tạo migration mới từ terminal:

```bash
php artisan make:migration create_<name>_table
```

### **2. Nội Dung Migration (Tạo, Thêm, Sửa Đổi Cột)**

Dưới đây là bộ mã migration đầy đủ với các câu lệnh cơ bản bạn có thể sử dụng cho bất kỳ đối tượng nào trong Laravel. Bộ mã này bao gồm tất cả các câu lệnh để thao tác với bảng, từ tạo bảng mới, thay đổi cấu trúc bảng, thêm cột, đổi tên cột, cho đến các thao tác với chỉ mục và khóa ngoại.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExampleMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng mới
        Schema::create('example_table', function (Blueprint $table) {
            $table->id(); // Cột 'id' tự động tăng
            $table->string('name'); // Cột 'name' kiểu string
            $table->integer('quantity')->default(0); // Cột 'quantity' kiểu integer, mặc định là 0
            $table->decimal('price', 10, 2); // Cột 'price' kiểu decimal với 10 chữ số và 2 chữ số thập phân
            $table->text('description')->nullable(); // Cột 'description' kiểu text, có thể null
            $table->boolean('is_active')->default(true); // Cột 'is_active' kiểu boolean, mặc định là true
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Cột 'status' kiểu enum
            $table->date('order_date'); // Cột 'order_date' kiểu date
            $table->time('order_time'); // Cột 'order_time' kiểu time
            $table->dateTime('published_at')->nullable(); // Cột 'published_at' kiểu datetime, có thể null
            $table->json('settings')->nullable(); // Cột 'settings' kiểu JSON, có thể null
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại 'user_id', xóa cascade
            $table->timestamps(); // Tạo cột 'created_at' và 'updated_at'
        });


        // Thêm chỉ mục cho cột 'name'
        Schema::table('example_table', function (Blueprint $table) {
            $table->index('name'); // Thêm chỉ mục cho cột 'name'
        });

        // Thêm khóa ngoại liên kết với bảng khác (nếu có bảng liên quan)
        Schema::table('example_table', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('categories'); // Khóa ngoại liên kết với bảng 'categories'
        });

        // Thêm cột 'sku' với chỉ mục duy nhất
        Schema::table('example_table', function (Blueprint $table) {
            $table->string('sku')->unique(); // Cột 'sku' với chỉ mục duy nhất
        });

        // Thêm cột 'remember_token' cho chức năng "remember me"
        Schema::table('example_table', function (Blueprint $table) {
            $table->rememberToken(); // Thêm cột 'remember_token'
        });

        // Thêm cột xóa mềm (soft deletes)
        Schema::table('example_table', function (Blueprint $table) {
            $table->softDeletes(); // Thêm cột 'deleted_at' cho xóa mềm
        });

        // Thay đổi kiểu dữ liệu của cột
        Schema::table('example_table', function (Blueprint $table) {
            $table->string('name')->change(); // Thay đổi kiểu dữ liệu của cột 'name' thành string
        });

        // Đổi tên cột
        Schema::table('example_table', function (Blueprint $table) {
            $table->renameColumn('old_column', 'new_column'); // Đổi tên cột từ 'old_column' thành 'new_column'
        });

        // Thêm cột mới
        Schema::table('example_table', function (Blueprint $table) {
            $table->integer('stock')->default(0); // Thêm cột 'stock' kiểu integer với giá trị mặc định
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Xóa bảng 'example_table' nếu có
        Schema::dropIfExists('example_table');

        // Xóa cột 'sku'
        Schema::table('example_table', function (Blueprint $table) {
            $table->dropColumn('sku'); // Xóa cột 'sku'
        });

        // Xóa chỉ mục cho cột 'name'
        Schema::table('example_table', function (Blueprint $table) {
            $table->dropIndex(['name']); // Xóa chỉ mục cho cột 'name'
        });

        // Xóa khóa ngoại 'category_id'
        Schema::table('example_table', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // Xóa khóa ngoại cho cột 'category_id'
        });

        // Xóa cột 'remember_token'
        Schema::table('example_table', function (Blueprint $table) {
            $table->dropColumn('remember_token'); // Xóa cột 'remember_token'
        });

        // Xóa cột 'softDeletes'
        Schema::table('example_table', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Xóa cột 'deleted_at' cho xóa mềm
        });
    }
}
```

### Giải thích các câu lệnh:

1. **Tạo bảng mới** (`Schema::create`):
    - Tạo bảng mới với các cột cơ bản như `id`, `name`, `price`, `description`, và khóa ngoại `category_id`.
2. **Thêm cột mới** (`$table->column_type()`):

    - Bạn có thể thêm các cột như `string`, `integer`, `decimal`, `text`, `boolean` vào bảng.

3. **Thêm chỉ mục** (`$table->index()`, `$table->unique()`):
    - Thêm chỉ mục cho cột như `name`, `sku` để tối ưu hóa việc tìm kiếm.
4. **Thêm khóa ngoại** (`$table->foreignId()->constrained()`):
    - Tạo khóa ngoại liên kết với bảng khác như `category_id` liên kết với bảng `categories`.
5. **Cột xóa mềm** (`$table->softDeletes()`):
    - Thêm cột `deleted_at` để thực hiện xóa mềm (soft deletes).
6. **Cột `remember_token`**:
    - Thêm cột `remember_token` để hỗ trợ chức năng "remember me" trong hệ thống xác thực.
7. **Thay đổi kiểu dữ liệu** (`$table->change()`):

    - Thay đổi kiểu dữ liệu của cột hiện tại (ví dụ: thay đổi `name` thành `string`).

8. **Đổi tên cột** (`$table->renameColumn()`):

    - Đổi tên cột từ `old_column` thành `new_column`.

9. **Hủy các thay đổi** (`down()`):
    - Trong phương thức `down()`, bạn có thể hủy các thay đổi đã thực hiện trong `up()`, ví dụ: xóa bảng, xóa cột, xóa chỉ mục, hoặc xóa khóa ngoại.

### Cách sử dụng:

-   Bạn có thể thêm hoặc thay đổi các câu lệnh trong phần `up()` và `down()` để thực hiện các thay đổi đối với cơ sở dữ liệu.

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
