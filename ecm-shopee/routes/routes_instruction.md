Dưới đây là hướng dẫn đầy đủ về cách sử dụng **routes** trong Laravel, bao gồm các ví dụ code đầy đủ cho từng phần.

### **1. Định Nghĩa Routes Cơ Bản**

Trong Laravel, bạn sẽ định nghĩa các routes trong tệp `routes/web.php` cho giao diện người dùng và `routes/api.php` cho API. Dưới đây là một ví dụ về cách định nghĩa route cơ bản trong Laravel.

#### **Code Ví Dụ Route Cơ Bản**

```php
// routes/web.php

use Illuminate\Support\Facades\Route;

// Route GET cơ bản
Route::get('/', function () {
    return view('welcome');
});

// Route GET với tham số
Route::get('/category/{id}', function ($id) {
    return "Category ID: $id";
});

// Route POST để xử lý form
Route::post('/submit-form', function () {
    return 'Form submitted';
});
```

### **2. Routes Với Controller**

Thay vì sử dụng closures (hàm ẩn), bạn có thể sử dụng controllers để nhóm các phương thức xử lý của mình.

#### **Tạo Controller**

Đầu tiên, bạn cần tạo một controller. Sử dụng lệnh Artisan:

```bash
php artisan make:controller CategoryController
```

#### **Định Nghĩa Route Với Controller**

```php
// routes/web.php

use App\Http\Controllers\CategoryController;

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
```

#### **Code Controller**

```php
// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index()
    {
        return view('categories.index');
    }

    // Hiển thị chi tiết danh mục
    public function show($id)
    {
        return view('categories.show', compact('id'));
    }
}
```

### **3. Routes Với Middleware**

Middleware giúp bạn kiểm tra điều kiện trước khi yêu cầu đến controller. Ví dụ, bạn có thể chỉ cho phép người dùng đã đăng nhập truy cập một số route nhất định.

#### **Code Middleware Và Route**

```php
// routes/web.php

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('/profile', function () {
        return view('profile');
    });
});
```

#### **Code Middleware**

```php
// app/Http/Middleware/Authenticate.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guest()) {
            return redirect('login');
        }

        return $next($request);
    }
}
```

### **4. Route Resource (CRUD Routes)**

Laravel cung cấp một phương thức `Route::resource` để tự động tạo các routes cơ bản cho các thao tác CRUD (Create, Read, Update, Delete).

#### **Định Nghĩa Route Resource**

```php
// routes/web.php

use App\Http\Controllers\CategoryController;

Route::resource('categories', CategoryController::class);
```

#### **Tạo Controller Resource**

Nếu bạn tạo controller với tùy chọn `--resource`, Laravel sẽ tự động tạo các phương thức cho CRUD.

```bash
php artisan make:controller CategoryController --resource
```

#### **Code Controller Resource**

```php
// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

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

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
```

### **5. Sử Dụng Route Name**

Khi bạn gán tên cho route, bạn có thể dễ dàng tham chiếu đến chúng từ các nơi khác trong ứng dụng.

#### **Định Nghĩa Route Với Tên**

```php
// routes/web.php

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
```

#### **Sử Dụng Route Name Trong View**

```php
// resources/views/categories/index.blade.php

<a href="{{ route('categories.index') }}">Danh sách danh mục</a>
```

### **6. Redirect Routes**

Để chuyển hướng từ một route này đến một route khác, bạn có thể sử dụng phương thức `redirect`.

```php
// routes/web.php

Route::redirect('/old-url', '/new-url');
```

Hoặc chuyển hướng đến một route đã định nghĩa:

```php
Route::get('/home', function () {
    return redirect()->route('home.dashboard');
});
```

### **7. Route Parameters**

Bạn có thể định nghĩa các tham số trong routes để xử lý URL động.

#### **Route Với Tham Số Bắt Buộc**

```php
// routes/web.php

Route::get('/category/{id}', function ($id) {
    return "Category ID: $id";
});
```

#### **Route Với Tham Số Tùy Chọn**

```php
// routes/web.php

Route::get('/category/{id?}', function ($id = null) {
    return $id ? "Category ID: $id" : 'No category ID provided';
});
```

#### **Route Với Validation Tham Số**

```php
// routes/web.php

Route::get('/category/{id}', function ($id) {
    return "Category ID: $id";
})->where('id', '[0-9]+');
```

### **8. Route Prefixes**

Bạn có thể nhóm các route lại với một tiền tố chung, chẳng hạn như `/admin` hoặc `/user`.

```php
// routes/web.php

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::get('/settings', function () {
        return view('admin.settings');
    });
});
```

Kết quả là các route này sẽ có tiền tố `/admin`, ví dụ `/admin/dashboard` và `/admin/settings`.

---

### **Tóm Tắt Code Đầy Đủ**

```php
// routes/web.php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Route Cơ Bản
Route::get('/', function () {
    return view('welcome');
});

// Route GET với tham số
Route::get('/category/{id}', function ($id) {
    return "Category ID: $id";
});

// Route POST
Route::post('/submit-form', function () {
    return 'Form submitted';
});

// Route với Controller
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::resource('categories', CategoryController::class);

// Route với Middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::get('/profile', function () {
        return view('profile');
    });
});
```

Các ví dụ trên cung cấp cái nhìn tổng quát về cách định nghĩa và sử dụng routes trong Laravel, bao gồm cách xử lý yêu cầu HTTP, sử dụng controllers, middleware, gán tên cho route và làm việc với tham số động.
