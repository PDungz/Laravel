<style>
    body {
        background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        margin: 0;
    }

    .login-form {
        max-width: 450px;
        padding: 2.5rem;
        border-radius: 15px;
        background-color: #ffffff;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-form h4 {
        font-weight: 700;
        color: #007BFF;
        text-align: center;
        margin-bottom: 2rem;
        font-size: 1.8rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #555;
        margin-left: 10px;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.9rem 1.25rem;
        font-size: 1rem;
        width: 100%;
        box-sizing: border-box;
        outline: none;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: #007BFF;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn-primary {
        background-color: #007BFF;
        border: none;
        border-radius: 50px;
        padding: 0.9rem;
        font-size: 1.1rem;
        transition: background-color 0.3s ease, transform 0.3s ease;
        width: 100%;
        color: white;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
    }

    .alert-danger {
        margin-bottom: 0.5rem;
        padding: 0.85rem;
        border-radius: 10px;
        font-size: 0.9rem;
        background-color: #f8d7da;
        color: #721c24;
    }

    .switch-text {
        text-align: center;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: #555;
    }

    .switch-text a {
        color: #007BFF;
        text-decoration: none;
    }

    .switch-text a:hover {
        text-decoration: underline;
    }
</style>

<form action="{{ url('login') }}" method="POST" class="login-form">
    @csrf
    <h4>Login</h4>
    <!-- Email -->
    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('email') }}" required>
        <!-- Hiển thị lỗi -->
        @error('email')
        <div class="alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Mật khẩu -->
    <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required>
        <!-- Hiển thị lỗi -->
        @error('password')
        <div class="alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Hiển thị thông báo thành công (nếu có) -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Hiển thị thông báo lỗi chung (nếu có) -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <button type="submit" class="btn btn-primary" id="loginButton">Login</button>

    <!-- Chuyển sang trang đăng nhập -->
    <div class="switch-text">
        Don't have an account? <a href="{{ url('register') }}">Register here</a>
    </div>
</form>