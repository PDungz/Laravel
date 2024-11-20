# **Hướng dẫn Tạo và Sử Dụng Controller trong Laravel**

Controller trong Laravel là nơi chứa logic xử lý yêu cầu từ người dùng. Dưới đây là hướng dẫn chi tiết cách tạo và sử dụng controller, cùng với các ví dụ cụ thể.

---

## **1. Tạo Controller**

Sử dụng Artisan CLI để tạo một controller mới.

### **Tạo Controller Cơ Bản**

```bash
php artisan make:controller <TênController>
```

Ví dụ:

```bash
php artisan make:controller CategoryController
```

Lệnh trên sẽ tạo file `CategoryController.php` trong thư mục `app/Http/Controllers`.

### **Tạo Controller Resource (Tích hợp các phương thức CRUD)**

```bash
php artisan make:controller <TênController> --resource
```

Ví dụ:

```bash
php artisan make:controller OrderController --resource
```

Controller này sẽ tự động tạo các phương thức: `index`, `create`, `store`, `show`, `edit`, `update`, và `destroy`.

---

## **2. Sử Dụng Controller**

### **Định Tuyến (Routes)**

-   **Định tuyến đến một phương thức cụ thể**  
    Bạn có thể kết nối route đến một phương thức trong controller.

```php
use App\Http\Controllers\CategoryController;

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/categories', [CategoryController::class, 'store']);
```

-   **Sử dụng Resource Route**  
    Nếu controller được tạo với tùy chọn `--resource`, bạn có thể sử dụng một lệnh duy nhất để định nghĩa toàn bộ các route CRUD.

```php
Route::resource('categories', CategoryController::class);
```

Lệnh trên sẽ tự động tạo các route như sau:

| HTTP Method | URL                   | Action  | Route Name         |
| ----------- | --------------------- | ------- | ------------------ |
| GET         | /categories           | index   | categories.index   |
| GET         | /categories/create    | create  | categories.create  |
| POST        | /categories           | store   | categories.store   |
| GET         | /categories/{id}      | show    | categories.show    |
| GET         | /categories/{id}/edit | edit    | categories.edit    |
| PUT/PATCH   | /categories/{id}      | update  | categories.update  |
| DELETE      | /categories/{id}      | destroy | categories.destroy |

---

## **3. Các Phương Thức Thường Dùng Trong Controller**

Dưới đây là các phương thức CRUD phổ biến trong một controller.

### **3.1. Phương Thức `index` (Hiển Thị Danh Sách Dữ Liệu)**

Trong hàm `index`, bạn có thể thêm các **xử lý phổ biến khác** để mở rộng chức năng hiển thị danh sách dữ liệu. Dưới đây là các ví dụ về các xử lý phổ biến và cách áp dụng chúng:

---

#### **3.1.1 Phân Trang (`paginate`)**

Hiển thị danh sách dữ liệu với phân trang thay vì tải toàn bộ dữ liệu:

```php
public function index()
{
    $categories = Category::paginate(10); // Hiển thị 10 mục mỗi trang
    return view('categories.index', compact('categories'));
}
```

---

#### **3.1.2. Tìm Kiếm (`search`)**

Cho phép người dùng tìm kiếm dữ liệu thông qua từ khóa:

```php
public function index(Request $request)
{
    $query = Category::query();

    // Kiểm tra nếu có từ khóa tìm kiếm
    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $categories = $query->paginate(10);

    return view('categories.index', compact('categories'));
}
```

---

#### **3.1.3. Sắp Xếp (`orderBy`)**

Cho phép sắp xếp danh sách theo một cột nhất định:

```php
public function index(Request $request)
{
    $sortField = $request->get('sort', 'created_at'); // Mặc định sắp xếp theo `created_at`
    $sortOrder = $request->get('order', 'desc'); // Mặc định thứ tự giảm dần

    $categories = Category::orderBy($sortField, $sortOrder)->paginate(10);

    return view('categories.index', compact('categories'));
}
```

---

#### **3.1.4. Lọc Dữ Liệu (`filter`)**

Lọc danh sách dựa trên các tiêu chí nhất định:

```php
public function index(Request $request)
{
    $query = Category::query();

    // Lọc theo trạng thái (ví dụ: `active` hoặc `inactive`)
    if ($request->has('status')) {
        $query->where('status', $request->status);
    }

    $categories = $query->paginate(10);

    return view('categories.index', compact('categories'));
}
```

---

#### **3.1.5. Đếm Số Lượng Dữ Liệu (`count`)**

Lấy tổng số bản ghi để hiển thị thông tin phụ trợ:

```php
public function index()
{
    $categories = Category::paginate(10);
    $totalCategories = Category::count();

    return view('categories.index', compact('categories', 'totalCategories'));
}
```

---

#### **3.1.6. Quan Hệ Liên Kết (`with`)**

Lấy dữ liệu liên kết với một bảng khác (Eloquent Relationships):

```php
public function index()
{
    $categories = Category::with('products')->paginate(10); // Lấy danh sách kèm sản phẩm liên kết
    return view('categories.index', compact('categories'));
}
```

---

#### **3.1.7. Truy Vấn Cụ Thể (Custom Query)**

Lấy dữ liệu với các điều kiện đặc biệt:

```php
public function index()
{
    $categories = Category::where('is_featured', true) // Chỉ lấy danh mục nổi bật
                         ->orderBy('name', 'asc')     // Sắp xếp theo tên
                         ->paginate(10);

    return view('categories.index', compact('categories'));
}
```

---

#### **3.1.8. Hiển Thị Với Dữ Liệu Tĩnh**

Kết hợp danh sách với một số dữ liệu tĩnh hoặc cấu hình:

```php
public function index()
{
    $categories = Category::all();
    $settings = [
        'title' => 'Danh sách danh mục',
        'description' => 'Quản lý tất cả các danh mục sản phẩm.',
    ];

    return view('categories.index', compact('categories', 'settings'));
}
```

---

#### **Tổng Hợp Code Mẫu Hoàn Chỉnh**

```php
public function index(Request $request)
{
    $query = Category::query();

    // Tìm kiếm
    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Lọc theo trạng thái
    if ($request->has('status')) {
        $query->where('status', $request->status);
    }

    // Sắp xếp
    $sortField = $request->get('sort', 'created_at');
    $sortOrder = $request->get('order', 'desc');
    $query->orderBy($sortField, $sortOrder);

    // Lấy dữ liệu kèm quan hệ
    $categories = $query->with('products')->paginate(10);

    // Đếm tổng số danh mục
    $totalCategories = Category::count();

    return view('categories.index', compact('categories', 'totalCategories'));
}
```

### **3.2. Phương Thức `create` (Hiển Thị Form Tạo Dữ Liệu Mới)**

```php
public function create(Request $request)
{
    // Kiểm tra quyền truy cập
    if (!auth()->user()->can('create-category')) {
        abort(403, 'Bạn không có quyền tạo danh mục.');
    }

    // Dữ liệu phụ trợ
    $statuses = ['active' => 'Active', 'inactive' => 'Inactive'];
    $parentId = $request->get('parent_id', null);
    $parentCategory = $parentId ? Category::find($parentId) : null;

    // Dữ liệu mặc định
    $defaultStatus = 'active';

    // return view('categories.create', compact('statuses', 'parentCategory', 'defaultStatus'));

    return view('categories.create');
}

```

### **Phương Thức `store` (Xử Lý Lưu Dữ Liệu Mới)**

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
    ]);

    Category::create($validated);

    return redirect()->route('categories.index')->with('success', 'Category created successfully!');
}
```

### **Phương Thức `show` (Hiển Thị Chi Tiết Dữ Liệu)**

```php
public function show($id)
{
    $category = Category::findOrFail($id);
    return view('categories.show', compact('category'));
}
```

### **Phương Thức `edit` (Hiển Thị Form Cập Nhật Dữ Liệu)**

```php
public function edit($id)
{
    $category = Category::findOrFail($id);
    return view('categories.edit', compact('category'));
}
```

### **Phương Thức `update` (Xử Lý Cập Nhật Dữ Liệu)**

```php
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
    ]);

    $category = Category::findOrFail($id);
    $category->update($validated);

    return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
}
```

### **Phương Thức `destroy` (Xóa Dữ Liệu)**

```php
public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
}
```

---

## **4. Ví Dụ Full Code Trong Controller**

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        return view('categories.create');
    }

    // Lưu sản phẩm mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    // Hiển thị form cập nhật sản phẩm
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    // Cập nhật sản phẩm trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    // Xóa sản phẩm
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
```

---

## **5. Kết Luận**

Controller giúp tổ chức logic ứng dụng một cách rõ ràng, chia nhỏ các chức năng để dễ quản lý. Kết hợp với **routes** và **Blade templates**, bạn có thể xây dựng giao diện và xử lý logic đầy đủ cho ứng dụng của mình.
