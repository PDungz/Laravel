# **Hướng Dẫn Các Câu Lệnh Phổ Biến Trong Model Laravel**

## Mục lục

-   [1. Tạo Model trong Laravel](#1-tạo-model-trong-laravel)
-   [2. Các phương thức phổ biến khi sử dụng Model](#2-các-phương-thức-phổ-biến-khi-sử-dụng-model)
-   [3. Các Code Thường Sử Dụng trong Model](#3-các-code-thường-sử-dụng-trong-model)
-   [Ví dụ đọc thêm](#ví-dụ-đọc-thêm)

Dưới đây là hướng dẫn chi tiết về cách **tạo model** trong Laravel và một số **code hay sử dụng** cùng với ví dụ cụ thể.

## **1. Tạo Model trong Laravel**

### **Lệnh tạo model:**

Để tạo một model trong Laravel, bạn sử dụng lệnh Artisan sau:

```bash
php artisan make:model NameModel
```

Lệnh trên sẽ tạo ra một file model có tên là `NameModel.php` trong thư mục `app/Models`.

### **Model Cơ Bản:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameModel extends Model
{
    use HasFactory;

    // Nếu tên bảng không theo chuẩn Laravel (số nhiều), bạn có thể chỉ định tên bảng:
    protected $table = 'products';

    // Nếu bạn không muốn sử dụng timestamps (created_at, updated_at):
    public $timestamps = false;

    // Các thuộc tính có thể gán đại trà (mass assignable):
    protected $fillable = ['name', 'price', 'description'];

    // Các thuộc tính không thể gán đại trà (mass assignable):
    protected $guarded = ['id'];
}
```

### **Giải thích về các thuộc tính trong model:**

-   **`$table`**: Chỉ định tên bảng trong cơ sở dữ liệu mà model này đại diện. Laravel sẽ tự động sử dụng tên số nhiều của tên model (ví dụ: `NameModel` sẽ tương ứng với bảng `products`). Nếu bảng của bạn có tên khác, bạn cần chỉ định thủ công.
-   **`$timestamps`**: Nếu bảng của bạn không có các cột `created_at` và `updated_at`, bạn có thể tắt tính năng timestamps bằng cách đặt `public $timestamps = false;`.

-   **`$fillable`**: Thuộc tính này là một mảng các trường có thể gán đại trà (mass assignable). Điều này giúp bảo vệ ứng dụng khỏi các cuộc tấn công kiểu Mass Assignment.
-   **`$guarded`**: Thuộc tính này chỉ định các trường không thể gán đại trà. Bạn có thể sử dụng một trong hai (fillable hoặc guarded), nhưng không dùng cả hai.

## **2. Các phương thức phổ biến khi sử dụng Model**

Dưới đây là các phương thức hay sử dụng khi làm việc với Eloquent models trong Laravel, với ví dụ cụ thể.

### **2.1. Lấy Dữ Liệu (Retrieving Data)**

1. **Lấy tất cả bản ghi:**

```php
$products = NameModel::all(); // Lấy tất cả các sản phẩm trong bảng products
```

2. **Lấy bản ghi đầu tiên:**

```php
$product = NameModel::first(); // Lấy bản ghi đầu tiên
```

3. **Lấy bản ghi theo điều kiện:**

```php
$product = NameModel::where('name', 'Laptop')->first(); // Lấy sản phẩm có tên 'Laptop'
```

4. **Lấy tất cả sản phẩm với một điều kiện:**

```php
$products = NameModel::where('price', '>', 1000)->get(); // Lấy các sản phẩm có giá lớn hơn 1000
```

### **2.2. Thêm Dữ Liệu (Inserting Data)**

1. **Thêm một bản ghi mới:**

```php
$product = new NameModel();
$product->name = 'Smartphone';
$product->price = 500;
$product->description = 'A high-end smartphone.';
$product->save(); // Lưu sản phẩm vào cơ sở dữ liệu
```

2. **Thêm một bản ghi mới bằng cách sử dụng `create`:**

```php
$product = NameModel::create([
    'name' => 'Tablet',
    'price' => 300,
    'description' => 'A powerful tablet.'
]);
```

Lưu ý: Để sử dụng phương thức `create`, bạn cần chắc chắn rằng các trường cần gán đại trà đã được khai báo trong thuộc tính `$fillable` hoặc không có trong `$guarded`.

### **2.3. Cập nhật Dữ Liệu (Updating Data)**

1. **Cập nhật một bản ghi:**

```php
$product = NameModel::find(1); // Tìm sản phẩm có id = 1
$product->price = 550; // Cập nhật giá sản phẩm
$product->save(); // Lưu thay đổi
```

2. **Cập nhật bản ghi theo điều kiện:**

```php
NameModel::where('name', 'Smartphone')
    ->update(['price' => 450]); // Cập nhật giá của sản phẩm có tên 'Smartphone'
```

### **2.4. Xóa Dữ Liệu (Deleting Data)**

1. **Xóa một bản ghi:**

```php
$product = NameModel::find(1); // Tìm sản phẩm có id = 1
$product->delete(); // Xóa sản phẩm
```

2. **Xóa bản ghi theo điều kiện:**

```php
NameModel::where('price', '<', 100)->delete(); // Xóa các sản phẩm có giá nhỏ hơn 100
```

### **2.5. Các Quan Hệ giữa Các Bảng (Relationships)**

Laravel hỗ trợ nhiều kiểu quan hệ giữa các bảng. Dưới đây là ví dụ về các quan hệ phổ biến.

Trong Laravel, khi bạn thiết lập các mối quan hệ giữa các bảng (ví dụ như **One to One**, **One to Many**, **Many to Many**), Laravel sẽ tự động xác định các khóa ngoại (foreign keys) dựa trên quy ước, nhưng bạn cũng có thể chỉ định thủ công nếu cần thiết. Cụ thể, trong phần khai báo mối quan hệ, bạn không cần phải chỉ định rõ `id` của bảng quan hệ vì Laravel sẽ sử dụng các quy ước mặc định để xác định khóa ngoại, trừ khi bạn muốn thay đổi nó.

### **1. Quan hệ Một-Một (One to One)**

#### **Laravel Quy Ước Mặc Định:**

-   Laravel sẽ sử dụng tên bảng của model hiện tại và thêm `_id` để tạo khóa ngoại.
-   Ví dụ, nếu bạn có model `NameModel`, Laravel sẽ tìm kiếm trường `product_id` trong bảng liên kết.

#### **Ví dụ:**

Giả sử bạn có bảng `products` và bảng `product_details`. Một sản phẩm chỉ có một chi tiết sản phẩm liên quan.

**Trong model `NameModel`:**

```php
public function detail()
{
    return $this->hasOne(NameModelDetail::class);
}
```

**Trong model `NameModelDetail`:**

```php
public function product()
{
    return $this->belongsTo(NameModel::class);
}
```

-   Laravel sẽ tự động giả định rằng bảng `product_details` sẽ có một cột `product_id` để tạo mối quan hệ này. Bạn không cần phải khai báo `product_id` trong phương thức quan hệ.

#### **Nếu muốn chỉ định rõ khóa ngoại**:

Bạn có thể chỉ định khóa ngoại nếu bảng quan hệ của bạn không tuân theo quy ước mặc định. Ví dụ, nếu bảng `product_details` có khóa ngoại là `product_reference_id` thay vì `product_id`, bạn có thể làm như sau:

**Trong model `NameModel`:**

```php
public function detail()
{
    return $this->hasOne(NameModelDetail::class, 'product_reference_id');
}
```

**Trong model `NameModelDetail`:**

```php
public function product()
{
    return $this->belongsTo(NameModel::class, 'product_reference_id');
}
```

### **2. Quan hệ Một-Nhiều (One to Many)**

#### **Laravel Quy Ước Mặc Định:**

-   Trong mối quan hệ **One to Many**, khóa ngoại sẽ là tên bảng của model liên kết, cộng với `_id`. Ví dụ, nếu bạn có model `Category` và `NameModel`, Laravel sẽ tự động tìm kiếm cột `category_id` trong bảng `products`.

#### **Ví dụ:**

Giả sử bạn có một danh mục (Category) có nhiều sản phẩm (NameModel).

**Trong model `Category`:**

```php
public function products()
{
    return $this->hasMany(NameModel::class);
}
```

**Trong model `NameModel`:**

```php
public function category()
{
    return $this->belongsTo(Category::class);
}
```

-   Laravel sẽ tự động tìm cột `category_id` trong bảng `products` để thiết lập mối quan hệ này.

#### **Chỉ định khóa ngoại thủ công:**

Nếu cột khóa ngoại của bạn không theo quy ước (ví dụ `category_ref_id` thay vì `category_id`), bạn có thể chỉ định khóa ngoại như sau:

**Trong model `NameModel`:**

```php
public function category()
{
    return $this->belongsTo(Category::class, 'category_ref_id');
}
```

**Trong model `Category`:**

```php
public function products()
{
    return $this->hasMany(NameModel::class, 'category_ref_id');
}
```

### **3. Quan hệ Nhiều-Nhiều (Many to Many)**

#### **Laravel Quy Ước Mặc Định:**

-   Laravel sẽ sử dụng tên số nhiều của hai bảng và sắp xếp chúng theo thứ tự alphabet, sau đó thêm `_id` để tạo tên bảng trung gian (pivot table). Ví dụ, với model `NameModel` và `Category`, bảng trung gian sẽ có tên là `category_product`, với khóa ngoại là `product_id` và `category_id`.

#### **Ví dụ:**

Giả sử mỗi sản phẩm có thể thuộc nhiều danh mục, và mỗi danh mục có thể chứa nhiều sản phẩm.

**Trong model `NameModel`:**

```php
public function categories()
{
    return $this->belongsToMany(Category::class);
}
```

**Trong model `Category`:**

```php
public function products()
{
    return $this->belongsToMany(NameModel::class);
}
```

-   Laravel sẽ tự động tạo bảng trung gian có tên là `category_product` với các khóa ngoại là `product_id` và `category_id`.

#### **Chỉ định tên bảng và khóa ngoại thủ công:**

Nếu bạn sử dụng một bảng trung gian khác, bạn có thể chỉ định tên bảng và khóa ngoại theo cách thủ công.

```php
public function categories()
{
    return $this->belongsToMany(Category::class, 'custom_pivot_table', 'product_id', 'category_id');
}
```

Trong đó:

-   `custom_pivot_table`: Tên bảng trung gian bạn muốn sử dụng.
-   `product_id`: Khóa ngoại trong bảng trung gian liên kết với bảng `products`.
-   `category_id`: Khóa ngoại trong bảng trung gian liên kết với bảng `categories`.

Lưu ý:

-   **Mối quan hệ một-một (hasOne, belongsTo)**: Laravel sẽ tự động tìm khóa ngoại trong bảng liên kết theo quy ước mặc định (thêm `_id` vào tên model). Bạn chỉ cần chỉ định rõ tên khóa ngoại nếu bảng của bạn không tuân theo quy ước này.
-   **Mối quan hệ một-nhiều (hasMany, belongsTo)**: Tương tự như trên, Laravel sẽ tự động tìm khóa ngoại là tên model cộng với `_id` (ví dụ `category_id` trong bảng `products`).
-   **Mối quan hệ nhiều-nhiều (belongsToMany)**: Laravel sẽ tự động tạo bảng trung gian theo tên bảng số nhiều của các model, nhưng bạn có thể chỉ định tên bảng và các khóa ngoại nếu cần thiết.

Nếu bạn không muốn sử dụng tên khóa ngoại mặc định, bạn có thể tùy chỉnh tên khóa ngoại trong mỗi phương thức quan hệ, như đã trình bày trong các ví dụ.

### **2.6. Lọc Dữ Liệu với Scope**

Laravel cung cấp một tính năng gọi là "scopes" giúp tái sử dụng các điều kiện tìm kiếm phức tạp.

1. **Định nghĩa Scope:**

```php
// Trong model NameModel
public function scopeExpensive($query)
{
    return $query->where('price', '>', 1000);
}
```

2. **Sử dụng Scope:**

```php
$products = NameModel::expensive()->get(); // Lấy tất cả các sản phẩm có giá > 1000
```

## **3. Các Code Thường Sử Dụng trong Model**

### **3.1. Tìm kiếm theo nhiều điều kiện (Search with multiple conditions):**

```php
$products = NameModel::where('name', 'like', '%phone%')
                    ->where('price', '>', 500)
                    ->get();
```

### **3.2. Sắp xếp kết quả (Ordering results):**

```php
$products = NameModel::orderBy('price', 'desc')->get(); // Sắp xếp theo giá giảm dần
```

### **3.3. Giới hạn số lượng bản ghi (Limit records):**

```php
$products = NameModel::limit(5)->get(); // Lấy 5 sản phẩm đầu tiên
```

### **3.4. Thực hiện phân trang (Pagination):**

```php
$products = NameModel::paginate(10); // Lấy 10 sản phẩm mỗi trang
```

---

## **Kết luận**

Model trong Laravel cung cấp nhiều phương thức hữu ích để tương tác với cơ sở dữ liệu một cách dễ dàng. Các phương thức như `all()`, `find()`, `create()`, `update()`, `delete()` giúp bạn quản lý dữ liệu một cách linh hoạt. Các quan hệ giữa các bảng như One-to-One, One-to-Many, và Many-to-Many giúp bạn làm việc với dữ liệu liên quan mà không cần phải viết SQL phức tạp.

Hy vọng hướng dẫn trên sẽ giúp bạn dễ dàng làm việc với các model trong Laravel.

## Ví dụ đọc thêm

### **Ví Dụ Code đầy Đủ trong Model Laravel**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Các thuộc tính có thể được gán giá trị hàng loạt
    protected $fillable = ['name', 'email', 'password'];

    // Các thuộc tính không thể được gán giá trị hàng loạt
    protected $guarded = ['id'];

    // Mối quan hệ One to One với bảng Profile
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // Mối quan hệ One to Many với bảng Posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Mối quan hệ Many to Many với bảng Roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // Tạo scope để lọc người dùng theo tên
    public function scopeName($query, $name)
    {
        return $query->where('name', 'LIKE', "%$name%");
    }

    // Tạo sự kiện tạo mới người dùng
    protected static function booted()
    {
        static::creating(function ($user) {
            // Thực hiện một số hành động trước khi tạo người dùng mới
            $user->email = strtolower($user->email);
        });
    }

    // Thêm phương thức kiểm tra mật khẩu
    public function checkPassword($password)
    {
        return \Hash::check($password, $this->password);
    }

    // Phương thức truy vấn để lấy người dùng đã xác thực
    public static function authenticated()
    {
        return self::where('is_active', true)->whereNotNull('last_login');
    }
}

class Profile extends Model
{
    // Mối quan hệ ngược lại với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class Post extends Model
{
    // Mối quan hệ ngược lại với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class Role extends Model
{
    // Mối quan hệ ngược lại với User (Many to Many)
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
```

### **Giải Thích Mã Code:**

1. **$fillable và $guarded**:

    - **$fillable**: Dùng để xác định các thuộc tính có thể được gán giá trị hàng loạt.
    - **$guarded**: Chỉ định các thuộc tính không thể được gán giá trị hàng loạt.

2. **Mối Quan Hệ**:

    - **hasOne**: Được sử dụng khi một bản ghi trong bảng này chỉ có một bản ghi liên quan trong bảng khác (Ví dụ: Một người dùng có một profile).
    - **hasMany**: Được sử dụng khi một bản ghi trong bảng này có nhiều bản ghi liên quan trong bảng khác (Ví dụ: Một người dùng có nhiều bài viết).
    - **belongsToMany**: Được sử dụng khi hai bảng có mối quan hệ nhiều-nhiều, ví dụ như người dùng có thể có nhiều vai trò và vai trò có thể có nhiều người dùng.

3. **Query Scope**:

    - **scopeName**: Phương thức này cho phép bạn tạo một phương thức truy vấn tùy chỉnh. Đây là cách để dễ dàng lọc người dùng theo tên.

4. **Sự Kiện**:

    - **booted**: Sử dụng trong `booted()` để thêm các sự kiện khi một model được tạo, ví dụ như thay đổi email về chữ thường trước khi lưu vào cơ sở dữ liệu.

5. **Các Phương Thức Kiểm Tra và Truy Vấn**:
    - **checkPassword**: Phương thức này để kiểm tra mật khẩu của người dùng với một mật khẩu cung cấp.
    - **authenticated**: Phương thức này giúp truy vấn các người dùng đã xác thực, những người có `is_active = true` và đã đăng nhập gần đây.

### **Cách Sử Dụng:**

#### **Sử Dụng các Mối Quan Hệ:**

```php
// Lấy profile của một user
$user = User::find(1);
$profile = $user->profile;

// Lấy tất cả các posts của một user
$posts = $user->posts;

// Lấy tất cả các roles của user
$roles = $user->roles;
```

#### **Sử Dụng Scope:**

```php
// Tìm kiếm người dùng theo tên
$users = User::name('John')->get();
```

#### **Sử Dụng sự kiện tạo người dùng:**

```php
// Khi tạo người dùng mới, email sẽ được chuyển thành chữ thường trước khi lưu vào CSDL
$user = User::create([
    'name' => 'John Doe',
    'email' => 'JohnDoe@Example.com',
    'password' => bcrypt('password123'),
]);
```

---

Hy vọng ví dụ trên sẽ giúp bạn hiểu rõ hơn về cách sử dụng các phương thức và mối quan hệ trong các model của Laravel!
