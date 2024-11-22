<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;

class UrProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $products = Product::when($search, function($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('quantity', 'LIKE', "%{$search}%")
                ->orWhere('entry_date', 'LIKE', "%{$search}%")
                ->orWhere('price', 'LIKE', "%{$search}%");
            })->get();

            return view('products.index', compact('products', 'search'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Không thể tìm kiếm. Vui lòng thử lại sau');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $categories = Category::all();
            return view('products.create', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', "Không thể hiển thị form thêm $e. Vui lòng thử lại.");
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
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'quantity' => 'required|integer|min:0',
                'entry_date' => 'required|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'quantity' => $request->quantity,
                'entry_date' => $request->entry_date,
                'image' => $imagePath, // Lưu đường dẫn ảnh
            ]);

            return redirect()->route('products.index')->with('success', 'Tạo thành công');
        } catch (\Exception $e) {
            Log::error("Lỗi khi thêm sản phẩm: " . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Không thể tạo . Vui lòng thử lại.')->withInput(); 
        }   
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')
            ->with('error', 'Không thể hiển thị chi tiết. Vui lòng thử lại.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        try {
            $categories = Category::all();
            return view('products.update', compact(['product', 'categories']));
        } catch (\Exception $e) {
            // Log lỗi nếu không thể lấy dữ liệu để chỉnh sửa
            Log::error("Lỗi khi hiển thị form chỉnh sửa sản phẩm: " . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Không thể hiển thị form chỉnh sửa sản phẩm. Vui lòng thử lại.');
        }
    }

    /**
     * Update the specified resource in storage.
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'entry_date' => 'required|date',
                'category_id' => 'required|exists:categories,id',
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
            $product->fill($request->only(['name', 'description', 'price', 'quantity', 'entry_date', 'category_id']));
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
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();
            return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công.');
    } catch (\Exception $e) {
        // Log lỗi nếu có vấn đề khi xóa sản phẩm
        Log::error("Lỗi khi xóa sản phẩm: " . $e->getMessage());
        return redirect()->route('products.index')->with('error', 'Không thể xóa sản phẩm. Vui lòng thử lại.');
    }
    }
}
