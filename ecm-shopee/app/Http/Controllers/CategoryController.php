<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các danh mục.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Lấy tất cả các danh mục từ cơ sở dữ liệu.
            $categories = Category::all();

            // Trả về view 'categories.index' với dữ liệu danh sách danh mục.
            return view('categories.index', compact('categories'));
        } catch (\Exception $e) {
            // Ghi log lỗi và trả về thông báo lỗi cho người dùng.
            Log::error("Lỗi khi lấy danh sách danh mục: " . $e->getMessage());
            return redirect()->route('categories.index')->with('error', 'Không thể lấy danh sách danh mục. Vui lòng thử lại sau.');
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
