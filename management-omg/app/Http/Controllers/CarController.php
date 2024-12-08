<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Car;
use App\Models\Category;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $cars = Car::when($search, function($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%");
            })->get();

            return view('cars.index', compact('cars', 'search'));
            // $cars = Car::all();
            // return view('cars.index', compact('cars'));
        } catch (\Exception $e) {
            return redirect()->route('cars.index')->with('error', 'Không thể tìm kiếm. Vui lòng thử lại sau');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $categories = Category::all();
            return view('cars.create', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->route('cars.index')->with('error', "Không thể hiển thị form thêm. Vui lòng thử lại.");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'manufacturer' => 'nullable|string',
                'price' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'color' => 'nullable|string|max:45',
                'yearOfManufacture' => 'required|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('cars', 'public');
            }

            Car::create([
                'name' => $request->name,
                'manufacturer' => $request->manufacturer,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'color' => $request->color,
                'yearOfManufacture' => $request->yearOfManufacture,
                'image' => $imagePath, // Lưu đường dẫn ảnh
            ]);

            return redirect()->route('cars.index')->with('success', 'Tạo thành công');
        } catch (\Exception $e) {
            return redirect()->route('cars.index')->with('error', 'Không thể tạo . Vui lòng thử lại.' . $e->getMessage())->withInput(); 
        }   
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        try {
            return view('cars.show', compact('car'));
        } catch (\Exception $e) {
            return redirect()->route('cars.index')
            ->with('error', 'Không thể hiển thị chi tiết. Vui lòng thử lại.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        try {
            $categories = Category::all();
            return view('cars.update', compact(['car', 'categories']));
        } catch (\Exception $e) {
            // Log lỗi nếu không thể lấy dữ liệu để chỉnh sửa
            Log::error("Lỗi khi hiển thị form chỉnh sửa: " . $e->getMessage());
            return redirect()->route('cars.index')->with('error', 'Không thể hiển thị form chỉnh sửa. Vui lòng thử lại.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $request->validate([
                'name' => 'required|string|max:255',
                'manufacturer' => 'nullable|string',
                'price' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'color' => 'nullable|string|max:45',
                'yearOfManufacture' => 'required|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Kiểm tra nếu có tệp ảnh mới được tải lên
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu có
                if ($car->image) {
                    Storage::disk('public')->delete($car->image);
                }

                // Lưu ảnh mới vào thư mục 'cars'
                $imagePath = $request->file('image')->store('cars', 'public');
                $car->image = $imagePath;
            }

            // Cập nhật các trường còn lại của
            $car->fill($request->only(['name', 'manufacturer', 'color', 'price', 'yearOfManufacture', 'category_id']));
            $car->save();

            // Chuyển hướng về danh sách với thông báo thành công
            return redirect()->route('cars.index')
                ->with('success', 'Cập nhật thành công.');
        } catch (\Exception $e) {
            // Log lỗi nếu có vấn đề khi cập nhật
            Log::error("Lỗi khi cập nhật: " . $e->getMessage());
            // Quay lại trang chỉnh sửa với thông báo lỗi
            return redirect()->back()->with('error', 'Không thể cập nhật. Vui lòng thử lại.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        try {
            // if ($car->image) {
            //     Storage::disk('public')->delete($car->image);
            // }

            $car->delete();
            return redirect()->route('cars.index')->with('success', 'Xóa thành công.');
        } catch (\Exception $e) {
            // Log lỗi nếu có vấn đề khi xóa
            Log::error("Lỗi khi xóa: " . $e->getMessage());
            return redirect()->route('cars.index')->with('error', 'Không thể xóa. Vui lòng thử lại.');
        }
    }
}
