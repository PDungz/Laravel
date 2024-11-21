<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/PDungz/Laravel">
  <img src="https://img.shields.io/badge/-GitHub%20Phùng Văn Dũng-black?logo=github&logoColor=white" alt="GitHub Channel">
</a>
<a href="https://youtube.com/@pvdnocode3623?si=o3UX8WHisI5mAVCu">
  <img src="https://img.shields.io/badge/-Youtube%20Pvd NoCode-black?logo=youtube&logoColor=red" alt="Youtube Channel">
</a>
</p>

# **Hướng Dẫn Tạo Dự Án Laravel Quản Lý Sản Phẩm và Danh Mục**

Bài hướng dẫn này sẽ giúp bạn xây dựng một dự án Laravel quản lý sản phẩm và danh mục, bao gồm các bước từ khởi tạo dự án, cấu hình cơ sở dữ liệu, đến tạo các model, migration, controller, và views cơ bản.

---

## **Bước 1: Cài đặt Laravel**

1. **Cài đặt Laravel**:  
   Nếu chưa cài đặt Laravel, hãy chạy lệnh sau để tạo dự án mới:

    ```bash
    composer create-project --prefer-dist laravel/laravel ProductManagement
    ```

    Điều này sẽ tạo một dự án Laravel trong thư mục `ProductManagement`.

2. **Chạy dự án**:
    ```bash
    php artisan serve
    ```
    Truy cập vào trình duyệt tại [http://127.0.0.1:8000](http://127.0.0.1:8000) để kiểm tra dự án.

---

## **Bước 2: Cấu Hình Cơ Sở Dữ Liệu**

1. Mở file `.env` trong thư mục gốc của dự án và cấu hình kết nối cơ sở dữ liệu:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=product_management
    DB_USERNAME=root
    DB_PASSWORD=
    ```

2. Tạo cơ sở dữ liệu trong MySQL:
    ```sql
    CREATE DATABASE product_management;
    ```

---

## **Bước 3: Tạo Migration**

1. **Tạo Migration cho Categories**:

    ```bash
    php artisan make:migration create_categories_table
    ```

    Trong file migration `create_categories_table`, thêm các cột:

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

2. **Tạo Migration cho Products**:

    ```bash
    php artisan make:migration create_products_table
    ```

    Trong file migration `create_products_table`, thêm các cột:

    ```php
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }
    ```

3. **Chạy Migration**:
    ```bash
    php artisan migrate
    ```

---

## **Bước 4: Tạo Models**

1.  **Tạo Model cho Categories**:

    ```bash
    php artisan make:model Category
    ```

    Trong file `app/Models/Category.php`, định nghĩa quan hệ:

    ```php

      <?php

      namespace App\Models;

      use Illuminate\Database\Eloquent\Factories\HasFactory;
      use Illuminate\Database\Eloquent\Model;

      class Category extends Model
      {
        use HasFactory;

        /**
         * Các thuộc tính có thể được gán giá trị hàng loạt.
        *
        * @var array<int, string>
        */
        protected $fillable = [
            'name',
            'description',
        ];

        /**
         * Định nghĩa quan hệ một-nhiều với Product.
        *
        * @return \Illuminate\Database\Eloquent\Relations\HasMany
        */
        public function products()
        {
            return $this->hasMany(Product::class);
        }

      }
    ```

2.  **Tạo Model cho Products**:

    ```bash
    php artisan make:model Product
    ```

    Trong file `app/Models/Product.php`, định nghĩa quan hệ:

    ```php
      <?php

      namespace App\Models;

      use Illuminate\Database\Eloquent\Factories\HasFactory;
      use Illuminate\Database\Eloquent\Model;

      class Product extends Model
      {
          use HasFactory;

          /**
          * Các thuộc tính có thể được gán giá trị hàng loạt.
          *
          * @var array<int, string>
          */
          protected $fillable = [
              'name',
              'description',
              'quantity',
              'price',
              'image',
              'category_id',
          ];

          /**
          * Định nghĩa quan hệ nhiều-một với Category.
          *
          * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
          */
          public function category()
          {
              return $this->belongsTo(Category::class);
          }
      }

    ```

---

## **Bước 5: Tạo Controller**

1.  **Tạo Resource Controllers**:

    ```bash
    php artisan make:controller CategoryController --resource
    php artisan make:controller ProductController --resource
    ```

2.  **Cấu hình Route** trong `routes/web.php`:

    ```php
    use App\Http\Controllers\CategoryController;
    use App\Http\Controllers\ProductController;

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    ```

3.  **CategoryController**: Thêm các phương thức để quản lý danh mục (CRUD). Ví dụ:

    ```php
      <?php

      namespace App\Http\Controllers;

      use App\Models\Category;
      use Illuminate\Http\Request;
      use Illuminate\Support\Facades\Log;

      class CategoryController extends Controller
      {
          /**
          * Hiển thị danh sách tất cả các danh mục với chức năng tìm kiếm.
          *
          * @param \Illuminate\Http\Request $request
          * @return \Illuminate\View\View
          */
          public function index(Request $request)
          {
              try {
                  // Lấy từ khóa tìm kiếm từ query string.
                  $search = $request->input('search');

                  // Lọc danh mục nếu có từ khóa tìm kiếm, ngược lại lấy tất cả.
                  $categories = Category::when($search, function ($query, $search) {
                      return $query->where('name', 'LIKE', "%{$search}%")
                                  ->orWhere('description', 'LIKE', "%{$search}%");
                  })->get();

                  // Trả về view 'categories.index' với dữ liệu danh sách danh mục.
                  return view('categories.index', compact('categories', 'search'));
              } catch (\Exception $e) {
                  // Ghi log lỗi và trả về thông báo lỗi cho người dùng.
                  Log::error("Lỗi khi tìm kiếm danh mục: " . $e->getMessage());
                  return redirect()->route('categories.index')->with('error', 'Không thể tìm kiếm danh mục. Vui lòng thử lại sau.');
              }
          }


          /**
          * Hiển thị form tạo mới danh mục.
          *
          * @return \Illuminate\View\View
          */
          public function create()
          {
              try {
                  // Trả về view 'categories.create' để người dùng có thể tạo mới danh mục.
                  return view('categories.create');
              } catch (\Exception $e) {
                  // Ghi log lỗi và trả về thông báo lỗi cho người dùng.
                  Log::error("Lỗi khi hiển thị form tạo mới danh mục: " . $e->getMessage());
                  return redirect()->route('categories.index')->with('error', 'Không thể hiển thị form tạo danh mục. Vui lòng thử lại sau.');
              }
          }

          /**
          * Lưu danh mục mới vào cơ sở dữ liệu.
          *
          * @param \Illuminate\Http\Request $request
          * @return \Illuminate\Http\RedirectResponse
          */
          public function store(Request $request)
          {
              try {
                  // Xác thực dữ liệu đầu vào từ form.
                  $request->validate([
                      'name' => 'required|string|max:255|unique:categories,name',
                      'description' => 'required|string|max:255|unique:categories,description',
                  ]);

                  // Tạo một danh mục mới trong cơ sở dữ liệu.
                  Category::create($request->all());

                  // Chuyển hướng người dùng về trang danh sách danh mục và hiển thị thông báo thành công.
                  return redirect()->route('categories.index')->with('success', 'Category created successfully.');
              } catch (\Exception $e) {
                  // Ghi log lỗi và trả về thông báo lỗi cho người dùng.
                  Log::error("Lỗi khi tạo danh mục mới: " . $e->getMessage());
                  return redirect()->route('categories.create')->with('error', 'Không thể tạo danh mục. Vui lòng thử lại sau.');
              }
          }

          /**
          * Hiển thị chi tiết của danh mục cụ thể.
          *
          * @param string $id
          * @return \Illuminate\View\View
          */
          public function show(string $id)
          {
              try {
                  // Lấy danh mục theo id từ cơ sở dữ liệu và trả về view chi tiết.
                  $category = Category::findOrFail($id);
                  return view('categories.show', compact('category'));
              } catch (\Exception $e) {
                  // Ghi log lỗi và trả về thông báo lỗi cho người dùng.
                  Log::error("Lỗi khi hiển thị danh mục: " . $e->getMessage());
                  return redirect()->route('categories.index')->with('error', 'Không thể hiển thị danh mục. Vui lòng thử lại sau.');
              }
          }

          /**
          * Hiển thị form chỉnh sửa thông tin danh mục.
          *
          * @param Category $category
          * @return \Illuminate\View\View
          */
          public function edit(Category $category)
          {
              try {
                  // Trả về view để chỉnh sửa thông tin danh mục hiện tại.
                  return view('categories.update', compact('category'));
              } catch (\Exception $e) {
                  // Ghi log lỗi và trả về thông báo lỗi cho người dùng.
                  Log::error("Lỗi khi hiển thị form chỉnh sửa danh mục: " . $e->getMessage());
                  return redirect()->route('categories.index')->with('error', 'Không thể hiển thị form chỉnh sửa danh mục. Vui lòng thử lại sau.');
              }
          }

          /**
          * Cập nhật thông tin danh mục trong cơ sở dữ liệu.
          *
          * @param \Illuminate\Http\Request $request
          * @param Category $category
          * @return \Illuminate\Http\RedirectResponse
          */
          public function update(Request $request, Category $category)
          {
              try {
                  // Xác thực lại dữ liệu đầu vào.
                  $request->validate([
                      'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                      'description' => 'required|string|max:255|unique:categories,description,' . $category->id,
                  ]);

                  // Cập nhật thông tin danh mục trong cơ sở dữ liệu.
                  $category->update($request->all());

                  // Chuyển hướng người dùng về trang danh sách danh mục và hiển thị thông báo thành công.
                  return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
              } catch (\Exception $e) {
                  // Ghi log lỗi và trả về thông báo lỗi cho người dùng.
                  Log::error("Lỗi khi cập nhật danh mục: " . $e->getMessage());
                  return redirect()->route('categories.edit', $category->id)->with('error', 'Không thể cập nhật danh mục. Vui lòng thử lại sau.');
              }
          }

          /**
          * Xóa một danh mục khỏi cơ sở dữ liệu.
          *
          * @param string $id
          * @return \Illuminate\Http\RedirectResponse
          */
          public function destroy(string $id)
          {
              try {
                  // Tìm danh mục theo id và xóa nó khỏi cơ sở dữ liệu.
                  $category = Category::findOrFail($id);
                  $category->delete();

                  // Chuyển hướng người dùng về trang danh sách danh mục và hiển thị thông báo thành công.
                  return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
              } catch (\Exception $e) {
                  // Ghi log lỗi và trả về thông báo lỗi cho người dùng.
                  Log::error("Lỗi khi xóa danh mục: " . $e->getMessage());
                  return redirect()->route('categories.index')->with('error', 'Không thể xóa danh mục. Vui lòng thử lại sau.');
              }
          }
      }
    ```

4.  **ProductController**: Thêm các phương thức tương tự như trên, đồng thời liên kết danh mục:

    ```php

      <?php

      namespace App\Http\Controllers;

      use App\Models\Category;
      use App\Models\Product;
      use Illuminate\Http\Request;
      use Illuminate\Support\Facades\Log;
      use Illuminate\Support\Facades\Storage;

      class ProductController extends Controller
      {
      /\*\*
      _ Hiển thị danh sách sản phẩm với khả năng tìm kiếm theo tên và mô tả.
      _/
      public function index(Request $request)
      {
      try {
      // Lấy giá trị tìm kiếm từ request
      $query = $request->input('query');

                  // Lọc danh sách sản phẩm dựa trên từ khóa tìm kiếm
                  $products = Product::when($query, function ($queryBuilder) use ($query) {
                      return $queryBuilder->where('name', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                  })
                      ->with('category')  // Lấy thông tin danh mục sản phẩm
                      ->paginate(10);     // Phân trang: hiển thị 10 sản phẩm mỗi trang

                  // Trả về view với danh sách sản phẩm
                  return view('products.index', compact('products'));
              } catch (\Exception $e) {
                  // Nếu có lỗi xảy ra, log lỗi và trả về thông báo lỗi
                  Log::error("Lỗi khi lấy danh sách sản phẩm: " . $e->getMessage());
                  return redirect()->back()->with('error', 'Không thể tải sản phẩm. Vui lòng thử lại.');
              }
          }

          /**
          * Hiển thị form tạo sản phẩm mới.
          */
          public function create()
          {
              try {
                  // Lấy tất cả danh mục sản phẩm
                  $categories = Category::all();
                  // Trả về view tạo sản phẩm, với danh sách danh mục
                  return view('products.create', compact('categories'));
              } catch (\Exception $e) {
                  // Log lỗi nếu có vấn đề khi lấy dữ liệu
                  Log::error("Lỗi khi hiển thị form tạo sản phẩm: " . $e->getMessage());
                  // Quay lại trang danh sách sản phẩm với thông báo lỗi
                  return redirect()->route('products.index')->with('error', 'Không thể hiển thị form tạo sản phẩm. Vui lòng thử lại.');
              }
          }

          /**
          * Xử lý tạo sản phẩm mới.
          */
          public function store(Request $request)
          {
              try {
                  // Xác thực dữ liệu đầu vào
                  $request->validate([
                      'name' => 'required|string|max:255',
                      'description' => 'nullable|string',
                      'price' => 'required|numeric|min:0',
                      'category_Id' => 'required|exists:categories,id',
                      'quantity' => 'required|integer|min:0',
                      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                  ]);

                  // Kiểm tra nếu có tệp ảnh được tải lên
                  $imagePath = null;
                  if ($request->hasFile('image')) {
                      // Lưu ảnh vào thư mục 'products' trên disk 'public'
                      $imagePath = $request->file('image')->store('products', 'public');
                  }

                  // Tạo sản phẩm mới trong cơ sở dữ liệu
                  Product::create([
                      'name' => $request->name,
                      'description' => $request->description,
                      'price' => $request->price,
                      'category_Id' => $request->category_Id,
                      'quantity' => $request->quantity,
                      'image' => $imagePath, // Lưu đường dẫn ảnh
                  ]);

                  // Chuyển hướng về danh sách sản phẩm và thông báo thành công
                  return redirect()->route('products.index')
                      ->with('success', 'Tạo sản phẩm thành công.');
              } catch (\Exception $e) {
                  // Log lỗi nếu có vấn đề khi tạo sản phẩm
                  Log::error("Lỗi khi tạo sản phẩm: " . $e->getMessage());
                  // Quay lại trang tạo sản phẩm với thông báo lỗi
                  return redirect()->back()->with('error', 'Không thể tạo sản phẩm. Vui lòng thử lại.')->withInput();
              }
          }

          /**
          * Hiển thị chi tiết sản phẩm.
          */
          public function show(Product $product)
          {
              try {
                  // Trả về view chi tiết sản phẩm
                  return view('products.show', compact('product'));
              } catch (\Exception $e) {
                  // Log lỗi và trả về thông báo lỗi nếu không thể hiển thị chi tiết sản phẩm
                  Log::error("Lỗi khi hiển thị chi tiết sản phẩm: " . $e->getMessage());
                  return redirect()->route('products.index')->with('error', 'Không thể hiển thị chi tiết sản phẩm. Vui lòng thử lại.');
              }
          }

          /**
          * Hiển thị form chỉnh sửa sản phẩm.
          */
          public function edit(Product $product)
          {
              try {
                  // Lấy tất cả danh mục sản phẩm
                  $categories = Category::all();
                  // Trả về view chỉnh sửa sản phẩm cùng dữ liệu sản phẩm và danh mục
                  return view('products.update', compact(['product', 'categories']));
              } catch (\Exception $e) {
                  // Log lỗi nếu không thể lấy dữ liệu để chỉnh sửa
                  Log::error("Lỗi khi hiển thị form chỉnh sửa sản phẩm: " . $e->getMessage());
                  return redirect()->route('products.index')->with('error', 'Không thể hiển thị form chỉnh sửa sản phẩm. Vui lòng thử lại.');
              }
          }

          /**
          * Xử lý cập nhật sản phẩm.
          */
          public function update(Request $request, Product $product)
          {
              try {
                  // Xác thực dữ liệu đầu vào
                  $request->validate([
                      'name' => 'required|string|max:255',
                      'description' => 'nullable|string',
                      'price' => 'required|numeric|min:0',
                      'quantity' => 'required|integer|min:0',
                      'category_Id' => 'required|exists:categories,id',
                      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                  ]);

                  // Kiểm tra nếu có tệp ảnh mới được tải lên
                  if ($request->hasFile('image')) {
                      // Xóa ảnh cũ nếu có
                      if ($product->image) {
                          Storage::disk('public')->delete($product->image);
                      }

                      // Lưu ảnh mới vào thư mục 'products'
                      $imagePath = $request->file('image')->store('products', 'public');
                      $product->image = $imagePath;
                  }

                  // Cập nhật các trường còn lại của sản phẩm
                  $product->fill($request->only(['name', 'description', 'price', 'quantity', 'category_Id']));
                  $product->save();

                  // Chuyển hướng về danh sách sản phẩm với thông báo thành công
                  return redirect()->route('products.index')
                      ->with('success', 'Cập nhật sản phẩm thành công.');
              } catch (\Exception $e) {
                  // Log lỗi nếu có vấn đề khi cập nhật sản phẩm
                  Log::error("Lỗi khi cập nhật sản phẩm: " . $e->getMessage());
                  // Quay lại trang chỉnh sửa sản phẩm với thông báo lỗi
                  return redirect()->back()->with('error', 'Không thể cập nhật sản phẩm. Vui lòng thử lại.')->withInput();
              }
          }

          /**
          * Xóa sản phẩm.
          */
          public function destroy(Product $product)
          {
              try {
                  // Kiểm tra nếu sản phẩm có hình ảnh, nếu có thì xóa ảnh
                  if ($product->image) {
                      Storage::disk('public')->delete($product->image);
                  }

                  // Xóa sản phẩm khỏi cơ sở dữ liệu
                  $product->delete();

                  // Chuyển hướng về danh sách sản phẩm với thông báo thành công
                  return redirect()->route('products.index')
                      ->with('success', 'Xóa sản phẩm thành công.');
              } catch (\Exception $e) {
                  // Log lỗi nếu có vấn đề khi xóa sản phẩm
                  Log::error("Lỗi khi xóa sản phẩm: " . $e->getMessage());
                  return redirect()->route('products.index')->with('error', 'Không thể xóa sản phẩm. Vui lòng thử lại.');
              }
          }
      }

    ```

Để tạo liên kết lưu trữ hình ảnh trong Laravel, bạn cần sử dụng lệnh Artisan để tạo symbolic link từ thư mục `storage/app/public` đến thư mục `public/storage`. Đây là cách để các tệp trong thư mục `storage` có thể truy cập được qua URL.

Lệnh cần chạy là:

```bash
php artisan storage:link
```

Lệnh này sẽ tạo một symbolic link từ thư mục `public/storage` đến `storage/app/public`, cho phép bạn truy cập các tệp lưu trữ từ thư mục `public`.

### Cách sử dụng:

1. Đảm bảo bạn đã cấu hình đúng hệ thống lưu trữ trong file `config/filesystems.php` để sử dụng `public` disk.
2. Chạy lệnh trên trong terminal của dự án Laravel.
3. Sau khi chạy lệnh này, bạn có thể sử dụng URL như sau để truy cập hình ảnh:

```php
$url = Storage::url('products/your-image.jpg');
```

---

## **Bước 6: Tạo Views**

### **Cấu trúc thư mục views**

Cấu trúc thư mục views sẽ được tổ chức theo dạng thư mục con để dễ dàng quản lý các loại view khác nhau:

```
resources
│
└───views
    │
    ├───layouts
    │   └───app.blade.php
    │
    ├───categories
    │   ├───index.blade.php
    │   ├───create.blade.php
    │   ├───edit.blade.php
    │   └───show.blade.php
    │
    └───products
        ├───index.blade.php
        ├───create.blade.php
        ├───edit.blade.php
        └───show.blade.php
```

### **Bước 1: Tạo Layout Chung `resources/views/layouts/app.blade.php`**

Đây là layout chung cho tất cả các trang trong ứng dụng, chứa các thành phần như `header`, `footer`, và các link đến các route khác.

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Product Management</title>
        <link rel="stylesheet" href="style.css" />
        <!-- Link đến file CSS nếu có -->
    </head>
    <body>
        <header>
            <nav>
                <a href="{{ route('categories.index') }}">Categories</a>
                <a href="{{ route('products.index') }}">Products</a>
            </nav>
        </header>

        <!-- Phần nội dung chính của trang sẽ được chèn vào đây -->
        <main>@yield('content')</main>

        <footer>
            <p>&copy; 2024 Product Management</p>
        </footer>
    </body>
</html>
```

-   **`@yield('content')`**: Đây là nơi nội dung của từng trang (view) sẽ được hiển thị. Mỗi view sẽ kế thừa layout này và chèn nội dung vào phần này.

### **Bước 2: Tạo Views cho Categories**

### **Bước 3: Tạo Views cho Products**

### **Bước 4: Kiểm tra và Tinh Chỉnh**

1. Truy cập vào các route `/categories` và `/products` để kiểm tra các trang quản lý danh mục và sản phẩm.
2. Thực hiện các thao tác tạo, cập nhật và xóa danh mục, sản phẩm để kiểm tra xem mọi chức năng có hoạt động đúng không.

---

### **Tóm tắt**

-   **Layouts**: Sử dụng layout chung cho tất cả các view để giữ cho giao diện nhất quán.
-   **Views**: Các view cho danh mục (`categories`) và sản phẩm (`products`) sử dụng các form để tạo và chỉnh

---

## **Bước 7: Tạo Routes trong routes/web.php**

Mở file routes/web.php và khai báo các route cần thiết cho các chức năng quản lý danh mục và sản phẩm.

```php
<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route Cơ Bản
Route::get('/', function () {
    return view('welcome');
});

// Route GET với tham số
Route::resource('categories', CategoryController::class);
Route::resource('products', CategoryController::class);

```

## **Bước 8: Kiểm Tra Ứng Dụng**

1. Truy cập vào các route:

    - `/categories`: Quản lý danh mục.
    - `/products`: Quản lý sản phẩm.

2. Kiểm tra việc tạo, cập nhật, và xóa danh mục, sản phẩm.
