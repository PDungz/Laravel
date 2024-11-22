<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/PDungz/Laravel">
  <img src="https://img.shields.io/badge/-GitHub%20Phùng Văn Dũng-black?logo=github&logoColor=white" alt="GitHub Channel">
</a>
<a href="https://youtube.com/@pvdnocode3623?si=o3UX8WHisI5mAVCu">
  <img src="https://img.shields.io/badge/-Youtube%20Pvd NoCode-black?logo=youtube&logoColor=red" alt="Youtube Channel">
</a>
</p>

# **Hướng dẫn tích hợp xác thực đăng nhập, đăng xuất**

Để thực hiện từ phần đăng nhập cho đến quản lý sản phẩm và danh mục, chúng ta sẽ chia quá trình thành các bước cụ thể. Dưới đây là hướng dẫn đầy đủ từ việc tạo tài khoản, đăng nhập, phân quyền người dùng đến quản lý sản phẩm và danh mục trong Laravel.

Nếu bạn muốn đặt các file **product** và **category** trong thư mục **admin** thay vì ở cấp cao trong thư mục **views**, bạn có thể cấu trúc lại thư mục **views** như sau để quản lý rõ ràng hơn giữa các khu vực người dùng và quản trị viên.

### **Cấu trúc thư mục mới:**

```
resources/
├── views/
│   ├── auth/
│   │   ├── login.blade.php         // Form đăng nhập cho quản trị viên
│   │   ├── register.blade.php      // Form đăng ký cho quản trị viên
│   │   └── forgot-password.blade.php // Form quên mật khẩu cho quản trị viên
│   ├── admin/
│   │   ├── products/
│   │   │   ├── index.blade.php         // Danh sách sản phẩm
│   │   │   ├── create.blade.php        // Form thêm sản phẩm
│   │   │   ├── edit.blade.php          // Form chỉnh sửa sản phẩm
│   │   │   └── show.blade.php          // Chi tiết sản phẩm
│   │   ├── categories/
│   │   │   ├── index.blade.php         // Danh sách danh mục
│   │   │   ├── create.blade.php        // Form thêm danh mục
│   │   │   ├── edit.blade.php          // Form chỉnh sửa danh mục
│   │   │   └── show.blade.php          // Chi tiết danh mục
│   ├── layouts/
│   │   └── app.blade.php       // Trang quản lý của admin (Dashboard)
│   ├── products/
│   │   ├── index.blade.php         // Danh sách sản phẩm
│   │   └── show.blade.php
│   └── welcome.blade.php
```

### 1. **Tạo Controller cho Đăng nhập và Đăng ký**

#### 1.1. Tạo Controller `AuthController`

Chạy lệnh tạo controller:

```bash
php artisan make:controller AuthController
```

Trong `AuthController.php`, bạn sẽ tạo các phương thức cho đăng ký, đăng nhập, và đăng xuất như sau:

```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Hiển thị form đăng ký
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký người dùng
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            Log::info('Registering user with email: ' . $request->email);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);

            Log::info('User registered successfully');
            return redirect()->route('login')->with('success', 'Registration successful! Please login.');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập người dùng
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('welcome'));
        }

        return redirect()->back()->with('error', 'The provided credentials are incorrect.');
    }

    // Xử lý đăng xuất người dùng
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
```

#### 1.2. Tạo Route cho Đăng ký, Đăng nhập, Đăng xuất

Mở file `routes/web.php` và thêm các route sau:

```php
<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UrProductController;


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

Route::get('/', function () {
    return view('welcome');
});


Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login',  [AuthController::class,  'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard',  [AdminController::class,  'dashboard'])->name('admin.dashboard');

    Route::resource('/admin/products', ProductController::class, [
        'as' => 'admin' // Đặt tiền tố 'admin' cho tất cả các route của products
    ]);
    Route::resource('/admin/categories', CategoryController::class, [
        'as' => 'admin' // Đặt tiền tố 'admin' cho tất cả các route của products
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

### 2. **Tạo Middleware Kiểm tra Phân quyền**

#### 2.1. Tạo Middleware `AdminMiddleware`

Chạy lệnh tạo middleware:

```bash
php artisan make:middleware AdminMiddleware
```

Trong `AdminMiddleware.php`, thêm mã kiểm tra quyền của người dùng:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }

        return redirect()->route('welcome');
    }
}
```

#### 2.2. Đăng ký Middleware

Mở file `app/Http/Kernel.php` và thêm middleware vào mảng `routeMiddleware`:

```php
protected $routeMiddleware = [
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

### 3. **Tạo Giao diện Đăng ký và Đăng nhập**

#### 3.1. Tạo Form Đăng ký (View)

Tạo file `resources/views/auth/register.blade.php` với nội dung:

#### 3.2. Tạo Form Đăng nhập (View)

Tạo file `resources/views/auth/login.blade.php` với nội dung:

### 4. **Quản lý Sản phẩm và Danh mục cho Admin**

#### 4.1. Tạo Controller `AdminController`

Chạy lệnh tạo controller:

```bash
php artisan make:controller AdminController
```

Trong `AdminController.php`, thêm các phương thức quản lý sản phẩm và danh mục như sau:

```php
namespace App\Http\Controllers;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Hiển thị trang dashboard cho admin
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Quản lý sản phẩm
    public function products()
    {
        return app(ProductController::class)->index();
    }

    // Quản lý danh mục
    public function categories()
    {
        return app(CategoryController::class)->index();
    }
}
```

#### 4.2. Tạo Route cho Admin

Trong `routes/web.php`, tạo các route cho trang quản lý sản phẩm và danh mục:

```php
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'admin'])->group(function () {
    // Route dashboard cho admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Route quản lý sản phẩm
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    // Route quản lý danh mục
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
});
```

### 5. **Cập nhật Layout `app.blade.php`**

Trong `resources/views/layouts/app.blade.php`, bạn có thể thêm các liên kết đến trang đăng ký và đăng nhập như sau:
