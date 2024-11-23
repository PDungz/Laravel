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
php artisan make:controller NameModelController
```

Lệnh trên sẽ tạo file `NameModelController.php` trong thư mục `app/Http/Controllers`.

### **Tạo Controller Resource (Tích hợp các phương thức CRUD)**

```bash
php artisan make:controller <TênController> --resource
```

Ví dụ:

```bash
php artisan make:controller NameModelController --resource
```

Controller này sẽ tự động tạo các phương thức: `index`, `create`, `store`, `show`, `edit`, `update`, và `destroy`.

---

## **2. Sử Dụng Controller**

### **Định Tuyến (Routes)**

-   **Định tuyến đến một phương thức cụ thể**  
    Bạn có thể kết nối route đến một phương thức trong controller.

```php
use App\Http\Controllers\NameModelController;

Route::get('/name', [NameModelController::class, 'index']);
Route::get('/name/{id}', [NameModelController::class, 'show']);
Route::post('/name', [NameModelController::class, 'store']);
```

-   **Sử dụng Resource Route**  
    Nếu controller được tạo với tùy chọn `--resource`, bạn có thể sử dụng một lệnh duy nhất để định nghĩa toàn bộ các route CRUD.

```php
Route::resource('name', NameModelController::class);
```

Lệnh trên sẽ tự động tạo các route như sau:

| HTTP Method | URL             | Action  | Route Name   |
| ----------- | --------------- | ------- | ------------ |
| GET         | /name           | index   | name.index   |
| GET         | /name/create    | create  | name.create  |
| POST        | /name           | store   | name.store   |
| GET         | /name/{id}      | show    | name.show    |
| GET         | /name/{id}/edit | edit    | name.edit    |
| PUT/PATCH   | /name/{id}      | update  | name.update  |
| DELETE      | /name/{id}      | destroy | name.destroy |

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
    $name = NameModel::paginate(10); // Hiển thị 10 mục mỗi trang
    return view('name.index', compact('name'));
}
```

---

#### **3.1.2. Tìm Kiếm (`search`)**

Cho phép người dùng tìm kiếm dữ liệu thông qua từ khóa:

```php
public function index(Request $request)
{
    $query = NameModel::query();

    // Kiểm tra nếu có từ khóa tìm kiếm
    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $name = $query->paginate(10);

    return view('name.index', compact('name'));
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

    $name = NameModel::orderBy($sortField, $sortOrder)->paginate(10);

    return view('name.index', compact('name'));
}
```

---

#### **3.1.4. Lọc Dữ Liệu (`filter`)**

Lọc danh sách dựa trên các tiêu chí nhất định:

```php
public function index(Request $request)
{
    $query = NameModel::query();

    // Lọc theo trạng thái (ví dụ: `active` hoặc `inactive`)
    if ($request->has('status')) {
        $query->where('status', $request->status);
    }

    $name = $query->paginate(10);

    return view('name.index', compact('name'));
}
```

---

#### **3.1.5. Đếm Số Lượng Dữ Liệu (`count`)**

Lấy tổng số bản ghi để hiển thị thông tin phụ trợ:

```php
public function index()
{
    $name = NameModel::paginate(10);
    $totalCategories = NameModel::count();

    return view('name.index', compact('name', 'totalCategories'));
}
```

---

#### **3.1.6. Quan Hệ Liên Kết (`with`)**

Lấy dữ liệu liên kết với một bảng khác (Eloquent Relationships):

```php
public function index()
{
    $name = NameModel::with('products')->paginate(10); // Lấy danh sách kèm sản phẩm liên kết
    return view('name.index', compact('name'));
}
```

---

#### **3.1.7. Truy Vấn Cụ Thể (Custom Query)**

Lấy dữ liệu với các điều kiện đặc biệt:

```php
public function index()
{
    $name = NameModel::where('is_featured', true) // Chỉ lấy danh mục nổi bật
                         ->orderBy('name', 'asc')     // Sắp xếp theo tên
                         ->paginate(10);

    return view('name.index', compact('name'));
}
```

---

#### **3.1.8. Hiển Thị Với Dữ Liệu Tĩnh**

Kết hợp danh sách với một số dữ liệu tĩnh hoặc cấu hình:

```php
public function index()
{
    $name = NameModel::all();
    $settings = [
        'title' => 'Danh sách danh mục',
        'description' => 'Quản lý tất cả các danh mục sản phẩm.',
    ];

    return view('name.index', compact('name', 'settings'));
}
```

---

#### **Tổng Hợp Code Mẫu Hoàn Chỉnh**

```php
public function index(Request $request)
{
    $query = NameModel::query();

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
    $name = $query->with('products')->paginate(10);

    // Đếm tổng số danh mục
    $totalCategories = NameModel::count();

    return view('name.index', compact('name', 'totalCategories'));
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
    $parentNameModel = $parentId ? NameModel::find($parentId) : null;

    // Dữ liệu mặc định
    $defaultStatus = 'active';

    // return view('name.create', compact('statuses', 'parentNameModel', 'defaultStatus'));

    return view('name.create');
}

```

### **Phương Thức `store` (Xử Lý Lưu Dữ Liệu Mới)**

```php
public function store(Request $request)
{
    // Xác thực dữ liệu đầu vào
    $validated = $request->validate([
        // Chuỗi (string)
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',

        // Số (number)
        'price' => 'required|numeric|min:0|max:999999.99', // Giá (tối thiểu 0, tối đa 999,999.99)
        'stock' => 'required|integer|min:0|max:100000', // Số lượng tồn kho (nguyên)

        // Boolean (true/false)
        'is_active' => 'required|boolean', // Trạng thái hoạt động

        // Ngày (date)
        'expiry_date' => 'nullable|date|after_or_equal:today', // Ngày hết hạn, không nhỏ hơn ngày hiện tại

        // Email
        'email' => 'required|email|max:255|unique:users,email', // Email duy nhất trong bảng users

        // URL
        'website' => 'nullable|url|max:255', // URL hợp lệ

        // File (image)
        'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // File ảnh với kích thước tối đa 2MB

        // Mảng (array)
        'tags' => 'nullable|array', // Dữ liệu dạng mảng
        'tags.*' => 'string|max:50', // Mỗi phần tử trong mảng phải là chuỗi và tối đa 50 ký tự

        // Khóa ngoại
        'category_id' => 'required|exists:name,id', // Phải tồn tại trong bảng name
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    NameModel::create([
        'name' => $request->name,
        'description' => $request->description,
         ....
        'image' => $imagePath, // Lưu đường dẫn ảnh
    ]);


    return redirect()->route('name.index')->with('success', 'NameModel created successfully!');
}
```

### **Phương Thức `show` (Hiển Thị Chi Tiết Dữ Liệu)**

```php
public function show($id)
{
    $category = NameModel::findOrFail($id);
    return view('name.show', compact('category'));
}
```

### **Phương Thức `edit` (Hiển Thị Form Cập Nhật Dữ Liệu)**

```php
public function edit($id)
{
    $category = NameModel::findOrFail($id);
    return view('name.edit', compact('category'));
}
```

### **Phương Thức `update` (Xử Lý Cập Nhật Dữ Liệu)**

```php
public function update(Request $request, NameModel $name)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
         ...
        'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // File ảnh với kích thước tối đa 2MB
    ]);

    // Kiểm tra nếu có tệp ảnh mới được tải lên
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu có
        if ($name->image) {
            Storage::disk('public')->delete($name->image);
        }

        // Lưu ảnh mới vào thư mục 'names'
        $imagePath = $request->file('image')->store('names', 'public');
        $name->image = $imagePath;
    }

    // Cập nhật các trường còn lại của sản phẩm
    $name->fill($request->only(['name', 'description', 'price', 'quantity', 'entry_date', 'category_id']));
    $name->save();

    return redirect()->route('name.index')->with('success', 'NameModel updated successfully!');
}
```

### **Phương Thức `destroy` (Xóa Dữ Liệu)**

```php
public function destroy($id)
{
    $category = NameModel::findOrFail($id);
    $category->delete();

    return redirect()->route('name.index')->with('success', 'NameModel deleted successfully!');
}
```

---

## **4. Ví Dụ Full Code Trong Controller**

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NameModel;

class NameModelController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $name = NameModel::all();
        return view('name.index', compact('name'));
    }

    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        return view('name.create');
    }

    // Lưu sản phẩm mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        NameModel::create($validated);

        return redirect()->route('name.index')->with('success', 'NameModel created successfully!');
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $category = NameModel::findOrFail($id);
        return view('name.show', compact('category'));
    }

    // Hiển thị form cập nhật sản phẩm
    public function edit($id)
    {
        $category = NameModel::findOrFail($id);
        return view('name.edit', compact('category'));
    }

    // Cập nhật sản phẩm trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $category = NameModel::findOrFail($id);
        $category->update($validated);

        return redirect()->route('name.index')->with('success', 'NameModel updated successfully!');
    }

    // Xóa sản phẩm
    public function destroy($id)
    {
        $category = NameModel::findOrFail($id);
        $category->delete();

        return redirect()->route('name.index')->with('success', 'NameModel deleted successfully!');
    }
}
```

---

## **5. Kết Luận**

Controller giúp tổ chức logic ứng dụng một cách rõ ràng, chia nhỏ các chức năng để dễ quản lý. Kết hợp với **routes** và **Blade templates**, bạn có thể xây dựng giao diện và xử lý logic đầy đủ cho ứng dụng của mình.
