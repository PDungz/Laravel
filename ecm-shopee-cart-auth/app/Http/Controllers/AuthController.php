<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\User;


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
        try {
            // Validate các trường đầu vào
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Tạo dữ liệu user mới
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ];

            // Tạo user mới với dữ liệu đầu vào
            $user = User::create($userData);
            $user->cart()->create([]);

            // Chuyển hướng sau khi đăng ký thành công
            return redirect()->route('login')->with('success', 'Registration successful! Please login.');
        } catch (\Exception $e) {
            // Ghi log lỗi để biết thêm chi tiết
            Log::error('Registration failed: ' . $e->getMessage());

            // Trả về thông báo lỗi và giữ lại thông tin đầu vào (ngoại trừ password)
            return redirect()->back()->withInput($request->except('password'))->with('error', 'Registration failed. Please try again.');
        }
    }

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

        if (Auth::attempt($request->only('email',  'password'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('products.index'));
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
