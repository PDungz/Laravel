<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## **Hướng dẫn Cấu trúc và Sử dụng Dự án Laravel**

#### **1. Cấu trúc dự án Laravel**

Laravel có cấu trúc rõ ràng, giúp quản lý và mở rộng ứng dụng dễ dàng. Dưới đây là các thư mục và tệp quan trọng:

- **`app/`**:  
  Chứa logic ứng dụng chính.

  - `Models/`: Quản lý dữ liệu và tương tác với cơ sở dữ liệu.
  - `Http/Controllers/`: Xử lý các yêu cầu HTTP và trả về phản hồi.
  - `Http/Middleware/`: Logic trung gian, như kiểm tra quyền truy cập.

- **`bootstrap/`**:  
  Tệp khởi động ứng dụng (`app.php`).

- **`config/`**:  
  Chứa các tệp cấu hình như cơ sở dữ liệu (`database.php`), mail (`mail.php`), cache (`cache.php`).

- **`database/`**:  
  Quản lý cơ sở dữ liệu:

  - `migrations/`: Tạo và thay đổi bảng.
  - `seeders/`: Tạo dữ liệu mẫu.

- **`public/`**:  
  Chứa các tệp công khai như CSS, JavaScript, hình ảnh. Đây là thư mục chính ứng dụng phục vụ.

- **`resources/`**:

  - `views/`: Các tệp giao diện (Blade templates).
  - `lang/`: Các tệp ngôn ngữ.

- **`routes/`**:  
  Định nghĩa đường dẫn URL và ánh xạ tới Controller:

  - `web.php`: Các route giao diện người dùng.
  - `api.php`: Các route API.

- **`storage/`**:  
  Lưu trữ tạm thời (file upload, cache, log).

- **`tests/`**:  
  Chứa các tệp kiểm thử tự động.

- **`vendor/`**:  
  Chứa các thư viện từ Composer.

---

#### **2. Tệp `.env`**

Tệp `.env` lưu trữ các biến môi trường, cho phép cấu hình ứng dụng mà không cần thay đổi mã nguồn.

##### **Ví dụ nội dung tệp `.env`**

```env
APP_NAME=Laravel          # Tên ứng dụng
APP_ENV=local             # Môi trường (local, production, testing)
APP_KEY=base64:...        # Khóa mã hóa của ứng dụng
APP_DEBUG=true            # Bật hoặc tắt chế độ gỡ lỗi
APP_URL=http://localhost  # URL ứng dụng

# Cấu hình database
DB_CONNECTION=mysql       # Loại cơ sở dữ liệu (mysql, sqlite, pgsql,...)
DB_HOST=127.0.0.1         # Địa chỉ máy chủ cơ sở dữ liệu
DB_PORT=3306              # Cổng kết nối cơ sở dữ liệu
DB_DATABASE=laravel       # Tên cơ sở dữ liệu
DB_USERNAME=root          # Tên người dùng cơ sở dữ liệu
DB_PASSWORD=secret        # Mật khẩu cơ sở dữ liệu

# Cấu hình email
MAIL_MAILER=smtp          # Phương thức gửi mail (smtp, sendmail, mailgun,...)
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=example@example.com
MAIL_FROM_NAME="${APP_NAME}"

# Cấu hình cache và session
CACHE_DRIVER=file         # Loại driver cache (file, redis, memcached,...)
SESSION_DRIVER=file       # Driver lưu trữ session
SESSION_LIFETIME=120      # Thời gian sống của session (phút)

# Cấu hình queue (hàng đợi)
QUEUE_CONNECTION=sync     # Phương thức xử lý hàng đợi (sync, database, redis,...)

# Cấu hình dịch vụ khác (ví dụ: AWS, Stripe, ...)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

```

##### **Cách sử dụng tệp `.env`:**

- Truy cập biến môi trường bằng hàm `env()`:
  ```php
  $appName = env('APP_NAME');
  ```
- Các tệp trong thư mục `config/` sử dụng giá trị từ `.env`:
  ```php
  'name' => env('APP_NAME', 'Laravel'),
  ```

##### **Lưu ý quan trọng:**

- **Bảo mật:** Laravel tự động thêm `.env` vào `.gitignore`, đảm bảo tệp này không bị theo dõi trên Git.
- **Cập nhật cấu hình:** Sau khi thay đổi `.env`, chạy lệnh để áp dụng:
  ```bash
  php artisan config:cache
  ```

---

#### **3. Quy trình hoạt động trong Laravel**

1. **Route nhận yêu cầu:**  
   Định nghĩa URL và ánh xạ đến Controller trong `routes/web.php` hoặc `routes/api.php`.

2. **Middleware xử lý trung gian:**  
   Kiểm tra xác thực, quyền truy cập, hoặc xử lý trước/ sau khi yêu cầu.

3. **Controller xử lý logic:**  
   Lấy dữ liệu từ Model, xử lý logic nghiệp vụ, và trả về View hoặc JSON.

4. **Model quản lý cơ sở dữ liệu:**  
   Sử dụng Eloquent ORM để thực hiện các thao tác CRUD (Create, Read, Update, Delete).

5. **View hiển thị giao diện:**  
   Blade template kết hợp dữ liệu với HTML để hiển thị nội dung.

---

### **Kết luận**

Laravel cung cấp một cấu trúc dự án mạnh mẽ và dễ bảo trì, với các thành phần chính như Route, Middleware, Controller, Model và View. Tệp `.env` đảm bảo cấu hình linh hoạt giữa các môi trường, giúp phát triển và triển khai ứng dụng dễ dàng và hiệu quả.
